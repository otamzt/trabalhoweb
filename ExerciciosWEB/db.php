<?php
// db.php
$host = "localhost";
$dbname = "Seculus"; // Banco padrão, altere se necessário
$user = "admin"; // Nome do usuário
$password = "admin"; // Senha do usuário

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    die();
}
?>
