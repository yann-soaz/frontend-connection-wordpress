<?php

/**
 * fonction qui récupère le html des formulaires de connection
 */
function login_form(){
  global $ys_connection_dir;
  $return = null;
  if (!is_user_logged_in()) {
    ob_start();
    include $ys_connection_dir.'/template/form-connection.php';
    $return = ob_get_contents();
    ob_clean();
  } else {
    ob_start();
    include $ys_connection_dir.'/template/form-profil.php';
    $return = ob_get_contents();
    ob_clean();
  }
  return $return;
}

/**
 * Enregistrement du shortcode [login /] pour affichage des formulaires de connection
 */
function register_shortcodes(){
  add_shortcode('ys_login', 'login_form');
}
add_action( 'init', 'register_shortcodes'); 


/**
 * pre_process_shortcode
 * vérifie si le code de connection est disponnible dans la page, et si l'utilisateur est connecté.
 * si oui : redirection
 */
// function pre_process_shortcode() {
//   global $post;
//   if (!empty($post->post_content)) {
//     $regex = get_shortcode_regex();
//     preg_match_all('/'.$regex.'/',$post->post_content,$matches);
//     if (!empty($matches[2]) && in_array('login',$matches[2]) && is_user_logged_in()) {
//       $ys_options = get_option( 'ys_connection' );
//       if (!empty($ys_options['redirect_url'])) {
//         header('location: '.$ys_options['redirect_url']);
//       } else {
//         header('location: '.site_url());
//       }
//     }
//   }
// }
// add_action('template_redirect','pre_process_shortcode',1);
