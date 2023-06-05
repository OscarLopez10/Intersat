<?php
  $page_title = 'Agregar Grupo';
  require_once('includes/load.php');
  // Comprobación de qué nivel de usuario tiene permiso para ver esta página
   page_require_level(1);
?>
<?php
  if(isset($_POST['add'])){

   $req_fields = array('nombre-del-grupo','nivel-de-grupo');
   validate_fields($req_fields);

   if(find_by_groupName($_POST['nombre-del-grupo']) === false ){
     $session->msg('d','<b>Error!</b> El nombre de grupo realmente existe en la base de datos');
     redirect('add_group.php', false);
   }elseif(find_by_groupLevel($_POST['nivel-de-grupo']) === false) {
     $session->msg('d','<b>Error!</b> El nombre de grupo realmente existe en la base de datos ');
     redirect('add_group.php', false);
   }
   if(empty($errors)){
           $name = remove_junk($db->escape($_POST['nombre-del-grupo']));
          $level = remove_junk($db->escape($_POST['nivel-de-grupo']));
         $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO user_groups (";
        $query .="group_name,group_level,group_status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$level}','{$status}'";
        $query .=")";
        if($db->query($query)){
         
          $session->msg('s',"Grupo ha sido creado! ");
          redirect('add_group.php', false);
        } else {

          $session->msg('d','Lamentablemente no se pudo crear el grupo!');
          redirect('add_group.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_group.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
<div class="cuadro-user">
    <div class="text-center">
       <h3>Agregar nuevo grupo</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="add_group.php" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Nombre del grupo</label>
              <input type="name" class="form-control" name="nombre-del-grupo" required>
        </div>
        <div class="form-group">
              <label for="level" class="control-label">Nivel del grupo</label>
              <input type="number" class="form-control" name="nivel-de-grupo">
        </div>
        <div class="form-group">
          <label for="status">Estado</label>
            <select class="form-control" name="status">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
        </div>
        <div class="form-group clearfix" style="display: flex; justify-content: flex-end;">
                <button type="submit" name="add" class="btn btn-infoo">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>

