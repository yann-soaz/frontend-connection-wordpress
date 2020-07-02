<form action="" id="subscribe-front-form">
  <div>
    <label for="user_mail">Adresse e-mail</label>
    <input type="text" class="user_mail" name="user_mail">
  </div>
  <div>
    <label for="user_pass">Mot de passe</label>
    <input type="password" class="user_pass" name="user_pass">
  </div>
  <div>
    <label for="user_pass">Confirmez le mot de passe</label>
    <input type="password" class="user_pass_confirm" name="user_pass">
  </div>
  <div>
    <?php wp_nonce_field( 'ajax-subscribe-nonce', 'security' ); ?>
    <input type="submit" value="s'inscrire">
  </div>
</form>
