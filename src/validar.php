<?php
session_start();
require_once 'conexao.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $_email = $conn->real_escape_string($_POST['email']);
    $_senha = ($_POST['senha']);

    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario =  $resultado->fetch_assoc();
        
        if (password_verify($_senha, $usuario['senha'])){
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            header("Location: home.php");
            exit();
        }
    }

} else {
    header("Location: login.php?erro=credenciais_invalidas");
    exit();
}
?>