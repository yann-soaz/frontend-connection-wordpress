<?php

/*************************************
************************************** Supprime wp-login.php
*************************************/

add_action('init', 'prevent_wp_login');

function prevent_wp_login() {
    if(!is_admin()) {
    global $pagenow;
    // Check if a $_GET['action'] is set, and if so, load it into $action variable
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    // Check if we're on the login page, and ensure the action is not 'logout'
    if( $pagenow == 'wp-login.php' && $action != 'logout' ) {
      $page = '/';
      wp_redirect($page);
      exit();
    }
  }
}



/*************************************
************************************** Supprime wp-admin
*************************************/
add_action( 'admin_init', 'block_wp_admin' );

function block_wp_admin() {
  $allowed = allowed_to_see_admin();
  if ( (!is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX) ) || !$allowed ) {
    wp_safe_redirect( home_url() );
    exit;
  }
}


/*************************************
************************************** ENLEVE LA BARRE ADMIN SI N'EST PAS ADMINISTRATEUR
*************************************/
function non_admin_remove_admin_bar() {
  $allowed = allowed_to_see_admin();
	if((!current_user_can('administrator') && !is_admin()) || !$allowed) {
  		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'non_admin_remove_admin_bar');


/**
 * user_has_role
 * vérifie si l'utilisateur connecté à un role spécifique
 *
 * @param  string $role
 * @return boolean
 */
function user_has_role (string $role) {
  $user = wp_get_current_user();
  return in_array( $role, (array) $user->roles );
}

/**
 * allowed_to_see_admin
 * défini si l'utilisateur peut avoir accès à l'administration en fonction des informations du plugin
 */
function allowed_to_see_admin () {
  $ys_options = get_option( 'ys_connection' );
  $allowed = true;
  if (isset($ys_options['hide_admin']) && $ys_options['hide_admin'] == 1) {
    $allowed = false;
    if (isset($ys_options['allow_admin'])) {
      foreach($ys_options['allow_admin'] as $role) {
        if (user_has_role($role)) {
          $allowed = true;
        }
      }
    }
  }

  return $allowed;
}