<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root"; // Substitua com seu nome de usuário do banco de dados
    $password = "aluno@etep"; // Substitua com sua senha do banco de dados
    $dbname = "loja";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Receber dados do formulário
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    
    // Verificar se a imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagemTemp = $_FILES['imagem']['tmp_name'];
        $imagemNome = $_FILES['imagem']['name'];
        $imagemTipo = $_FILES['imagem']['type'];
        $imagemTamanho = $_FILES['imagem']['size'];
        
        // Definir o diretório onde a imagem será salva
        $diretorioDestino = 'uploads/';
        
        // Verificar se o diretório existe, caso contrário, criar
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }

        // Gerar um nome único para o arquivo
        $imagemNomeFinal = uniqid() . '-' . basename($imagemNome);
        $imagemCaminho = $diretorioDestino . $imagemNomeFinal;
        
        // Mover o arquivo enviado para o diretório de destino
        if (move_uploaded_file($imagemTemp, $imagemCaminho)) {
            // Preparar e executar a consulta para inserir o produto
            $sql = "INSERT INTO produtos (nome, descricao, imagem, preco) VALUES ('$nome', '$descricao', '$imagemCaminho', '$preco')";

            if ($conn->query($sql) === TRUE) {
                echo "<p class='success-msg'>Produto adicionado com sucesso!</p>";
                header("Location: produtos.php"); // Redirecionar para a lista de produtos após adicionar
                exit();
            } else {
                echo "<p class='error-msg'>Erro ao adicionar produto: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error-msg'>Erro ao fazer upload da imagem.</p>";
        }
    } else {
        echo "<p class='error-msg'>Por favor, selecione uma imagem para o produto.</p>";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
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
        <h2 class="add-product-title">Adicionar Novo Produto</h2>
        <form action="adiconar-produto.php" method="post" enctype="multipart/form-data" class="product-form">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required class="form-input">
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" required class="form-textarea"></textarea>
            </div>
            
            <div class="form-group">
                <label for="imagem">Imagem (Upload):</label>
                <input type="file" id="imagem" name="imagem" required class="form-input">
            </div>
            
            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" required class="form-input">
            </div>
            
            <button type="submit" class="submit-btn">Salvar Produto</button>
        </form>

        <div class="back-to-list">
            <button onclick="window.location.href='produtos.php'" class="back-btn">Voltar para Lista de Produtos</button>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; Loja do Arimar | Feito por Henrique Pereira e João Victor | Criado em 2024</p>
    </footer>
</body>
</html>
