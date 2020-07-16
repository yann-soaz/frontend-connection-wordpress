<form action="" id="subscribe-front-form" style="display:none;">
  <div>
    <label for="user_mail"><?= __('email') ?></label>
    <input type="text" class="user_mail" name="user_mail">
  </div>
  <div>
    <label for="user_pass"><?= __('Mot de passe') ?></label>
    <input type="password" class="user_pass" name="user_pass">
  </div>
  <div>
    <label for="user_pass"><?= __('Confirmation du nouveau mot de passe') ?></label>
    <input type="password" class="user_pass_confirm" name="user_pass">
  </div>
  <div>
    <?php wp_nonce_field( 'ys-subscribe-nonce', 'ys-subscribe' ); ?>
    <input type="submit" value="<?= __('Je créé mon compte') ?>">
    <a href="#" id="ys-connection"><?= __('se connecter') ?></a>
  </div>
</form>
