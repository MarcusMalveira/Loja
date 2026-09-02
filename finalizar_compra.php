<?php

session_start();

require_once "conexão.php";

if(!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    die("O carrinho está vazio. <a href='loja.php'>Voltar à loja</a>");
}

$carrinho = $_SESSION['carrinho'];

try{
    $pdo->beginTransaction();

        foreach($carrinho as $item){

        $id = $item['id'];
        $quantidade = $item['quantidade'];

        $stmt = $pdo->prepare("SELECT quantidade FROM produto WHERE id = ? FOR UPDATE");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$produto){
            throw new Exception("Produto com ID $id não encontrado.");
        }

        $novaQuantidade = $produto['quantidade'] - $quantidade;
        if($novaQuantidade == 0 ){
            $stmt = $pdo->prepare("DELETE FROM produto WHERE id = ?");

            $stmt->execute([$id]);
        }else {
            $stmt = $pdo->prepare("UPDATE produto SET quantidade = ? WHERE id = ?");

            $stmt->execute([$novaQuantidade, $id]);
        }
    }
    $pdo->commit();
    unset($_SESSION['carrinho']);


} catch (Exception $e) {
    pdo->rollBack();
    die("Erro ao finalizar a compra: " . $e->getMessage());
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizando Compra</title>
</head>
<body>
    <h1> Compra realizada com sucesso!</h1>
    <p>Obrigado por sua compra!</p>
    <p>Os produtos foram retirados do estoque.</p>
    <a href="loja.php">Voltar para a loja</a>
</body>
</html>