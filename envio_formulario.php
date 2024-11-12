<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";  // Substitua com seu nome de usuário do banco de dados
    $password = "aluno@etep";  // Substitua com sua senha do banco de dados
    $dbname = "loja";  // Nome do seu banco de dados

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Receber dados do formulário
    $nome = $conn->real_escape_string($_POST["nome"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $assunto = $conn->real_escape_string($_POST["assunto"]);
    $mensagem = $conn->real_escape_string($_POST["mensagem"]);

    // Preparar e executar a consulta para inserir os dados na tabela 'contato'
    $sql = "INSERT INTO contato (nome, email, assunto, mensagem) VALUES ('$nome', '$email', '$assunto', '$mensagem')";

    if ($conn->query($sql) === TRUE) {
        // Sucesso na inserção
        echo "<p>Mensagem enviada com sucesso!</p>";
    } else {
        // Erro ao inserir
        echo "<p>Erro ao enviar mensagem: " . $conn->error . "</p>";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    echo "<p>Erro: O formulário não foi enviado corretamente.</p>";
}
?>
