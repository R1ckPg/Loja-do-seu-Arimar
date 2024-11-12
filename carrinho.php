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

// Remover produto do carrinho
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    if (isset($_SESSION['carrinho'][$productId])) {
        unset($_SESSION['carrinho'][$productId]);
    }
    header("Location: carrinho.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Imagens/Logo.png" type="image/x-icon">
    <title>Loja do seu Arimar</title>
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
        <h2 class="cart-title">Carrinho de Compras</h2>

        <div id="cart-items">
            <?php
            if (!empty($_SESSION['carrinho'])) {
                $totalCarrinho = 0; // Variável para somar o total do carrinho
                foreach ($_SESSION['carrinho'] as $productId => $quantity) {
                    $sql = "SELECT * FROM produtos WHERE id = $productId";
                    $result = $conn->query($sql);
                    if ($row = $result->fetch_assoc()) {
                        $totalItem = $row["preco"] * $quantity;
                        $totalCarrinho += $totalItem; // Soma o total do carrinho

                        echo "<div class='cart-item'>";
                        echo "<h3>" . htmlspecialchars($row["nome"]) . "</h3>";
                        echo "<p>Quantidade: $quantity</p>";
                        echo "<p>Preço Unitário: R$ " . number_format($row["preco"], 2, ',', '.') . "</p>";
                        echo "<p>Total: R$ " . number_format($totalItem, 2, ',', '.') . "</p>";
                        echo "<a href='carrinho.php?remove=" . $productId . "' class='remove-btn'>Remover</a>";
                        echo "</div>";
                    }
                }
                echo "<div class='cart-total'>";
                echo "<h3>Total do Carrinho: R$ " . number_format($totalCarrinho, 2, ',', '.') . "</h3>";
                echo "</div>";
            } else {
                echo "<p>Seu carrinho está vazio.</p>";
            }
            ?>
        </div>

        <div class="cart-buttons">
            <button onclick="alert('Processo de pagamento ainda não implementado')" class="pay-btn">Pagar</button>
            <button onclick="window.location.href='produtos.php'" class="continue-btn">Continuar Comprando</button>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; Loja do Arimar | Feito por Henrique Pereira e João Victor | Criado em 2024</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
