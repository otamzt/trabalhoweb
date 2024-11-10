<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "seculus";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexão falhou: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $telefone = $_POST['telefone'];

    $email = $conn->real_escape_string($email);
    $nome = $conn->real_escape_string($nome);
    $telefone = $conn->real_escape_string($telefone);

    // Consulta para inserir o usuário
    $sql = "INSERT INTO usuarios (nome, email, senha, telefone) VALUES ('$nome', '$email', '$senha', '$telefone')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao cadastrar usuário: " . $conn->error]);
    }
}

$conn->close();
?>
