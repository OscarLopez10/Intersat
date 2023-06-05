<?php
  $page_title = 'Agregar proveedor';
  require_once('includes/load.php');

  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_provider'])){
   $req_fields = array('nombre-del-proveedor');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['nombre-del-proveedor']));
     
     $date    = make_date();
     $query  = "INSERT INTO providers (";
     $query .=" name";
     $query .=") VALUES (";
     $query .=" '{$p_name}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Proveedor agregado exitosamente. ");
       redirect('add_provider.php', false);
     } else {
       $session->msg('d',' Lo siento, registro falló.');
       redirect('provider.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_provider.php',false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Agregar proveedor</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_provider.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="nombre-del-proveedor" placeholder="Nombre del proveedor">
               </div>
              </div>
              <button type="submit" name="add_provider" class="btn btn-success">Agregar proveedor</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
