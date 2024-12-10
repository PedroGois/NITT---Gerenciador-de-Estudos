<?php
include("../model/conexao.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['professor']) && isset($_POST['descricao'])) {
    $idMateria = intval($_POST['id']);
    $nomeMateria = trim($_POST['nome']);
    $professorMateria = trim($_POST['professor']);
    $descricaoMateria = trim($_POST['descricao']);
    $emailUsuario = $_SESSION["email"];

    // Atualizar todos os campos da matéria no banco de dados
    $queryUpdate = "UPDATE materias SET nome = ?, professor = ?, descricao = ? WHERE id = ? AND usuario_id = (
                        SELECT id FROM usuarios WHERE email = ?
                    )";
    $stmtUpdate = mysqli_prepare($con, $queryUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "sssis", $nomeMateria, $professorMateria, $descricaoMateria, $idMateria, $emailUsuario);

    if (mysqli_stmt_execute($stmtUpdate)) {
        header('Location: ../view/materias.php?sucesso=Materia+atualizada+com+sucesso.');
        exit;
    } else {
        header('Location: ../view/editMateria.php?id=' . $idMateria . '&erro=Falha+ao+atualizar+matéria.');
        exit;
    }
} else {
    header('Location: ../view/index.php?erro=Dados+inválidos.');
    exit;
}
