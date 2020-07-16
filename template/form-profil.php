<?php 
  $user_id = get_current_user_id();
  $user = get_userdata($user_id); 
?>

<h1><?= $user->user_login ?></h1>
<form id="front-end-profil">
  <fieldset>
    <label for="user_nicename"><?= __('Nom d\'utilisateur') ?></label>
    <input type="text" name="user_nicename" id="user_nicename"  value="<?= $user->user_nicename ?>">
  </fieldset>
  <fieldset>
    <button id="change-password"><?= __('Modifier le mot de passe') ?></button>
    <div id="new-password" style="display: none">
      <label for="user_pass"><?= __('Nouveau mot de passe') ?></label>
      <input type="password" name="user_pass" id="user_pass">
      <label for="user_pass_confirm"><?= __('Confirmation du nouveau mot de passe') ?></label>
      <input type="password" name="user_pass_confirm" id="user_pass_confirm">
    </div>
  </fieldset>
  <fieldset>
    <label for="last_name"><?= __('Nom') ?></label>
    <input type="text" name="user_lastname" id="user_lastname"  value="<?= $user->last_name ?>">
    <label for="first_name"><?= __('Prénom') ?></label>
    <input type="text" name="user_firstname" id="user_firstname"  value="<?= $user->first_name ?>">
  </fieldset>
  <fieldset>
    <label for="user_email"><?= __('email') ?></label>
    <input type="email" required name="user_email" id="user_email" value="<?= $user->user_email ?>">
    <label for="user_url"><?= __('Site web') ?></label>
    <input type="text" name="user_url" id="user_url" value="<?= $user->user_url ?>">
  </fieldset>
  <div>
    <?php wp_nonce_field( 'ys-user-nonce', 'ys-user' ); ?>
    <button type="submit"><?= __('Enregistrer') ?></button>
    <a href="#" id="delete_account"><?= __('Supprimer mon compte') ?></a>
  </div>
</form>
<a href="/wp-login.php?action=logout"><?= __('Se déconnecter') ?></a>