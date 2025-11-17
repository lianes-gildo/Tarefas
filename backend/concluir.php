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

$stmt = $conn->prepare('SELECT concluida FROM tarefas WHERE id=? AND usuario_id=?');
$stmt->execute([$id,$_SESSION['usuario_id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(['error'=>'Tarefa não encontrada']);
    exit;
}

$new = $row['concluida'] ? 0 : 1;
$u = $conn->prepare('UPDATE tarefas SET concluida=? WHERE id=? AND usuario_id=?');
$u->execute([$new,$id,$_SESSION['usuario_id']]);

echo json_encode(['status'=>'ok','concluida'=>$new]);
