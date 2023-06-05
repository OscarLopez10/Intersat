<?php
  require_once('includes/load.php');

/*------------------------------------------------ --------------*/
/* Función para encontrar todas las filas de la tabla de la base de datos por nombre de tabla
/*------------------------------------------------ --------------*/
error_reporting(0);
function find_all($table) {
   global $db;
    $where = ' WHERE anulado = 0';
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table).$where);
   }
}
/*------------------------------------------------ --------------*/
/* Función para realizar consultas
/*------------------------------------------------ --------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*------------------------------------------------ --------------*/
/* Función para buscar datos de la tabla por id
/*------------------------------------------------ --------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*------------------------------------------------ --------------*/
/* Función para eliminar datos de la tabla por id
/*------------------------------------------------ --------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
     $sql = "UPDATE ".$db->escape($table);
    //  para el caso de la eliminacion simplemente se anula para evitar la perdida de la informacion
    $sql .= " SET anulado = 1";
    $sql .= " WHERE id=". $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*------------------------------------------------ --------------*/
/* Funcion para Devolver cantidad de tabla por id
/*------------------------------------------------ --------------*/
function update_by_id($id,$qty)
{
  global $db;
     $sql = "UPDATE products";
    $sql .= " SET quantity = quantity +". $db->escape($qty);
    $sql .= " WHERE id=". $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
 
}
/*------------------------------------------------ --------------*/
  /* Función para obtener el product_id de sales
  /*------------------------------------------------ --------------*/


  function product_quantity($nombre)
	{
		global $db;
    $sql = "SELECT quantity FROM products p WHERE p.name ='{$nombre}'";
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
	}
/*------------------------------------------------ --------------*/
/* Función para Count id Por nombre de tabla
/*------------------------------------------------ --------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*------------------------------------------------ --------------*/
/* Determinar si existe la tabla de la base de datos
/*------------------------------------------------ --------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
/*------------------------------------------------ --------------*/
 /* Inicie sesión con los datos proporcionados en $_POST,
 /* procedente del formulario de inicio de sesión.
/*------------------------------------------------ --------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user['id'];
       }
    }
   return false;
  }



/*------------------------------------------------ --------------*/
  /* Encuentra el usuario de inicio de sesión actual por ID de sesión
  /*------------------------------------------------ --------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
/*------------------------------------------------ --------------*/
  /* Buscar todos los usuarios por
  /* Uniendo la tabla de usuarios y la tabla de grupos de usuarios
  /*------------------------------------------------ --------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level WHERE u.anulado = 0 ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
/*------------------------------------------------ --------------*/
  /* Función para actualizar el último inicio de sesión de un usuario
  /*------------------------------------------------ --------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

/*------------------------------------------------ --------------*/
  /* Buscar todos los nombres de grupos
  /*------------------------------------------------ --------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
/*------------------------------------------------ --------------*/
  /* Encontrar nivel de grupo
  /*------------------------------------------------ --------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
/*------------------------------------------------ --------------*/
  /* Función para verificar qué nivel de usuario tiene acceso a la página
  /*------------------------------------------------ --------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //si el usuario no inicia sesión
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //si el estado del grupo está desactivado
     elseif($login_level['group_status'] == '0'):
           $session->msg('d','Este nivel de usuario esta inactivo!');
           redirect('home.php',false);
      //registro de verificación en el nivel de usuario y el nivel requerido es menor o igual que
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('home.php', false);
        endif;

     }
   /*------------------------------------------------ --------------*/
   /* Función para encontrar todos los nombres de material
   /* 
   /*------------------------------------------------ --------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.codigo,p.name,p.quantity,p.media_id,p.date,c.name";
    $sql  .=" AS categorie,m.file_name AS image, pr.name AS provider";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" LEFT JOIN providers pr ON pr.id = p.providers_id";
    $sql  .=" WHERE p.anulado = 0";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
 /*------------------------------------------------ --------------*/
  /* Función para encontrar todos los nombres de material
  /* Solicitud proveniente de ajax.php para sugerencia automática
  /*------------------------------------------------ --------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*------------------------------------------------ --------------*/
  /* Función para encontrar toda la información del material por título del material
  /* Solicitud proveniente de ajax.php
  /*------------------------------------------------ --------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }
  /*------------------------------------------------ --------------*/
  /* Funcion para Actualizar cantidad de material
  /*------------------------------------------------ --------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*------------------------------------------------ --------------*/
  /* Función para mostrar material reciente agregado
  /*------------------------------------------------ --------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image, pr.name AS provider FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " LEFT JOIN providers pr ON pr.id = p.providers_id";
   $sql  .= " WHERE p.anulado = 0";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*------------------------------------------------ --------------*/
 /* Función para encontrar el material con mas retiro
 /*------------------------------------------------ --------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " WHERE s.anulado = 0";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*------------------------------------------------ --------------*/
 /* Función para encontrar todos los materiales
 /*------------------------------------------------ --------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.date,p.name,u.username AS responsable";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " LEFT JOIN users u ON s.users_id = u.id";
   $sql .= " WHERE s.anulado = 0";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*------------------------------------------------ --------------*/
 /* Función para encontrar todos los retiro de un tipo de material
 /*------------------------------------------------ --------------*/
 function find_all_sale_material($products){
  if($products !=''){
    $pro = "AND s.product_id= '$products'";
  }else{
    $pro = "";
  }
  global $db;
  $sql  = "SELECT s.id,s.qty,s.date,p.name,u.username AS responsable";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " LEFT JOIN users u ON s.users_id = u.id";
  $sql .= " WHERE s.anulado = 0 $pro";
  $sql .= " ORDER BY s.date DESC";
  return find_by_sql($sql);
}
/*------------------------------------------------ --------------*/
 /* Funcion para mostrar retiro reciente
 /*------------------------------------------------ --------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.date,p.name,u.username AS username";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " LEFT JOIN users u ON s.users_id = u.id";
  $sql .= " WHERE s.anulado = 0";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*------------------------------------------------ --------------*/
/* Función para Generar reporte de retiro por dos fechas
/*------------------------------------------------ --------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name, u.username AS username, ";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "LEFT JOIN users u ON s.users_id = u.id";
  $sql .= " WHERE s.anulado = 0 AND s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*------------------------------------------------ --------------*/
/* Función para Generar informe de retiro diario
/*------------------------------------------------ --------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name, u.username AS username";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " LEFT JOIN users u ON s.users_id = u.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*------------------------------------------------ --------------*/
/* Funcion para generar reporte de retiro mensual
/*------------------------------------------------ --------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT p.name AS material, MONTH(s.date) AS mes, SUM(s.qty) AS total   ";
  $sql .= " FROM sales s ";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " LEFT JOIN users u ON s.users_id = u.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}' AND s.anulado = 0";
  $sql .= " GROUP BY s.product_id, mes";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}
/*
SELECT s.qty, MONTH(s.date) AS date, p.name
FROM sales s
LEFT JOIN products p ON s.product_id = p.id
LEFT JOIN users u ON s.users_id = u.id
WHERE DATE_FORMAT(s.date, '%Y' ) = '2023'
GROUP BY DATE_FORMAT( s.date,  '%c' ), s.product_id
ORDER BY date_format(s.date, '%c' ) ASC
Luego hay que agregar un array con los nombres de los meses y todo bien ya, deberiamos de probar bien el codigo para 
cersiorarnos de que está bien el codigo
*/
?>
