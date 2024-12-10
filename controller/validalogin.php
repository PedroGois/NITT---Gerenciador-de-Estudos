<?php
// Inclua o arquivo de conexão
include("../model/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos foram preenchidos
    if (isset($_POST["email"]) && isset($_POST["senha"])) {
        // Recupera os valores dos campos
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        // Consulta SQL para verificar se o usuário existe
        $query = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
        // Preparar a declaração
        $stmt = $con->prepare($query);
        // Vincular parâmetros
        $stmt->bind_param("ss", $email, $senha);
        // Executar a consulta
        $stmt->execute();
        // Armazenar o resultado
        $result = $stmt->get_result();

        // Verificar se o usuário foi encontrado
        if ($result->num_rows == 1) {
            // Usuário encontrado, redirecionar para a página principal ou para onde for necessário
            session_start();
            $_SESSION["email"] = $email; // Armazenar o email do usuário na sessão, se necessário
            header('Location: ../view/index.php');
            exit;
        } else {
            // Usuário não encontrado, redirecionar de volta para a página de login com mensagem de erro
            header('Location: ../view/login.php?erro=Email+ou+senha+incorretos.');
            exit;
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        // Campos não preenchidos, redirecionar de volta para a página de login com mensagem de erro
        header('Location: ../view/login.php?erro=Por+favor,+insira+o+email+e+a+senha.');
        exit;
    }
}
?>
