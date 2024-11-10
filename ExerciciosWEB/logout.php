<?php
session_start();

// Destruir a sessão para efetuar logout
session_destroy();

// Retornar um status de sucesso
echo json_encode(['status' => 'success', 'message' => 'Logout realizado com sucesso']);
?>