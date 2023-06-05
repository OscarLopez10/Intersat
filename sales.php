<?php
$page_title = 'Lista de retiros';
require_once('includes/load.php');

page_require_level(3);
?>
<?php
$all_products = find_all("products");
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<h3 align="center">Filtrar por material </h3>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    </div>
    <div class="col-sm-4">
      <div align="center" id="filtro">
        <form method="post">
          <div class="form-group">
            <select class="form-control" name="products">
              <option value="">-- Todos --</option>
              <?php foreach ($all_products as $pro) : ?>
                <option value="<?php echo (int)$pro['id']; ?>" <?php if(isset($_POST['products']) && $_POST['products'] == $pro['id']) { echo "selected"; } ?>>
                  <?php echo remove_junk($pro['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="submit" name="filtro" value="Filtrar" class="btn btn-success">
        </form>
      </div>
    </div>
    <div class="col-sm-4">
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Todos los retiros</span>
        </strong>
        <div class="pull-right">
          <a href="add_sale.php" class="btn btn-primary">Agregar retiro</a>
        </div>
      </div>
      <div class="panel-body" style="height: 400px; overflow-y: scroll;">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Nombre del material </th>
              <th class="text-center" style="width: 15%;"> Cantidad</th>
              <th class="text-center" style="width: 15%;"> Responsable </th>
              <th class="text-center" style="width: 15%;"> Fecha </th>
              <th class="text-center" style="width: 100px;"> Técnico </th>
              <th class="text-center" style="width: 100px;"> Acciones </th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $sales = (isset($_POST['products']))? find_all_sale_material($_POST['products']):find_all_sale(); 
            $sumaRetiros = 0;
            ?>
            <?php foreach ($sales as $sale) : ?>

              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                <td class="text-center"><?php echo $sale['responsable']; ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
                <td class="text-center"><?php echo $sale['u.name']; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <!-- <a href="edit_sale.php?id=<?php //echo (int)$sale['id'];
                                                    ?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
                       <span class="glyphicon glyphicon-edit"></span>
                     </a> -->
                    <a href="delete_sale.php?id=<?php echo (int)$sale['id']; ?>&qty=<?php echo (int)$sale['qty']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
              <?php $sumaRetiros += $sale['qty']; ?>
            <?php endforeach; ?>
          </tbody>
          
        </table>
      </div>
      <table class="table table-bordered table-striped">
        <tr style="background-color: #2A3542; color:#fff">
            <th style="width: 5.5%;"></th>
            <th style="width: 39.5%;">Total:</th>
            <th style="width: 14%; text-align: center;"><?php echo number_format($sumaRetiros,0,".",","); ?></th>
            <th style="width: 14%;"></th>
            <th style="width: 14.5%;"></th>
            <th style="width: 11%;"></th>
        </tr>
      </table>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
