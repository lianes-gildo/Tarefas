<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Não autenticado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$titulo = trim($input['titulo'] ?? '');
$descricao = trim($input['descricao'] ?? '');

if (!$titulo) {
    echo json_encode(['error'=>'Título vazio']);
    exit;
}

$stmt = $conn->prepare('INSERT INTO tarefas (usuario_id,titulo,descricao) VALUES (?,?,?)');
$stmt->execute([$_SESSION['usuario_id'],$titulo,$descricao]);

$id = $conn->lastInsertId();
$stmt = $conn->prepare('SELECT * FROM tarefas WHERE id=? AND usuario_id=?');
$stmt->execute([$id,$_SESSION['usuario_id']]);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
