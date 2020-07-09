<form action="" id="subscribe-front-form" style="display:none;">
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
    <?php wp_nonce_field( 'ys-subscribe-nonce', 'ys-subscribe' ); ?>
    <input type="submit" value="s'inscrire">
    <a href="#" id="ys-connection">Se connecter</a>
  </div>
</form>
