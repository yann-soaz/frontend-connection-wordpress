<form action="" id="login-front-form">
  <div>
    <label for="user_mail">Adresse email ou nom d'utilisateur</label>
    <input type="text" class="user_mail" name="user_mail">
  </div>
  <div>
    <label for="user_pass">Mot de passe</label>
    <input type="password" class="user_pass" name="user_pass">
  </div>
  <div>
    <?php wp_nonce_field( 'ys-login-nonce', 'ys-login' ); ?>
    <input type="submit" value="se connecter">
    <?php if (!empty($ys_options['allow_register']) && $ys_options['allow_register'] == 1) : ?>
      <a href="#" id="ys-inscription">Créer un compte</a>
    <?php endif; ?>
  </div>
</form>