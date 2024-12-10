<?php
include("../model/cadastroatividade.php");
include("../model/conexao.php"); // Inclua a conexão ao banco de dados

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos necessários foram enviados pelo formulário
    if (
        isset($_POST["nome"]) &&
        isset($_POST["descricao"]) &&
        isset($_POST["motivo"]) &&
        isset($_POST["responsavel"]) &&
        isset($_POST["tipo_atividade"]) &&
        isset($_POST["quantidade"]) &&
        isset($_POST["viavel"]) &&
        isset($_POST["prioridade"]) &&
        isset($_POST["prazo"]) &&
        isset($_POST["status"]) &&
        isset($_POST["materia_id"]) // Certifique-se de que o ID da matéria também seja enviado
    ) {
        // Captura os dados do formulário
        $nome = $_POST["nome"];
        $descricao = $_POST["descricao"];
        $motivo = $_POST["motivo"];
        $responsavel = $_POST["responsavel"];
        $tipo_atividade = $_POST["tipo_atividade"];
        $quantidade = $_POST["quantidade"];
        $viavel = $_POST["viavel"];
        $prioridade = $_POST["prioridade"];
        $prazo = $_POST["prazo"];
        $status = $_POST["status"];
        $materia_id = $_POST["materia_id"]; // Capturando o ID da matéria
        $emailUsuario = $_SESSION["email"];

        // Consulta para obter o usuario_id a partir do e-mail
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $emailUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $usuario_id = $row["id"];

            // Chama a função do modelo para adicionar a atividade
            adicionarAtividade(
                $nome,
                $descricao,
                $motivo,
                $responsavel,
                $tipo_atividade,
                $quantidade,
                $viavel,
                $prioridade,
                $prazo,
                $status,
                $materia_id,
                $usuario_id
            );

            // Redireciona após adicionar a atividade
            header('Location: ../view/index.php');
            exit;
        } else {
            echo "Erro: Usuário não encontrado.";
        }
    } else {
        echo "Erro: Campos obrigatórios faltando.";
    }
}
?>
