<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include "conexao.php";

$id         = $_POST["id"] ?? 0;
$nome       = $_POST["nome"] ?? "";
$posicao    = $_POST["posicao"] ?? "";
$clube      = $_POST["clube"] ?? "";
$pais       = $_POST["pais"] ?? "";
$idade      = $_POST["idade"] ?? 0;
$numero     = $_POST["numero"] ?? 0;
$foto       = $_POST["foto"] ?? "";

if (empty($id) || empty($nome) || empty($posicao) || empty($clube)) {
    echo json_encode(["erro" => "Campos obrigatórios não preenchidos."]);
    exit;
}

$stmt = $conn->prepare("UPDATE jogadores SET nome=?, posicao=?, clube=?, pais=?, idade=?, numero=?, foto=? WHERE id=?");
$stmt->bind_param("ssssiisi", $nome, $posicao, $clube, $pais, $idade, $numero, $foto, $id);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => "Jogador atualizado com sucesso!"]);
} else {
    echo json_encode(["erro" => "Erro ao atualizar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>