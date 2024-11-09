<?php
// Iniciar a sessão para armazenar dados do usuário autenticado
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "seculus";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Proteção contra SQL Injection
    $email = $conn->real_escape_string($email);
    $senha = $conn->real_escape_string($senha);

    // Consulta para verificar o email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificação da senha
        if (password_verify($senha, $user['senha'])) {
            // Login bem-sucedido, armazena o ID do usuário na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email']; // Você pode armazenar mais informações do usuário aqui, se necessário
            
            echo "Login realizado com sucesso!";
            
            // Redireciona para a página de contato ou outra página
            header("Location: contato.html");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
}

$conn->close();
?>
