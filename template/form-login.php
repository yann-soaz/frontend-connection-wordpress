<form action="" id="login-front-form">
  <div>
    <label for="user_name">Adresse email</label>
    <input type="text" class="user_mail" name="user_name">
  </div>
  <div>
    <label for="user_pass">Mot de passe</label>
    <input type="password" class="user_pass" name="user_pass">
  </div>
  <div>
    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
    <input type="submit" value="se connecter">
  </div>
</form>