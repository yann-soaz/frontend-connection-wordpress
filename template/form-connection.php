<?php $ys_options = get_option( 'ys_connection' ); ?>
<section id="login-content">
  <?php include $ys_connection_dir.'/template/form-login.php'; ?>
  <?php if (!empty($ys_options['allow_register']) && $ys_options['allow_register'] == 1) : ?>
      <?php include $ys_connection_dir.'/template/form-subscribe.php'; ?>
  <?php endif; ?>
</section>