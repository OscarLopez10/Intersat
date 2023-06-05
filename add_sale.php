<?php
  $page_title = 'Agregar Retiro';
  require_once('includes/load.php');

   page_require_level(3);
?>
<?php
  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity', 'date', 'users_id' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id      = $db->escape((int)$_POST['s_id']);
          $s_qty     = $db->escape((int)$_POST['quantity']);
          $date      = $db->escape($_POST['date']);
          $u_id      = $db->escape($_POST['users_id']);
          $s_date    = make_date();

          $sql  = "INSERT INTO sales (";
          $sql .= " product_id,qty,date,users_id";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$s_qty}','{$s_date}','{$u_id}'";
          $sql .= ")";

                if($db->query($sql)){
                  update_product_qty($s_qty,$p_id);
                  $session->msg('s',"Retiro agregado ");
                  redirect('add_sale.php', false);
                } else {
                  $session->msg('d','Lo siento, registro falló.');
                  redirect('add_sale.php', false);
                }
        } else {
           $session->msg("d", $errors);
           redirect('add_sale.php',false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Búsqueda</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Buscar por el nombre del material">
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editar retiro</span>
       </strong>
       
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th style="width: 20%; text-align: center;"> Producto </th>
            <th style="width: 20%; text-align: center;"> Cantidad </th>
            <th style="width: 20%; text-align: center;"> Agregado</th>
            <th style="width: 20%; text-align: center;"> Acciones</th>
           </thead>
             <tbody  id="product_info"> </tbody>
            <input type="hidden" name="users_id" value="<?php echo $_SESSION["user_id"]; ?>">
         </table>
       </form>
      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>

