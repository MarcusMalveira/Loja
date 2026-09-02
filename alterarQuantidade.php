<?php 
    session_start();

    require_once "conexão.php";

    if(!isset($_POST['id']) || !isset($_POST['acao']) || !isset($_SESSION['carrinho'])){
        header("Location: carrinho.php");
        exit;
    }

    $id = $_POST['id'];
    $acao = $_POST['acao'];

    foreach ($_SESSION['carrinho'] as $indice => &$item){
        if($item['id'] == $id){
            if($acao === 'aumentar'){

            $stmt = $pdo->prepare("SELECT quantidade FROM produto WHERE id = ?");
            $stmt->execute([$id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            if($produto && $item['quantidade'] < $produto['quantidade']){
                $item['quantidade']++;
            }
            elseif($acao === 'diminuir'){
                $item['quantidade']--;

                if($item['quantidade'] <= 0){
                    unset($_SESSION['carrinho'][$indice]);
                }

            }
            break;
        }
    }

    unset($item);

    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    header("Location: carrinho.php");
    exit;
?>