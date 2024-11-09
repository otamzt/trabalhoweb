<?php
session_start(); // Iniciar a sessão

// Verificar se o usuário está logado e se é um administrador
if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    // O usuário está logado e é administrador
    echo json_encode(['status' => 'success']);
} else {
    // O usuário não é administrador ou não está logado
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
}
?>
