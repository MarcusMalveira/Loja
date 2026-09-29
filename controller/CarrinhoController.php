<?php

session_start();

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../model/ProdutoModel.php';

$model = new ProdutoModel($pdo);

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$acao = $_GET['acao'] ?? 'ver';

switch ($acao) {
    case 'ver':
        $carrinho = $_SESSION['carrinho'];
        $total = 0;
        require __DIR__ . '/../view/carrinho.php';
        break;

    case 'adicionar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ProdutoController.php?acao=listar');
            exit;
        }

        $produtoId = (int) $_POST['produto_id'];
        $quantidade = (int) $_POST['quantidade'];
        $produto = $model->buscarPorId($produtoId);

        if (!$produto) {
            die('Produto não encontrado.');
        }

        if ($quantidade < 1 || $quantidade > $produto['quantidade']) {
            die('Quantidade inválida ou estoque insuficiente.');
        }

        $produtoEncontrado = false;

        foreach ($_SESSION['carrinho'] as &$itemCarrinho) {
            if ($itemCarrinho['id'] == $produto['id']) {
                $novaQuantidade = $itemCarrinho['quantidade'] + $quantidade;

                if ($novaQuantidade > $produto['quantidade']) {
                    die('Estoque insuficiente.');
                }

                $itemCarrinho['quantidade'] = $novaQuantidade;
                $produtoEncontrado = true;
                break;
            }
        }
        unset($itemCarrinho);

        if (!$produtoEncontrado) {
            $_SESSION['carrinho'][] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'valor' => $produto['valor'],
                'quantidade' => $quantidade
            ];
        }

        header('Location: CarrinhoController.php?acao=ver');
        exit;

    case 'alterar':
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['id'], $_POST['operacao'])
        ) {
            header('Location: CarrinhoController.php?acao=ver');
            exit;
        }

        $id = (int) $_POST['id'];
        $operacao = $_POST['operacao'];
        $produtoBanco = $model->buscarPorId($id);

        foreach ($_SESSION['carrinho'] as $indice => &$item) {
            if ($item['id'] != $id) {
                continue;
            }

            if ($operacao === 'aumentar' && $produtoBanco) {
                if ($item['quantidade'] < $produtoBanco['quantidade']) {
                    $item['quantidade']++;
                }
            } elseif ($operacao === 'diminuir') {
                $item['quantidade']--;

                if ($item['quantidade'] <= 0) {
                    unset($_SESSION['carrinho'][$indice]);
                }
            } elseif ($operacao === 'definir' && $produtoBanco) {
                $novaQuantidade = (int) ($_POST['quantidade'] ?? 0);

                if ($novaQuantidade <= 0) {
                    unset($_SESSION['carrinho'][$indice]);
                } elseif ($novaQuantidade <= $produtoBanco['quantidade']) {
                    $item['quantidade'] = $novaQuantidade;
                }
            }

            break;
        }
        unset($item);

        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);

        header('Location: CarrinhoController.php?acao=ver');
        exit;

    case 'limpar':
        $_SESSION['carrinho'] = [];
        header('Location: CarrinhoController.php?acao=ver');
        exit;

    case 'finalizar':
        if (empty($_SESSION['carrinho'])) {
            die("O carrinho está vazio.");
        }

        try {
            $model->finalizarCompra($_SESSION['carrinho']);
            $_SESSION['carrinho'] = [];
            require __DIR__ . '/../view/finalizar_compra.php';
        } catch (Throwable $e) {
            die('Erro ao finalizar a compra: ' . $e->getMessage());
        }
        break;

    default:
        http_response_code(404);
        echo 'Ação não encontrada.';
}
