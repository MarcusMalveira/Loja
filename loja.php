<?php

    require_once "conexão.php";

    $stmt = $pdo->query("SELECT * FROM produto");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Loja</title>
</head>

<body>
    <h1>Loja de Produtos</h1>
    <a href="formularioProdutos.html" id="adicionar">
        Adicionar Produto
    </a>
    <br><br>
    <label for="produto">
        Produtos Cadastrados:
    </label>
    <select id="produto" onchange="mostrarInformacoes()">
        <option value="">
            Selecione um produto
        </option>
        <?php foreach ($produtos as $produto): ?>
            <option
                value="<?= $produto['id'] ?>"
                data-preco="<?= $produto['valor'] ?>"
                data-validade="<?= $produto['validade'] ?>"
                data-estoque="<?= $produto['quantidade'] ?>"
            >
                <?= htmlspecialchars($produto['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div id="informacoes" style="display: none;">
        <h2>Informações do Produto</h2>
        <p>
            Preço:
            R$ <span id="preco"></span>
        </p>
        <p>
            Validade:
            <span id="validade"></span>
        </p>
        <p>
            Estoque:
            <span id="estoque"></span>
            unidade(s)
        </p>
        <form method="POST" action="carrinho.php">

            <input
                type="hidden"
                id="produto_id"
                name="produto_id"
            >

            <label for="quantidade">
                Quantidade:
            </label>

            <input
                type="number"
                id="quantidade"
                name="quantidade"
                min="1"
                required
            >
            <button type="submit">
                Adicionar ao Carrinho
            </button>
            <a href="carrinho.php" id="ver_carrinho">
                Ver Carrinho
            </a>
        </form>
        <br>
        <form method="GET" action="editar.php">
            <input
                type="hidden"
                id="editar_id"
                name="id"
            >
            <button type="submit" id="editar">
                Editar Produto
            </button>
        </form>
        <br>
        <!-- EXCLUIR -->
        <form method="GET" action="excluir.php">
            <input
                type="hidden"
                id="excluir_id"
                name="id"
            >
            <button type="submit" id="excluir">
                Excluir Produto
            </button>
        </form>
    </div>
    <script>
        function mostrarInformacoes() {
            const select = document.getElementById("produto");
            
            const opcao = select.options[
                select.selectedIndex
            ];
            const informacoes = document.getElementById("informacoes");
            if (opcao.value === "") {
                informacoes.style.display = "none";
                return;
            }
            const preco = opcao.dataset.preco;
            const validade = opcao.dataset.validade;
            const estoque = opcao.dataset.estoque;

            document.getElementById("preco").textContent = Number(preco).toFixed(2).replace(".", ",");
            
            document.getElementById("validade").textContent = validade;
            
            document.getElementById("estoque").textContent = estoque;
            
            document.getElementById("quantidade").max = estoque;
            
            document.getElementById("produto_id").value = opcao.value;
            
            document.getElementById("editar_id").value = opcao.value;
            
            document.getElementById("excluir_id").value = opcao.value;
            
            informacoes.style.display = "block";
        }
    </script>
</body>
</html>