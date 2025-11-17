<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Não autenticado']);
    exit;
}

$stmt = $conn->prepare('SELECT * FROM tarefas WHERE usuario_id=? ORDER BY criado_em DESC');
$stmt->execute([$_SESSION['usuario_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tasks);
