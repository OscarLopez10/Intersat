<?php
  require_once('includes/load.php');

  page_require_level(3);
?>
<?php
  $d_sale = find_by_id('sales',(int)$_GET['id']);
  if(!$d_sale){
    $session->msg("d","ID vacío.");
    redirect('sales.php');
  }
?>
<?php
  
  update_by_id((int)$d_sale['product_id'],$_GET['qty']);
  $delete_id = delete_by_id('sales',(int)$d_sale['id']);

  if($delete_id){
      $session->msg("s","Retiro eliminado.");
      redirect('sales.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('sales.php');
  }
?>
