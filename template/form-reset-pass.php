<form action="" id="reset-front-form">
  <div>
    <label for="password"><?= __('Nouveau mot de passe') ?></label>
    <input type="password" class="password" name="password">
  </div>
  <div>
    <label for="pass_confirm"><?= __('Confirmation du nouveau mot de passe') ?></label>
    <input type="password" class="pass_confirm" name="pass_confirm">
  </div>
  <div>
    <?php wp_nonce_field( 'ys-reset-nonce', 'ys-reset' ); ?>
    <input type="hidden" name="login" value="<?= $_GET['login'] ?>">
    <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
    <input type="submit" value="<?= __('Enregistrer') ?>">
  </div>
</form>