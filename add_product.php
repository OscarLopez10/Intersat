<?php
  $page_title = 'Agregar Material';
  require_once('includes/load.php');
  // Comprobación de qué nivel de usuario tiene permiso para ver esta página
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $all_providers = find_all('providers');
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('Descripción','Categoría','Cantidad','Proveedor', 'Serial');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['Descripción']));
     $p_cat   = remove_junk($db->escape($_POST['Categoría']));
     $p_qty   = remove_junk($db->escape($_POST['Cantidad']));
     $pr_name = remove_junk($db->escape($_POST['Proveedor']));
     $p_codi = remove_junk($db->escape($_POST['Serial']));
     
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO products (";
     $query .=" codigo,name,quantity,categorie_id,media_id,date,providers_id";
     $query .=") VALUES (";
     $query .=" '{$p_codi}','{$p_name}', '{$p_qty}', '{$p_cat}', '{$media_id}', '{$date}', '{$pr_name}' ";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Material agregado exitosamente. ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Lo siento, registro falló.');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
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
            <span>Agregar material</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="Descripción" placeholder="Descripción">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="Serial" placeholder="Serial">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="Categoría">
                      <option value="">Selecciona una categoría</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Selecciona una imagen</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="Cantidad" placeholder="Cantidad">
                  </div>
                 </div>
                 <div class="col-md-6">
                    <select class="form-control" name="Proveedor">
                      <option value="">Seleccione un proveedor</option>
                    <?php  foreach ($all_providers as $providers): ?>
                      <option value="<?php echo (int)$providers['id'] ?>">
                        <?php echo $providers['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  
               </div>
               
              </div>
              
              <button type="submit" name="add_product" class="btn btn-success">Agregar material</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
