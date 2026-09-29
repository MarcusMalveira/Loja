<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../model/ProdutoModel.php';

$model = new ProdutoModel($pdo);
$acao = $_GET['acao'] ?? 'listar';

switch ($acao) {
    case 'listar':
        $produtos = $model->listarTodos();
        require __DIR__ . '/../view/loja.php';
        break;

    case 'formulario':
        require __DIR__ . '/../view/formularioProdutos.php';
        break;

    case 'cadastrar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ProdutoController.php?acao=formulario');
            exit;
        }

        $model->cadastrarOuSomar(
            trim($_POST['nome']),
            (float) $_POST['valor'],
            (int) $_POST['quantidade'],
            $_POST['validade']
        );

        header('Location: ProdutoController.php?acao=listar');
        exit;

    case 'editar':
        if (!isset($_GET['id'])) {
            die('ID do produto não encontrado');
        }

        $id = (int) $_GET['id'];
        $produto = $model->buscarPorId($id);

        if (!$produto) {
            die('Produto não encontrado');
        }

        require __DIR__ . '/../view/editar.php';
        break;

    case 'atualizar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            die('Dados inválidos para atualização');
        }

        $model->atualizar(
            (int) $_POST['id'],
            trim($_POST['nome']),
            (float) $_POST['valor'],
            (int) $_POST['quantidade'],
            $_POST['validade']
        );

        header('Location: ProdutoController.php?acao=listar');
        exit;

    case 'excluir':
        if (!isset($_GET['id'])) {
            die('ID do produto não encontrado');
        }

        $id = (int) $_GET['id'];

        if (!$model->buscarPorId($id)) {
            die('Produto não encontrado');
        }

        $model->excluir($id);

        header('Location: ProdutoController.php?acao=listar');
        exit;

    default:
        http_response_code(404);
        echo 'Ação não encontrada.';
}
