<form action="" id="forget-front-form" style="display:none">
  <div>
    <label for="user_mail"><?= __('Adresse email') ?></label>
    <input type="text" class="user_mail" name="user_mail">
  </div>
  <div>
    <?php wp_nonce_field( 'ys-forget-nonce', 'ys-forget' ); ?>
    <input type="submit" value="<?= __('Réinitialiser le mot de passe') ?>">
    <a href="#" id="ys-return-connect"><small><?= __('Annuler') ?></small></a>
  </div>
</form>