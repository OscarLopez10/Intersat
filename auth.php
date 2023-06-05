<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('usuario','contraseña' );
validate_fields($req_fields);
$usuario = remove_junk($_POST['usuario']);
$contraseña = remove_junk($_POST['contraseña']);

if(empty($errors)){
  $user_id = authenticate($usuario, $contraseña);
  if($user_id != false){
    $_SESSION["user_id"] = $user_id;
  }
  
  if($user_id){
    //crear sesion con id
     $session->login($user_id);
    // Actualizar hora de inicio de sesión
     updateLastLogIn($user_id);
     $session->msg("s", "Bienvenido");
     redirect('home.php',false);

  } else {
    $session->msg("d", "Nombre de usuario y/o contraseña incorrecto.");
    redirect('index.php',false);
  }

} else {
   $session->msg("d", $errors);
   redirect('index.php',false);
}

?>
