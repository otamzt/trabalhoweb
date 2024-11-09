<?php
// Conexão com o banco de dados
$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "seculus";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Conexão falhou: ' . $conn->connect_error]);
    exit();
}

// Iniciar a sessão
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Proteção contra SQL Injection
    $email = $conn->real_escape_string($email);
    $senha = $conn->real_escape_string($senha);

    // Verificar se o usuário existe no banco
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar se a senha está correta
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            echo json_encode(['status' => 'success', 'message' => 'Login efetuado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Senha incorreta.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado.']);
    }
}

$conn->close();
?>
