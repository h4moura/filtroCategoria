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

// Obter as categorias para o formulário
$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];

    $sql = "INSERT INTO produtos (nome, descricao, preco, categoria_id) VALUES ('$nome', '$descricao', '$preco', '$categoria_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica - Cadastro de Produtos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="menu">
        <a href="index.php">Consulta de Produtos</a>
        <a href="cadastro.php">Cadastro de Produtos</a>
    </div>

    <div class="container">
        <h1>Cadastro de Produtos</h1>
        <form method="POST" action="cadastro.php">
            <label for="nome">Nome do Produto:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required></textarea>

            <label for="preco">Preço:</label>
            <input type="number" step="0.01" name="preco" id="preco" required>

            <label for="categoria_id">Categoria:</label>
            <select name="categoria_id" id="categoria_id">
                <?php while ($categoria = $result_categorias->fetch_assoc()) { ?>
                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                <?php } ?>
            </select>

            <button type="submit">Cadastrar Produto</button>
        </form>
    </div>
</body>
</html>
