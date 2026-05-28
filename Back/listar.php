<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "conexao.php";

$id      = intval($_GET["id"] ?? 0);
$busca   = $_GET["busca"] ?? "";
$posicao = $_GET["posicao"] ?? "";

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM jogadores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $jogador = $result->fetch_assoc();
    echo json_encode($jogador ?: ["erro" => "Jogador não encontrado."]);
    $stmt->close();
    $conn->close();
    exit;
}

$sql = "SELECT * FROM jogadores WHERE 1=1";

if (!empty($busca)) {
    $busca_safe = $conn->real_escape_string($busca);
    $sql .= " AND (nome LIKE '%$busca_safe%' OR clube LIKE '%$busca_safe%' OR pais LIKE '%$busca_safe%')";
}

if (!empty($posicao)) {
    $posicao_safe = $conn->real_escape_string($posicao);
    $sql .= " AND posicao = '$posicao_safe'";
}

$sql .= " ORDER BY nome ASC";

$result = $conn->query($sql);

$jogadores = [];
while ($row = $result->fetch_assoc()) {
    $jogadores[] = $row;
}

echo json_encode($jogadores);

$conn->close();
?>