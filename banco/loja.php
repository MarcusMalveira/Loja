<?php
    require_once "conexão.php";

    $stmt = $pdo->query("SELECT * FROM produto");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja</title>
</head>
<body>
    <h1>Loja de Produtos</h1>
    <label for="produto">Produtos Cadastrados:</label>
    <select id="produto" onchange="mostrarInformacoes()">
        <option value="">Selecione um produto</option>
        <?php foreach ($produtos as $produto): ?>
                <option
                    value="<?= $produto['id'] ?>"
                    data-preco="<?= $produto['valor'] ?>"
                    data-validade="<?= $produto['validade'] ?>"
                    data-estoque="<?= $produto['quantidade'] ?>"
                >
                    <?= $produto['nome'] ?>
                </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <button id="btnEditar" style="display: none;"> Editar Produto</button>
    <button id="btnExcluir" style="display: none;"> Excluir produto</button>
      <!-- Informações do produto -->
    <div id="informações" style="display: none;">
        <h2>Informações do Produto</h2>
        <p>Preço: <span id="preco"></span></p>
        <p>Validade: <span id="validade"></span></p>
        <p>Estoque: <span id="estoque"></span>unidade(s)</p>
        <form id="formulario-carrinho" method="POST" action="carrinho.php">
            <input type="hidden" id="produto_id" name="produto_id">
            <label for="quantidade">Quantidade:</label>
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
            </form>
    </div>

    <script>
        function mostrarInformacoes(){
        //pega o select
        const select = document.getElementById('produto');
        //pega a opção selecionada
        const opcao = select.options[select.selectedIndex];
        //pega a area das informações
        const informacoes = document.getElementById('informações');

        const btnEditar = document.getElementById('btnEditar');

        const btnExluir = document.getElementById('btnExcluir');

        //Se nenhum produto foi selecionado
        if(opcao.value === ""){
            informacoes.style.display = "none";
            btnEditar.style.display = "none";
            btnExluir.style.display = "none";
            return;
        }

        //pega os dados que vieram do banco
        const preco = opcao.dataset.preco;
        const validade = opcao.dataset.validade;
        const estoque = opcao.dataset.estoque;

        //mostra os dados na página
        document.getElementById('preco').textContent = Number(preco).toFixed(2).replace('.', ',');
        document.getElementById('validade').textContent = validade;
        document.getElementById('estoque').textContent = estoque;

        //limitar a quantidade máxima ao estoque
        document.getElementById("quantidade").max = estoque;

        //pega o id do produto e coloca no input hidden
        document.getElementById("produto_id").value = select.value;

        //mostra as informações
        informacoes.style.display = "block";

        // Mostra o botão editar
        btnEditar.style.display = "inline-block";
        // Mostra o botão excluir
        btnExluir.style.display = "inline-block";

        
        // Ao clicar, envia o ID para editar.php
        btnEditar.onclick = function() {
            window.location.href = "editar.php?id=" + select.value;
        };

        btnExluir.onclick = function() {
            if(confirm("Tem certeza que deseja excluir este produto?")){
                window.location.href = "excluir.php?id=" + select.value;
            }
        };
        }

        
        

        function adicionarCarrinho() {
            const select = document.getElementById('produto');
            const opcao = select.options[select.selectedIndex];
            const nome = opcao.textContent.trim();
            const preco = Number(opcao.dataset.preco).toFixed(2).replace('.', ',');
            const quantidade = document.getElementById('quantidade').value;
            if(quantidade < 1){
                alert("Quantidade inválida!");
                return;
            }
            alert(nome + " - " + quantidade + "unidade(s) adicionadas(s) ao carrinho. Preço unitário: R$" + preco);
        }
        </script>
</body>
</html>