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
            $item = ['id' => $produto['id'], 'nome' => $produto['nome'], 'valor' => $produto['valor'], 'quantidade' => $quantidade];
            $_SESSION['carrinho'][] = $item;
        }
    }

    header("Location: carrinho.php");
    exit;
}

$carrinho = $_SESSION['carrinho'];
$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
</head>
<body>
    <h1>Meu Carrinho</h1>

    <?php if (empty($carrinho)): ?>
        <p>O carrinho está vazio.</p>
        <a href="loja.php" id="voltar_loja">Voltar para a loja</a>
    <?php else: ?>
        <?php foreach ($carrinho as $produto): ?>
            <?php $subtotal = $produto['valor'] * $produto['quantidade']; $total += $subtotal; ?>
            <div>
                <h2><?= $produto['nome'] ?></h2>
                <p>Preço:R$<?= number_format($produto['valor'], 2, ',', '.') ?></p>
                <p>Quantidade:</p>
                <div>
                    <form method="POST" action="alterarQuantidade.php">
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                        <input type="hidden" name="acao" value="definir">
                        <input type="number" id="quantidade_carrinho_<?= $produto['id'] ?>" name="quantidade" value="<?= $produto['quantidade'] ?>" min="1">
                        <button type="submit" id="atualizar_quantidade_<?= $produto['id'] ?>">Atualizar</button>
                    </form>
                    <div style="display: flex; gap: 5px;">
                        <form method="POST" action="alterarQuantidade.php">
                            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                            <input type="hidden" name="acao" value="diminuir">
                            <button type="submit" id="diminuir">-</button>
                        </form>
                        <form method="POST" action="alterarQuantidade.php">
                            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                            <input type="hidden" name="acao" value="aumentar">
                            <button type="submit" id="aumentar">+</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <h2>Total:R$<?= number_format($total, 2, ',', '.') ?></h2>
        <form method="POST" action="limpar_carrinho.php">
            <button type="submit" id="limpar_carrinho">Limpar Carrinho</button>
        </form>
        <form method="POST" action="finalizar_compra.php">
            <button type="submit" id="finalizar_compra">Finalizar Compra</button>
        </form>
        <br><br>
        <a href="loja.php" id="continuar_comprando">Continuar comprando</a>
    <?php endif; ?>
</body>
</html>