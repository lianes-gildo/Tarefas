<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Não autenticado']);
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['error'=>'ID inválido']);
    exit;
}

$stmt = $conn->prepare('DELETE FROM tarefas WHERE id=? AND usuario_id=?');
$stmt->execute([$id,$_SESSION['usuario_id']]);

echo json_encode(['status'=>'ok']);
