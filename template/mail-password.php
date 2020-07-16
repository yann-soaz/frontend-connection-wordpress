<html>
  <body>
    <table style="width:800px; max-width:90%;">
      <tr>
        <td style="text-align: center">
          <?php
            if ( function_exists('has_custom_logo') && has_custom_logo() ) {
              $custom_logo_id = get_theme_mod( 'custom_logo' );
              $logo = wp_get_attachment_image_src( $custom_logo_id , 'medium' );
              echo '<img src="' . esc_url( $logo ) . '" style="max-width:400px;" alt="' . get_bloginfo( 'name' ) . '">';
            } else {
              echo '<h1>'. get_bloginfo( 'name' ) .'</h1>';
            } 
          ?>
        </td>
      </tr>
      <tr>
        <td style="text-align: center">
            <p><?= __('Bonjour,') ?></p>
            <p><?= __('Vous recevez ce mail car vous avez demandé la réinitialisation de votre mot de passe.') ?></p>
            <p><?= __('Si vous n\'êtes pas à l\'origine de la demande, ignorez ce message, dans le cas contraire, veuillez cliquer sur le lien ci-dessous pour choisir un nouveau mot de passe.') ?></p>
        </td>
      </tr>
      <tr>
        <td style="text-align: center">
          <?php 
          $ys_options = get_option( 'ys_connection' );
          if (!empty($ys_options['connection_url'])) {
            $url = $ys_options['connection_url'];
          } else {
            $url = home_url();
          }
          ?>
          <a href="<?= $url.'?token='.$token.'&login='.$user->user_login ?>" style="display:inline-block; border-radius:4px; padding: 10px 20px; background-color: #333333; color: #f3f3f3; display:inline-block; text-decoration:none;"><?= __('Réinitialiser votre mot de passe') ?></a>
        </td>
      </tr>
    </table>
  </body>
</html>