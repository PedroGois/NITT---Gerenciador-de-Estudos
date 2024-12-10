<?php
include("conexao.php");


session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}


// Função no modelo para excluir a atividade
function excluirAtividade($con, $idAtividade, $emailUsuario) {
    $queryDelete = "DELETE FROM atividades WHERE id = ? AND usuario_id = (
                        SELECT id FROM usuarios WHERE email = ?
                    )"; 
    $stmtDelete = mysqli_prepare($con, $queryDelete);
    mysqli_stmt_bind_param($stmtDelete, "is", $idAtividade, $emailUsuario);

    return mysqli_stmt_execute($stmtDelete);
}
