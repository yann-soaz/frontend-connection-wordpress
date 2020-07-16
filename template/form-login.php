<form action="" id="login-front-form">
  <div>
    <label for="user_mail"><?= __('Adresse email ou nom d\'utilisateur') ?></label>
    <input type="text" class="user_mail" name="user_mail">
  </div>
  <div>
    <label for="user_pass"><?= __('Mot de passe') ?></label>
    <input type="password" class="user_pass" name="user_pass">
  </div>
  <div>
    <?php wp_nonce_field( 'ys-login-nonce', 'ys-login' ); ?>
    <input type="submit" value="<?= __('se connecter') ?>">
    <?php if (!empty($ys_options['allow_register']) && $ys_options['allow_register'] == 1) : ?>
      <a href="#" id="ys-inscription"><?= __('Créer un compte') ?></a>
    <?php endif; ?>
    <div>
    <a href="#" id="ys-forgot-pass"><small><?= __('Mot de passe oublié') ?></small></a>
    </div>
  </div>
</form>