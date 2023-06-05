<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>

<?php
$html = '';

if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  $products = find_product_by_title($_POST['product_name']);
  if ($products) {
    foreach ($products as $product) :
      $html .= "<li class=\"list-group-item\">";
      $html .= $product['name'];
      $html .= "</li>";
    endforeach;
  } else {

    $html .= '<li onClick=\"fill(\'' . addslashes() . '\')\" class=\"list-group-item\">';
    $html .= 'No encontrado';
    $html .= "</li>";
  }

  echo json_encode($html);
}
?>
<?php
// encontrar todos los productos
if (isset($_POST['p_name']) && strlen($_POST['p_name'])) {
  $product_title = remove_junk($db->escape($_POST['p_name']));
  $cantidad = product_quantity($product_title);
  if ($results = find_all_product_info_by_title($product_title)) {
    foreach ($results as $result) {

      $html .= "<tr>";

      $html .= "<td style=\"width: 20%; text-align: center;\" id=\"s_name\">" . $result['name'] . "</td>";
      $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";

      $html .= "<td style=\"width: 20%;\" id=\"s_qty\">";
      $html .= "<input type=\"number\" class=\"form-control\" id=\"quantity\" name=\"quantity\" value=\"1\" max=\"{$cantidad['quantity']}\" >";
      $html  .= "</td>";
      $html  .= "<td style=\"width: 10%;\">";
      $html  .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
      $html  .= "</td>";
      $html  .= "<td style=\"width: 20%; text-align: center;\">";
      $html .= "<div style='text-align: center;'>";
      $html .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Agregar</button>";
      $html .= "</div>";
      $html  .= "</td>";
      $html  .= "</tr>";
      
    }

  } else {
    $html = '<tr><td>El producto no se encuentra registrado en la base de datos</td></tr>';
  }
  // $html  .= "<script>
  //               function validarCantidad() {
  //                 var input = document.getElementById('quantity');
  //                 var max = input.getAttribute('max');
  //                 if (input.value > max) {
  //                   alert('Has excedido la cantidad máxima permitida.');
  //                 }
  //               }
  //               </script>";
  echo json_encode($html);
}
?>
