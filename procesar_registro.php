<?php
// Creamos las variables que contendrán los resultados de los inputs del formulario HTML:
if(isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['password'])){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}else{
    die();
}
//Creamos las variables necesarias para hacer la conexión en PHP
$servidor = "localhost"; //servidor local, es decir, mi PC
$usuario = "root"; // nombre de usuario administrador por defecto en XAMPP
$password_bd = ""; // contraseña vacía por defecto en XAMPP, pero en proyectos con más personas SÍ O SÍ hay que tener una contraseña segura
$base_datos = "sistema_usuarios"; // nombre de la base de datos, dado que se pueden tener muchas
/*almacenamos en una variable $conexion la función mysqli_connect() para crear 
el recurso de conexión que nos servirá como "puente" para que el código PHP 
interactúe con MySQL
*/
/* mysqli_connect() recibe los parámetros y retorna false si la conexión es fallida
y si la conexión funciona retorna un objeto de conexión 
*/
$conexion = mysqli_connect($servidor, $usuario, $password_bd, $base_datos); 
// creamos un if por si la conexión falla.
if(!$conexion){
    die("Conexión fallida a la base de datos"); //usamos die() para mostrar un mensaje si la conexión falla y además para NO ejecutar cualquier bloque de código que haya después de este.
} else{
    echo "<h3>Conexión a la base de datos exitosa!</h3>"; // mostramos un mensaje si la conexión es exitosa
}
/*
hacemos la primera consulta sql (usando marcadores de posición (?, ?, ?) 
para indicar que lo que va ahí son DATOS, NO ÓRDENES) (cibersecurity papá)
*/ 
$sql = "INSERT INTO usuarios (nombre, email, password) 
VALUES (?, ?, ?)";
$sql2 = "SELECT * FROM usuarios";
/*
Preparamos el statement (como decir: "tengo la orden lista, pero sin los ingredientes")
¿qué es stmt? Es un objeto "statement" (declaración preparada). Digamos que es un molde
vacío esperando ser llenado:
*/
$stmt = mysqli_prepare($conexion, $sql);
/* 
Ahora, vindeamos (vinculamos) los valores a los huecos que habíamos dejado para los datos con
? ? ?
*/
mysqli_stmt_bind_param($stmt, 'sss', $nombre, $email, $password);
/*
¿Qué significa eso de 'sss'?
simplemente indicamos que hay 3 strings para los tres campos que dejamos con ? ? ?
s = string (texto).
i = integer (número entero).
d = double (decimal).
Como nuestros 3 valores son textos, usamos 'sss'

El siguiente paso será ejecutar el statement, así:
*/
$resultado = mysqli_stmt_execute($stmt);
if($resultado){
    echo "Se guardó correctamente";
}else{
    echo "Error al guardar";
}
/*
En el paso anterior, MySQL dice: "Ya tengo la estructura y los datos por separado,
los combino de forma segura y ejecuto. (mysqli_execute($stmt))"

El último paso sería cerrar el statement para liberar recursos
*/
mysqli_stmt_close($stmt);


/*ahora, con la función mysqli_query(), vamos a hacer que meta en una "caja" las 
filas que hayan en la tabla de la base de datos
*/


$mostrar = mysqli_query($conexion, $sql2);
if($mostrar){
    while($usuarios_de_bd = mysqli_fetch_assoc($mostrar)){
        echo "<strong>Nombre: </strong>" . $usuarios_de_bd['nombre'] . '<br>';
        echo "<strong>Email: </strong>" . $usuarios_de_bd['email'] . '<br>';
        echo "<strong>Contraseña: </strong>" . $usuarios_de_bd['password'] . '<br>';
        echo "<hr>";
    }
}

/*Ahora, creamos un loop para mostrar todas las filas de la tabla usuarios
en la base de datos usando al función mysqli_fetch_assoc(), esta función se encarga
de sacar UNA fila a la vez de la tabla en la base de datos y mostrar los resultados
en un array asociativo. Usaremos un loop while*/

/*
while($usuario = mysqli_fetch_assoc($resultado)){
    echo "ID: " . $usuario["id"] . "<br>";
    echo "Nombre: " . $usuario["nombre"] . "<br>";
    echo "Correo electrónico: " . $usuario["email"] . "<br>";
    echo "<hr>";
}
    pd: lo comenté porque si no salía un error abajo. 
    Después miramos como mostrarlos automáticamente?
*/
?>