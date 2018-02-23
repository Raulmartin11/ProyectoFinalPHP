<?php
require_once("constantes.php");
$lnk = new mysqli(HOST, USUARIO_DB,CLAVE_DB);

if ($lnk->connect_errno > 0) {
        echo "No se ha podido establecer conexión con el servidor de bases de datos.<br>";
        die ("Error: " . $lnk->connect_error);
} else {
        $lnk->select_db(NOMBRE_DB);
        $lnk->set_charset("utf8");
}
?>
