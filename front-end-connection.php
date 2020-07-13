<?php
/**
 * Plugin Name: Front-end Connection
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: Plugin to ennable front-end connection for users.
 * Version: 1.0
 * Author: Yann-Soaz
 * Author URI: https://yann-soaz.github.io/
 */

$ys_connection_dir = __DIR__;
$ys_connection_file = __FILE__;

/**
 * installation du plugin :
 * création de la page de connection et des options de base
 */
function ys_frontend_connection_install () {

  $my_post = array(
    'post_title'    => 'Connection',
    'post_content'  => '[ys_login]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page'
  );

  // Insert the post into the database
  $id = wp_insert_post( $my_post );
  $connection_link = get_page_link($id);
  add_option('ys_connection', [
    'hide_admin'=> 1,
    'allow_register'=> 1,
    'redirect_url'=> home_url(),
    'connection_url'=> $connection_link,
    'allow_admin'=> ['administrator'],
    'role_created'=> 'contributor',
  ]);
}

register_activation_hook( __FILE__, 'ys_frontend_connection_install' );

/**
 * désinstallation du plugin :
 * suppression des options de base
 */
function ys_frontend_connection_uninstall () {
  delete_option('ys_connection');
}

register_deactivation_hook( __FILE__, 'ys_frontend_connection_uninstall' );

// page d'administration
include 'inc/settings.php';
// paramètre à l'initialisation du site
include 'inc/init.php';
// déclaration du shortcode
include 'inc/shortcode.php';
// routes ajax pour la connection
include 'inc/ajax.php';