<?php
$page_title = 'Editar Retiros';
require_once('includes/load.php');

page_require_level(3);
?>
<?php
$sale = find_by_id('sales', (int)$_GET['id']);
$all_users = find_all('users');
if (!$sale) {
  $session->msg("d", "Missing product id.");
  redirect('sales.php');
}
?>
<?php $product = find_by_id('products', $sale['product_id']); ?>
<?php

if (isset($_POST['update_sale'])) {
  $req_fields = array('title', 'quantity', 'date', 'user_id');
  validate_fields($req_fields);
  if (empty($errors)) {
    $p_id      = $db->escape((int)$product['id']);
    $s_qty     = $db->escape((int)$_POST['quantity']);
    $u_id      = $db->escape($_POST['user_id']);
    $date      = $db->escape($_POST['date']);
    $s_date    = date("Y-m-d", strtotime($date));

    $sql  = "UPDATE sales SET";
    $sql .= " product_id= '{$p_id}',qty={$s_qty},date='{$s_date}',users_id='{$u_id}' ";
    $sql .= " WHERE id ='{$sale['id']}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      update_product_qty($s_qty, $p_id);
      $session->msg('s', "Retiro Actualizado.");
      redirect('edit_sale.php?id=' . $sale['id'], false);
    } else {
      $session->msg('d', ' Lo sentimos el retiro no fue actualizado!');
      redirect('sales.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_sale.php?id=' . (int)$sale['id'], false);
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editar Retiro</span>
        </strong>
        <div class="pull-right">
          <a href="sales.php" class="btn btn-primary">Todos los Retiros</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <th> Producto </th>
            <th> Cantidad </th>
            <th> Responsable </th>
            <th> Fecha </th>
            <th> Acción </th>
          </thead>
          <tbody id="product_info">
            <tr>
              <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                <td id="s_name">
                  <input type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>">
                  <div id="result" class="list-group"></div>
                </td>
                <td id="s_qty">
                  <input type="text" class="form-control" name="quantity" value="<?php echo (int)$sale['qty']; ?>">
                </td>
                <td id="u_id">
                  <select class="form-control" name="user_id">
                    <?php foreach ($all_users as $user) : ?>
                      <option value="<?php echo (int)$user['id']; ?>" <?php if ($sale['users_id'] === $user['id']) : echo "selected"; endif; ?>>
                        <?php echo remove_junk($user['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td id="s_date">
                  <input type="date" class="form-control datepicker" name="date" data-date-format="" value="<?php echo remove_junk($sale['date']); ?>">
                </td>
                <td>
                  <button type="submit" name="update_sale" class="btn btn-primary">Actualizar Retiro</button>
                </td>
              </form>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>