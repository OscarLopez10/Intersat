<?php
  $page_title = 'Retiro mensuales';
  require_once('includes/load.php');

   page_require_level(3);
?>
<?php
$anios = array();
$anio_actual = date('Y');

for($i = 2020; $i <= $anio_actual; $i++) {
    $anios[$i] = $i;
}

$year = date('Y');
$meses = array(1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",8=>"agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<h3 align="center">Filtrar por Año </h3>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    </div>
    <div class="col-sm-4">
      <div align="center" id="filtro">
        <form method="post">
          <div class="form-group">
            <select class="form-control" name="anhos">
              <option value="">-- Año Actual --</option>
              <?php foreach($anios as $anio) { ?>
                <option value="<?php echo $anio; ?>"><?php echo $anio; ?></option>
              <?php } ?>
            </select>
          </div>
          <input type="submit" name="filtro" value="Filtrar" class="btn btn-success">
        </form>
      </div>
    </div>
    <div class="col-sm-4">
    </div>
  </div>
</div><br>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Retiros mensuales</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Descripción </th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Mes </th>
             </tr>
            </thead>
           <tbody>
            <?php $sales = (isset($_POST['anhos']))? monthlySales($_POST['anhos']):monthlySales($year); ?>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['material']); ?></td>
               <td class="text-center"><?php echo (int)$sale['total']; ?></td>
               <td class="text-center"><?php echo $meses[(int)$sale['mes']]; ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
