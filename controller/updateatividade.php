<?php
include("../model/conexao.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['data_entrega']) && isset($_POST['materia_id'])) {
    $idAtividade = intval($_POST['id']);
    $nomeAtividade = trim($_POST['nome']);
    $dataEntrega = $_POST['data_entrega'];
    $materiaId = intval($_POST['materia_id']);
    $emailUsuario = $_SESSION["email"];

    // Atualizar a atividade no banco de dados
    $queryUpdate = "UPDATE atividades SET nome = ?, data_entrega = ?, materia_id = ? WHERE id = ? AND usuario_id = (
                        SELECT id FROM usuarios WHERE email = ?
                    )";
    $stmtUpdate = mysqli_prepare($con, $queryUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "ssiii", $nomeAtividade, $dataEntrega, $materiaId, $idAtividade, $emailUsuario);

    if (mysqli_stmt_execute($stmtUpdate)) {
        header('Location: ../view/index.php?sucesso=Atividade+atualizada+com+sucesso.');
        exit;
    } else {
        header('Location: ../view/editAtividade.php?id=' . $idAtividade . '&erro=Falha+ao+atualizar+atividade.');
        exit;
    }
} else {
    header('Location: ../view/index.php?erro=Dados+inválidos.');
    exit;
}
