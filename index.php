<?php
ob_start();
require_once('includes/load.php');
if ($session->isUserLoggedIn(true)) {
  redirect('home.php', false);
}

?>
<?php include_once('layouts/header.php'); ?>

<div class="login-page">
  <div class="text-center">
    <h1>Bienvenido</h1>
    <p>Iniciar sesión </p>
  </div>
  <div class="display-login">
    <?php echo display_msg($msg); ?>
  </div>

  <form method="post" action="auth.php" class="clearfix form-login-clear">
    <div class="form-group">
      <label for="usuario" class="control-label">Usuario</label>
      <input type="name" class="form-control" name="usuario" placeholder="Usuario">
    </div>
    <div class="form-group">
      <label for="contraseña" class="control-label">Contraseña</label>
      <input type="password" name="contraseña" class="form-control" placeholder="Contraseña">
    </div>
    <div class="form-group">
      <button type="submit" class="btn button-entrar  pull-right">Entrar</button>
    </div>
  </form>
</div>

<?php include_once('layouts/footer.php'); ?>