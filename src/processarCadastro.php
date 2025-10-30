<?php

require_once 'conexao.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_plana = $_POST['senha'];


    $sql_check_email = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check_email);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check && $resultado_check->num_rows > 0) {
        $stmt_check->close();
        $conn->close();
        header("Location: cadastro.php?erro=email_existente");
        exit();
    }

    $stmt_check->close();

    $senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";

    $stmt_insert = $conn->prepare($sql_insert);
    if ($stmt_insert === false) {
        die("Erro na preparação da consulta: " . $conn->error);  
    }

    $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt_insert->execute()){
        header("Location: login.php?sucesso=cadastro_ok");
        exit();
    } else {
        header("Location: cadastro.php?erro=db_error");
        exit();
    }
 
} else {
    header("Location: cadastro.php");
    exit();
}
?>