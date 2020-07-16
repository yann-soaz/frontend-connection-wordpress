<?php

/**
 * active les routes ajax de connection et d'inscription
 */
function ys_ajax_login_init(){

  // Enable the user with no privileges to run ajax_login() in AJAX
  add_action( 'wp_ajax_nopriv_yslogin', 'ys_login' );
  add_action( 'wp_ajax_nopriv_yssubscribe', 'ys_suscribe' );
  add_action( 'wp_ajax_nopriv_yspassword', 'ys_password' );
  add_action( 'wp_ajax_nopriv_ysreset', 'ys_reset' );
  add_action( 'wp_ajax_ysprofil', 'ys_profil' );
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
      send_json(array('respont'=>false, 'message'=>__('Connexion impossible, mauvais mot de passe ou mauvaise adresse mail.')));
  } else {
    global $current_user;
    $current_user = wp_set_current_user($user_signon->ID);
    do_action( 'wp_login', $user_signon->user_login );
    wp_set_auth_cookie($user_signon->ID, true);
    send_json(array('respont'=>true, 'message'=>__('Bienvenu !')));
  }
}

/**
 * fonction de la route ajax d'inscription
 */
function ys_suscribe() {

  check_ajax_referer( 'ys-subscribe-nonce', 'security' );
  if (empty($_POST['user_password']) || empty($_POST['user_email'])) {
    send_json(array('respont'=>false, 'message'=>__('Vous n\'avez pas renseigné tout les champs.')));
  }
  if ($_POST['user_password'] == $_POST['user_pass_confirm']) {
    $ys_options = get_option( 'ys_connection' );
    $info = array();
    $info['role'] = (!empty($ys_options['role_created'])) ? $ys_options['role_created'] : 'subscriber';
    $info['user_email'] = $_POST['user_email'];
    $info['user_pass'] = $_POST['user_password'];
    $info['user_login'] = explode('@', $_POST['user_email'])[0];
    
    if (filter_var($info['user_email'], FILTER_VALIDATE_EMAIL)) {
      if (email_exists($info['user_email'])) {
        send_json(array('respont'=>false, 'message'=>__('Cette adresse de courriel est déjà associée à un compte utilisateur.')));
      }
      $registration = wp_insert_user( $info );
      if ( is_wp_error($registration) ){
        send_json(array('respont'=>false, 'message'=>__('Nous rencontrons actuellement une erreur, merci de réessayer plus tard.')));
      } else {
        send_json(array('respont'=>true, 'message'=>__('Votre compte à été créé avec succès. Vous pouvez désormais vous connecter avec les identifiants créés.')));
      }
    } else {
      send_json(array('respont'=>false, 'message'=>__('L\'adresse email  n\'est pas valide.')));
    }

  } else {
    send_json(array('respont'=>false, 'message'=>__('Les mots de passe ne correspondent pas.')));
  }
}
/**
 * fonction de la route ajax de réinitialisation de mot de passe
 */
function ys_password() {
  check_ajax_referer( 'ys-forget-nonce', 'security' );

  $email = $_POST['user_email'];
  if (empty($email)) {
    send_json(array('respont'=>false, 'message'=>__('L\'adresse email est obligatoire.')));
  } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
    send_json(array('respont'=>false, 'message'=>__('L\'adresse email n\'est pas valide.')));
  }

  $user = get_user_by('email', $email);
  if (!$user) {
    send_json(array('respont'=>false, 'message'=>__('Nous n\'avons pas été en mesure de retrouver cet utilisateur.')));
  }
  // function to get reset pass
  $token = get_password_reset_key( $user );

  ob_start();
  global $ys_connection_dir;
  include $ys_connection_dir.'/template/mail-password.php';
  $msg = ob_get_contents();
  ob_end_clean();

  if (wp_mail( $email, __('Réinitialisation de votre mot de passe'), $msg, array('Content-Type: text/html; charset=UTF-8') )) {
    send_json(array('respont'=>true, 'message'=>__('Un email vient de vous être renvoyé afin de vous permettre de changer de mot de passe.')));
  } else {
    send_json(array('respont'=>false, 'message'=>__('Une erreur à été détectée lors de l\'envoi du mail, veuillez ressayer plus tard.')));
  }
}

/**
 * fonction de la route ajax afin d'enregistrer un nouveau mot de passe
 */
function ys_reset() {
  check_ajax_referer( 'ys-reset-nonce', 'ys-reset' );

  $user = check_password_reset_key( $_POST['token'], $_POST['login'] );
  if (is_wp_error($user)) {
    send_json(array('respont'=>false, 'message'=>__('Impossible de réinitialiser le mot de passe, le lien n\'est peut-être plus valide.')));
  } 

  if ($_POST['password'] != $_POST['pass_confirm']) {
    send_json(array('respont'=>false, 'message'=>__('Les mots de passe ne correspondent pas.')));
  }

  $update = wp_update_user(['ID' => $user->ID, 'user_pass' => $_POST['password']]);
  if (is_wp_error($update)) {
    send_json(array('respont'=>false, 'message'=>__('Nous avons rencontré une erreur lors de la mise à jour du mot de passe.')));
  }
  send_json(array('respont'=>true, 'message'=>__('Votre mot de passe à bien été modifié.')));
}

/**
 * fonction de la route ajax de mise a jour de profil
 */
function ys_profil() {
  check_ajax_referer( 'ys-user-nonce', 'ys-user' );
  
  $user_id = get_current_user_id();
  $user = get_userdata($user_id); 

  if (!empty($_POST['user_email'])) {
    if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)){ 
      send_json(array('respont'=>false, 'message'=>__('L\'adresse email n\'est pas valide.')));
    } else {
      $user->user_email = esc_attr($_POST['user_email']);
    }
  }
  if (!empty($_POST['user_pass'])) {
    if($_POST['user_pass'] != $_POST['user_pass_confirm']){ 
      send_json(array('respont'=>false, 'message'=>__('Les mots de passe ne correspondent pas.')));
    } else {
      $user->user_pass = $_POST['user_pass'];
    }
  } 
  if (!empty($_POST['user_nicename'])) {
    $user->user_nicename = esc_attr($_POST['user_nicename']);
    $user->display_name = esc_attr($_POST['user_nicename']);
  }

  if (!empty($_POST['user_firstname'])) {
    $user->first_name = esc_attr($_POST['user_firstname']);
  }
  if (!empty($_POST['user_lastname'])) {
    $user->last_name = esc_attr($_POST['user_lastname']);
  }

  if (!empty($_POST['user_url'])) {
    $user->user_url = esc_attr($_POST['user_url']);
  }

  $update = wp_update_user($user);
  if (is_wp_error($update)) {
    send_json(array('respont'=>false, 'message'=>__('Nous rencontrons actuellement une erreur, veuillez réessayer plus tard.')));
  } else {
    send_json(array('respont'=>true, 'message'=>__('Votre profil à bien été mis à jour.')));
  }
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
          $error->add('invalid', __('Le mot de passe ou l\'adresse email ne sont pas valide !'));
          return $error;
      }
      else{ //check password
          if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
              $error = new WP_Error();
              $error->add('invalid', __('Le mot de passe ou l\'adresse email ne sont pas valide !'));
              return $error;
          }else{
              return $user; //passed
          }
      }
}, 20, 3);

function send_json (array $datas) {
  echo json_encode($datas);
  die;
}