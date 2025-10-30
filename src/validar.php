<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        $conn->close();
        die("Erro ao preparar a consulta."); // die() também encerra o script
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($senha, $usuario['senha'])) {
            // SUCESSO: Senha correta
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            $stmt->close();  
            $conn->close();
            header("Location: home.php");
            exit();
            
        } else {
            $stmt->close();
            $conn->close();
            header("Location: login.php?erro=1");
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: login.php?erro=1");
        exit();
    }

} else {
    header("Location: login.php");
    exit();
}
?>