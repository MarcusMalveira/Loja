<?php

session_start();

require_once "conexão.php";

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $stmt = $pdo->prepare("SELECT * FROM produto WHERE id = ?");
    $stmt->execute([$produto_id]);

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {

        $produtoEncontrado = false;

        foreach ($_SESSION['carrinho'] as &$itemCarrinho) {

            if ($itemCarrinho['id'] == $produto['id']) {

                $itemCarrinho['quantidade'] += $quantidade;

                $produtoEncontrado = true;

                break;
            }
        }

        unset($itemCarrinho);

        if (!$produtoEncontrado) {

            $item = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'valor' => $produto['valor'],
                'quantidade' => $quantidade
            ];

            $_SESSION['carrinho'][] = $item;
        }
    }

    // IMPORTANTE:
    // Depois de adicionar o produto,
    // redireciona para o carrinho usando GET.
    header("Location: carrinho.php");
    exit;
}

$carrinho = $_SESSION['carrinho'];
$total = 0;

?>