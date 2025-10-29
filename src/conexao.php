<?php
$servidor = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "ecommerce_jogos";

$conn = mysqli_connect($servidor, $usuario_db, $senha_db, $banco);

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

$conn->set_charset("utf8");

?>