<?php 
    require_once 'conexão.php';

    if(!isset($_GET['id'])){
        die ("ID do produto não encontrado");
    }

    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM produto WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto){
        die ("Produto não encontrado");
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $nome = $_POST['nome'];
        $valor = $_POST['valor'];
        $validade = $_POST['validade'];
        $quantidade = $_POST['quantidade'];

        $stmt = $pdo->prepare("UPDATE produto SET nome = ?, valor = ?, validade = ?, quantidade = ? WHERE id = ?");
        $stmt->execute([$nome, $valor, $validade, $quantidade, $id]);

        header("Location: loja.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edita Produto</title>
</head>
<body>
    <h1>Edita Produto</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        <br>
        <label for="valor">Valor:</label>
        <input type="number" id="valor" name="valor" step="0.01" value="<?= htmlspecialchars($produto['valor']) ?>" required>
        <br>
        <label for="validade">Validade:</label>
        <input type="date" id="validade" name="validade" value="<?= htmlspecialchars($produto['validade']) ?>" required>
        <br>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" value="<?= htmlspecialchars($produto['quantidade']) ?>" required>
        <br>
        <input type="submit" value="Atualizar">
    </form>

    <a href="loja.php">Voltar para a Loja</a>
</body>
</html>