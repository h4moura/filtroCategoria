<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "grafica";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se uma categoria ou termo de busca foi selecionado
$categoria_id = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

$sql_produtos = "SELECT * FROM produtos WHERE 1=1";
if ($categoria_id) {
    $sql_produtos .= " AND categoria_id = " . $categoria_id;
}
if ($search) {
    $sql_produtos .= " AND nome LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$result_produtos = $conn->query($sql_produtos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica - Consulta de Produtos</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="menu">
        <a href="index.php">Consulta de Produtos</a>
        <a href="cadastro.php">Cadastro de Produtos</a>
    </div>

    <div class="container">
        <h1>Consulta de Produtos</h1>
        <form method="GET" action="index.php">
            <label for="categoria">Filtrar por categoria:</label>
            <select name="categoria" id="categoria">
                <option value="">Todas as categorias</option>
                <?php while ($categoria = $result_categorias->fetch_assoc()) { ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo $categoria_id == $categoria['id'] ? 'selected' : ''; ?>>
                        <?php echo $categoria['nome']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="search">Buscar produto:</label>
            <input type="text" name="search" id="search" value="<?php echo $search; ?>" placeholder="Digite o nome do produto">

            <button type="submit">Filtrar</button>
        </form>

        <div class="produtos">
            <?php if ($result_produtos->num_rows > 0) {
                while ($produto = $result_produtos->fetch_assoc()) { ?>
                    <div class="produto">
                        <h2><?php echo $produto['nome']; ?></h2>
                        <p><?php echo $produto['descricao']; ?></p>
                        <p>Preço: R$<?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    </div>
            <?php }
            } else {
                echo "<p>Nenhum produto encontrado.</p>";
            } ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const produtos = document.querySelectorAll('.produto');

            // Reaplica a animação quando a página é carregada ou os itens são filtrados
            produtos.forEach(produto => {
                produto.style.animation = 'none';
                setTimeout(() => {
                    produto.style.animation = '';
                }, 100);
            });
        });
    </script>
</body>

</html>