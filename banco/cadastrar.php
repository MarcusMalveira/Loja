<?php
require_once "conexão.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $validade = $_POST['validade'];

    $sql = "INSERT INTO produto 
            (nome, valor, quantidade, validade) 
            VALUES (:nome, :valor, :quantidade, :validade)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":nome" => $nome,
        ":valor" => $valor,
        ":quantidade" => $quantidade,
        ":validade" => $validade
    ]);

    header("Location: loja.php");
    exit;
}
?>