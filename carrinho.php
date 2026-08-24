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
}



$carrinho = $_SESSION['carrinho'];
$total = 0;



?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrinho</title>
    </head>
    <body>
        <h1>Meu Carrinho</h1>
        <?php if(empty($carrinho)): ?>
            <p>O carrinho está vazio.</p>
            <a href="loja.php">Voltar para a loja</a>
        <?php else: ?>
        
            <?php foreach($carrinho as $produto): ?>
                <?php 
                $subtotal = $produto['valor'] * $produto['quantidade'];

                $total += $subtotal;
                ?>

                <div>
                    <h2><?= $produto['nome'] ?></h2>
                    <p>Preço: R$ <?= number_format($produto['valor'], 2, ',', '.') ?></p>
                    <p>Quantidade: <?= $produto['quantidade'] ?></p>
                    <p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
            <h2>Total: R$ <?= number_format($total, 2, ',', '.') ?></h2>
            <button>Finalizar Compra</button>
            <br><br>
            <a href="loja.php">Continuar comprando</a>
        <?php endif; ?>
    </body>
    </html>