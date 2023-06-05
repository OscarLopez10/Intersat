<?php
 $errors = array();

/*------------------------------------------------ --------------*/
 /* Función para Eliminar escapes especiales
 /* caracteres en una cadena para usar en una instrucción SQL
 /*------------------------------------------------ --------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*------------------------------------------------ --------------*/
/* Función para eliminar caracteres html
/*------------------------------------------------ --------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*------------------------------------------------ --------------*/
/* Función para el primer carácter en mayúsculas
/*------------------------------------------------ --------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*------------------------------------------------ --------------*/
/* Función para verificar que los campos de entrada no estén vacíos
/*------------------------------------------------ --------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." no puede estar en blanco.";
      return $errors;
    }
  }
}
/*------------------------------------------------ --------------*/
/* Función para Mostrar Mensaje de Sesión
   Ej. echo displayt_msg($mensaje);
/*------------------------------------------------ --------------*/
function display_msg($msg =''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}
/*--------------------------------------------------------------*/
/* Función para redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}

function total_price($totals){
   $sum = 0;
   $sub = 0;
   foreach($totals as $total ){
     $sum += $total['total_sales'];
   }
   return array($sum);
}
/*------------------------------------------------ --------------*/
/* Función para fecha y hora legible
/*------------------------------------------------ --------------*/
function read_date($str){
     if($str)
      return date('d/m/Y g:i:s a', strtotime($str));
     else
      return null;
  }
/*------------------------------------------------ --------------*/
/* Función para hacer legible la fecha y la hora
/*------------------------------------------------ --------------*/
function make_date(){
  date_default_timezone_set('America/Asuncion');
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*------------------------------------------------ --------------*/
/* Función para fecha y hora legible
/*------------------------------------------------ --------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*------------------------------------------------ --------------*/
/* Función para crear cadenas aleatorias
/*------------------------------------------------ --------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}


?>
