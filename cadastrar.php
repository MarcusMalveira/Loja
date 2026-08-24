<?php

require_once "conexão.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $validade = $_POST['validade'];
    $stmt = $pdo->prepare("
        SELECT * FROM produto
        WHERE nome = ? AND validade = ?
    ");
    $stmt->execute([
        $nome,
        $validade
    ]);
    $produtoExistente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($produtoExistente) {
        $novaQuantidade = $produtoExistente['quantidade'] + $quantidade;
        $stmt = $pdo->prepare("
            UPDATE produto
            SET valor = ?, quantidade = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $valor,
            $novaQuantidade,
            $produtoExistente['id']
        ]);
    } else {
        $sql = "
            INSERT INTO produto
            (nome, valor, quantidade, validade)
            VALUES (:nome, :valor, :quantidade, :validade)
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":nome" => $nome,
            ":valor" => $valor,
            ":quantidade" => $quantidade,
            ":validade" => $validade
        ]);
    }
    header("Location: loja.php");
    exit;
}

?>