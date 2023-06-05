<?php
  $page_title = 'Editar producto';
  require_once('includes/load.php');
  
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_providers = find_all('providers');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('Descripción','Categoría','Cantidad','Proveedor', 'Descripción' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['Descripción']));
       $p_cat   = (int)$_POST['Categoría'];
       $p_qty   = remove_junk($db->escape($_POST['Cantidad']));
       $pr_name = remove_junk($db->escape($_POST['Proveedor']));
       $p_codi = remove_junk($db->escape($_POST['Serial']));
       $add_qty = remove_junk($db->escape($_POST['quantity-count']));
        if($add_qty != null){
          $p_qty = $p_qty + $add_qty;
        }
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       $query   = "UPDATE products SET";
       $query  .=" codigo ='{$p_codi}', name ='{$p_name}', quantity ='{$p_qty}',";
       $query  .=" categorie_id ='{$p_cat}',media_id='{$media_id}', providers_id='{$pr_name}' ";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Producto ha sido actualizado. ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Lo siento, actualización falló.');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editar producto</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="Descripción" value="<?php echo remove_junk($product['name']);?>">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="Serial" value="<?php echo remove_junk($product['codigo']);?>">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                  <label for="cat">Categoria</label>
                    <select class="form-control" name="Categoría">
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                  <div class="col-md-6">
                  <label for="img">Imagen</label>
                    <select class="form-control" name="product-photo">
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                  <div class="form-group">
                    <label for="qty">Cantidad</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="number" class="form-control" name="Cantidad" value="<?php echo remove_junk($product['quantity']); ?>">
                   </div>
                  </div>
                 </div>
                 <div class="col-md-6">
                    <label for="pro">Proveedores</label>
                    <select class="form-control" name="Proveedor">
                   <?php  foreach ($all_providers as $pro): ?>
                     <option value="<?php echo (int)$pro['id']; ?>" <?php if($product['providers_id'] === $pro['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($pro['name']); ?></option>
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
                      <input type="number" class="form-control" name="quantity-count" placeholder="Agregar Cantidad">
                    </div>
                  </div>
                </div>
               </div>
               
              <button type="submit" name="product" class="btn btn-primary">Actualizar</button>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
