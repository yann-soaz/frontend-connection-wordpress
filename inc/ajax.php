<?php

/**
 * active les routes ajax de connection et d'inscription
 */
function ys_ajax_login_init(){

  // Enable the user with no privileges to run ajax_login() in AJAX
  add_action( 'wp_ajax_nopriv_yslogin', 'ys_login' );
  add_action( 'wp_ajax_nopriv_yssubscribe', 'ys_suscribe' );
}
add_action('init', 'ys_ajax_login_init');


/**
 * fonction de la route ajax de connection
 */
function ys_login(){
  check_ajax_referer( 'ys-login-nonce', 'security' );

  $info = array();
  $info['user_login'] = $_POST['user_email'];
  $info['user_password'] = $_POST['user_password'];
  $info['remember'] = true;

  $user_signon = wp_signon( $info, false );
  if ( is_wp_error($user_signon) ){
      echo json_encode(array('respont'=>false, 'message'=>'connexion impossible, mauvais mot de passe ou mauvaise adresse mail.'));
  } else {
    global $current_user;
    $current_user = wp_set_current_user($user_signon->ID);
    do_action( 'wp_login', $user_signon->user_login );
    wp_set_auth_cookie($user_signon->ID, true);
    echo json_encode(array('respont'=>true, 'message'=>'Bienvenu !'));
  }

  die();
}

/**
 * fonction de la route ajax d'inscription
 */
function ys_suscribe() {

  check_ajax_referer( 'ys-subscribe-nonce', 'security' );
  
  if ($_POST['user_password'] == $_POST['user_pass_confirm']) {
    $ys_options = get_option( 'ys_connection' );
    $info = array();
    $info['role'] = (!empty($ys_options['role_created'])) ? $ys_options['role_created'] : 'subscriber';
    $info['user_email'] = $_POST['user_email'];
    $info['user_pass'] = $_POST['user_password'];
    $info['user_login'] = explode('@', $_POST['user_email'])[0];
    
    if (filter_var($info['user_email'], FILTER_VALIDATE_EMAIL)) {
      $registration = wp_insert_user( $info );
      if ( is_wp_error($registration) ){
          echo json_encode(array('respont'=>false, 'message'=>'Nous rencontrons actuellement une erreur, merci de réessayer plus tard.', 'code' => $registration->get_error_message()));
      } else {
          echo json_encode(array('respont'=>true, 'message'=>'Votre compte à été créé avec succès. Vous pouvez désormais vous connecter avec les identifiants créés.'));
      }
    } else {
      echo json_encode(array('respont'=>false, 'message'=>'L\'adresse de courriel n\'est pas valide.'));
    }

  } else {
    echo json_encode(array('respont'=>false, 'message'=>'Les mots de passe ne correspondent pas.'));

  }

  die();
}

/*************************************
************************************** CONNECTION PAR ADRESSE MAIL
*************************************/

remove_filter('authenticate', 'wp_authenticate_username_password', 20);
add_filter('authenticate', function($user, $email, $password){
 
  //Check for empty fields
      if(empty($email) || empty ($password)){        
          //create new error object and add errors to it.
          $error = new WP_Error();

          if(empty($email)){ //No email
              $error->add('empty_username', __('<strong>ERROR</strong>: Email field is empty.'));
          }
          else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
              $error->add('invalid_username', __('<strong>ERROR</strong>: Email is invalid.'));
          }

          if(empty($password)){ //No password
              $error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
          }

          return $error;
      }

      //Check if user exists in WordPress database
      $user = get_user_by('email', $email);
      if (!$user) {
        $user = get_user_by('login', $email);
      }
 
      //bad email
      if(!$user){
          $error = new WP_Error();
          $error->add('invalid', 'Le mot de passe ou l\'adresse email sont invalide !');
          return $error;
      }
      else{ //check password
          if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
              $error = new WP_Error();
              $error->add('invalid', 'Le mot de passe ou l\'adresse email sont invalide !');
              return $error;
          }else{
              return $user; //passed
          }
      }
}, 20, 3);

