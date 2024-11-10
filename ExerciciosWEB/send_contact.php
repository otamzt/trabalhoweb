<?php
// Conexão com o banco de dados
$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "seculus";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o usuário está logado
session_start();
if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, redireciona para o login
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome'], $_POST['email'], $_POST['mensagem'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mensagem = $_POST['mensagem'];

    // Proteção contra SQL Injection
    $nome = $conn->real_escape_string($nome);
    $email = $conn->real_escape_string($email);
    $mensagem = $conn->real_escape_string($mensagem);

    // Consulta para inserir os dados do contato
    $sql = "INSERT INTO contato (nome, email, mensagem) VALUES ('$nome', '$email', '$mensagem')";

    if ($conn->query($sql) === TRUE) {
        echo "Orçamento enviado com sucesso!";
    } else {
        echo "Erro ao enviar o orçamento: " . $conn->error;
    }
}

$conn->close();
?>
