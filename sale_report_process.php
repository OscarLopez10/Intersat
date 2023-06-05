<?php
$page_title = 'Reporte de retiros';
$results = '';
  require_once('includes/load.php');

   page_require_level(3);
?>
<?php
  if(isset($_POST['submit'])){
    $req_dates = array('Rango-de-fechas','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $Rango_de_fechas   = remove_junk($db->escape($_POST['Rango-de-fechas']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $results      = find_sale_by_dates($Rango_de_fechas,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('sales_report.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Reporte de retiros</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   <style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 40px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #ededed;
     }table tbody tr td{
       vertical-align: middle;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;
       white-space: nowrap;
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
     .logo {
            position: absolute;
            top: 30px;
            left: 40px;
            width: 140px;
            height: 140px;

        }
    </style>
   </style>
</head>
<body>
<img src="https://i.postimg.cc/T379MG5f/intersat.png" alt="Logo" class="logo">
  <?php if($results): ?>
    <div class="page-break">
       <div class="sale-head pull-right">
           <h1>Reporte de retiros</h1>
           <strong><?php if(isset($Rango_de_fechas)){ echo $Rango_de_fechas;}?> a <?php if(isset($end_date)){echo $end_date;}?> </strong>
       </div>
      <table class="table table-border">
        <thead>
          <tr>
              <th>Fecha</th>
              <th>Descripción</th>
              <th>Encargado</th>
              <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): ?>
           <tr>
              <td class=""><?php echo remove_junk($result['date']);?></td>
              <td class="desc">
                <h6><?php echo remove_junk(ucfirst($result['name']));?></h6>
              </td>
              <td><?php echo remove_junk($result['username']) ;?></td>
              <td class="text-right"><?php echo remove_junk($result['total_sales']);?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr class="text-right">
           <td colspan="2"></td>
           <td colspan="1"> Total </td>
           <td> 
           <?php echo number_format(@total_price($results)[0]);?>
          </td>
         </tr>
        </tfoot>
      </table>
    </div>
  <?php
    else:
        $session->msg("d", "No se encontraron retiros. ");
        redirect('sales_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
