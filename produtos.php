<?php
session_start();

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "aluno@etep";
$dbname = "loja";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Adicionar ao carrinho
if (isset($_GET['add_to_cart'])) {
    $productId = $_GET['add_to_cart'];

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Adiciona ou incrementa a quantidade do produto no carrinho
    if (isset($_SESSION['carrinho'][$productId])) {
        $_SESSION['carrinho'][$productId]++;
    } else {
        $_SESSION['carrinho'][$productId] = 1;
    }

    header("Location: produtos.php");
    exit();
}

// Remover produto do banco de dados
if (isset($_POST['remove_product']) && isset($_POST['password'])) {
    $productId = $_POST['remove_product'];
    $password = $_POST['password'];

    if ($password === "1234bobo") {
        $sql = "DELETE FROM produtos WHERE id = $productId";
        if ($conn->query($sql) === TRUE) {
            echo "Produto removido com sucesso!";
        } else {
            echo "Erro ao remover o produto: " . $conn->error;
        }
    } else {
        echo "Senha incorreta!";
    }
}

// Consultar produtos no banco de dados
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Imagens/Logo.png" type="image/x-icon">
    <title>Loja do seu Arimar</title>
    <script>
        function confirmRemove(productId) {
            const password = prompt("Digite a senha para remover o produto:");
            if (password !== null) {
                const form = document.createElement("form");
                form.method = "post";
                form.action = "produtos.php";
                
                const inputProductId = document.createElement("input");
                inputProductId.type = "hidden";
                inputProductId.name = "remove_product";
                inputProductId.value = productId;
                form.appendChild(inputProductId);
                
                const inputPassword = document.createElement("input");
                inputPassword.type = "hidden";
                inputPassword.name = "password";
                inputPassword.value = password;
                form.appendChild(inputPassword);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <h1 class="logo">Loja do seu Arimar</h1>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="carrinho.php">Carrinho</a></li>
                <li><a href="contato.html">Contato</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="main-content">
        <h2 class="products-title">Produtos Disponíveis</h2>
        <div class="products-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    echo "<h3 class='product-name'>" . htmlspecialchars($row["nome"]) . "</h3>";
                    echo "<img class='product-image' src='" . htmlspecialchars($row["imagem"]) . "' alt='" . htmlspecialchars($row["nome"]) . "' />";
                    echo "<p class='product-price'>Preço: R$ " . number_format($row["preco"], 2, ',', '.') . "</p>";
                    echo "<a href='produtos.php?add_to_cart=" . $row["id"] . "' class='add-to-cart-btn'>Adicionar ao Carrinho</a>";
                    echo "<button onclick='confirmRemove(" . $row["id"] . ")' class='remove-btn'>Remover Produto</button>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-products'>Nenhum produto encontrado.</p>";
            }
            ?>
        </div>
    </main>

    <div class="options-container">
        <a href="adiconar-produto.php" class="add-product-btn">Adicionar Produto</a>
        <a href="carrinho.php" class="view-cart-btn">Visualizar Carrinho</a>
    </div>

    <footer class="footer">
        <p>&copy; Loja do Arimar | Feito por Henrique Pereira e João Victor | Criado em 2024</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
