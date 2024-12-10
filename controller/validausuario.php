<?php
// Inclua o arquivo de conexão
include("../model/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos foram preenchidos
    if (isset($_POST["primeiroNome"]) && isset($_POST["sobrenome"]) && isset($_POST["email"]) && isset($_POST["senha"]) && isset($_POST["confirmaSenha"])) {
        // Recupera os valores dos campos
        $primeiroNome = $_POST["primeiroNome"];
        $sobrenome = $_POST["sobrenome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $confirmaSenha = $_POST["confirmaSenha"];

        // Verifica se a senha e a confirmação de senha são iguais
        if ($senha === $confirmaSenha) {
            // Senha válida, continuar com o cadastro

            // Consulta SQL para inserir dados na tabela "usuarios"
            $query = "INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)";
            // Preparar a declaração
            $stmt = $con->prepare($query);
            // Vincular parâmetros
            $stmt->bind_param("ssss", $primeiroNome, $sobrenome, $email, $senha);
            // Executar a consulta
            $stmt->execute();
            
            // Verificar se a inserção foi bem-sucedida
            if ($stmt->affected_rows > 0) {
                // Inserção bem-sucedida
                header('Location: ../view/login.php?success=Registrado+com+sucesso+,realize+o+login!');
                exit;
            } else {
                // Falha na inserção
                header('Location: ../view/registro.php?erro=Erro+ao+cadastrar+o+usuário.');
                exit;
            }

            // Fechar a declaração
            $stmt->close();
        } else {
            // Senha e confirmação de senha não coincidem
            header('Location: ../view/registro.php?erro=A+senha+e+a+confirmação+de+senha+não+são+iguais.');
            exit;
        }
    } else {
        // Campos não preenchidos
        header('Location: ../view/registro.php?erro=Por+favor,+preencha+todos+os+campos.');
        exit;
    }
}
?>
