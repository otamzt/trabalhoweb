<?php
session_start();

// Verifique se o usuário está logado
if (isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'success', 'message' => 'Usuário logado']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
}
?>
