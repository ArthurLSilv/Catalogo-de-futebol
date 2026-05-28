<?php
// Captura QUALQUER erro/warning do PHP e devolve como JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    header("Content-Type: application/json");
    echo json_encode(["erro" => "PHP Error [$errno]: $errstr em linha $errline"]);
    exit;
});

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

include "conexao.php";

$nome    = trim($_POST["nome"]    ?? "");
$posicao = trim($_POST["posicao"] ?? "");
$clube   = trim($_POST["clube"]   ?? "");
$pais    = trim($_POST["pais"]    ?? "");
$idade   = intval($_POST["idade"] ?? 0);
$numero  = intval($_POST["numero"]?? 0);
$foto    = trim($_POST["foto"]    ?? "");

if (empty($nome) || empty($posicao) || empty($clube)) {
    echo json_encode(["erro" => "Nome, posição e clube são obrigatórios."]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO jogadores (nome, posicao, clube, pais, idade, numero, foto)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(["erro" => "Falha no prepare: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssssiis", $nome, $posicao, $clube, $pais, $idade, $numero, $foto);

if ($stmt->execute()) {
    echo json_encode([
        "sucesso" => "Jogador cadastrado com sucesso!",
        "id"      => $stmt->insert_id
    ]);
} else {
    echo json_encode(["erro" => "Erro ao cadastrar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>