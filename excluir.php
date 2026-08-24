<?php

    require_once 'conexão.php';

    if(!isset($_GET['id'])){
        die ("ID do produto não encontrado");
    }

    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM produto WHERE id = ?");
    $stmt->execute([$id]);

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto){
        die ("Produto não encontrado");
    }

    $stmt = $pdo->prepare("DELETE FROM produto WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: loja.php");
    exit;
?>