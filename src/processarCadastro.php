<?php

require_once 'conexao.php';
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    $sql_check_email = "SELECT id FROM usuarios WHERE email = $email";
    $result = $conn->query($sql_check_email);

    if ($result->num_rows > 0) {
        header("Location: cadastro.php?erro=email_existente");
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('? ', '?', '?')";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false){
        die("Erro na preparação da consulta: " . $conn->error);
    }


    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: login.php?sucesso=cadastro_realizado");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: cadastro.php?erro=erro_cadastro");
        exit();
    }


} else{
    header("Location: cadastro.php");
    exit();
}
?>