<?php

    session_start();

    require_once "conexão.php";

    //recebe os dados enviados do formulário
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    //busca o produto no banco de dados
    $sql = "SELECT * FROM produto WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $produto_id]);

    //pega o produto encontrado
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    //verifica se o produto existe
    if(!$produto){
        echo "Produto não encontrado.";
        exit;
    }

    //verifica se a quatintidade solicitada é valida

    if($quantidade < 1){
        echo "Quantidade inválida.";
        exit;
    }

    //verifica se existe estoque suficiente
    if($quantidade > $produto['quantidade']){
        echo "Estoque insuficiente.";
        exit;
    }

    //Cria o carrinho na sessão caso não exista
    if(!isset($_SESSION['carrinho'])){
        $_SESSION['carrinho'] = [];
    }

    //verifica se o produto já está no carrinho
    if(isset($_SESSION['carrinho'][$idproduto])){
        //se existe, apenas atualiza a quantidade
        $_SESSION['carrinho'][$produto_id]['quantidade'] += $quantidade;
    }else{
        //se não existe, adiciona o produto ao carrinho
        $_SESSION['carrinho'][$produto_id] = [
            'nome' => $produto['nome'],
            'valor' => $produto['valor'],
            'quantidade' => $quantidade
        ];
    }

    //vai para o carrinho
    header("Location: carrinho.php");
    exit;

?>