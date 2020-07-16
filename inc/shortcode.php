<?php

/**
 * fonction qui récupère le html des formulaires de connection
 */
function login_form(){
  global $ys_connection_dir;
  $return = null;
  if (!empty($_GET['token']) && !empty($_GET['login'])) {
    ob_start();
    include $ys_connection_dir.'/template/form-reset-pass.php';
    $return = ob_get_contents();
    ob_clean();
  } elseif (!is_user_logged_in()) {
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