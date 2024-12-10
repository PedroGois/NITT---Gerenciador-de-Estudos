<?php
include("conexao.php");

function adicionarAtividade(
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
) {
    global $con; // Usando a variável $con da conexão definida no arquivo 'conexao.php'
    
    // Verifique se a conexão foi feita corretamente
    if ($con->connect_error) {
        die("Erro de conexão: " . $con->connect_error);
    }

    // Consulta SQL para inserir os dados na tabela `atividades`
    $sql = "INSERT INTO atividades 
            (nome, descricao, motivo, responsavel, tipo_atividade, quantidade, viavel, prioridade, prazo, status, materia_id, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare a consulta
    $stmt = $con->prepare($sql);
    
    // Verifique se a preparação foi bem-sucedida
    if (!$stmt) {
        die("Erro na preparação: " . $con->error);
    }

    // Vincula os parâmetros à consulta
    $stmt->bind_param(
        "sssssisiisii", // Tipos de dados correspondentes
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

    // Execute a consulta e verifique por erros
    if ($stmt->execute()) {
        echo "Atividade adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar atividade: " . $stmt->error;
    }

    // Feche a consulta e a conexão
    $stmt->close();
    $con->close();
}

?>
