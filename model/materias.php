<?php
include("conexao.php");


session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

function adicionarMateria($nome, $emailUsuario, $professor, $status, $descricao) {
    global $con;

    // Consulta SQL para obter o ID do usuário com base no email
    $queryUsuario = "SELECT id FROM usuarios WHERE email = ?";
    $stmtUsuario = mysqli_prepare($con, $queryUsuario);
    mysqli_stmt_bind_param($stmtUsuario, "s", $emailUsuario);
    mysqli_stmt_execute($stmtUsuario);
    mysqli_stmt_bind_result($stmtUsuario, $usuario_id);
    mysqli_stmt_fetch($stmtUsuario);
    mysqli_stmt_close($stmtUsuario);

    // Verifica se o ID do usuário foi encontrado
    if ($usuario_id) {
        $queryMateria="INSERT INTO materias (nome, professor, status, descricao, usuario_id) VALUES (?, ?, ?, ?, ?)";
        $stmtMateria = mysqli_prepare($con, $queryMateria);

        // Verifique se a preparação da consulta foi bem-sucedida
        if ($stmtMateria === false) {
            die("Erro ao preparar a consulta: " . mysqli_error($con));
        }

        // Vincula os parâmetros (s = string, i = inteiro)
        mysqli_stmt_bind_param($stmtMateria, "ssssi", $nome, $professor, $status, $descricao, $usuario_id);

        // Executa a consulta
        if (!mysqli_stmt_execute($stmtMateria)) {
            die("Erro ao executar a consulta: " . mysqli_error($con));
        }

        // Fecha a declaração
        mysqli_stmt_close($stmtMateria);
    } else {
        header('Location: ../view/login.php?erro=Usuário+não+encontrado.');
        exit;
    }
}

// Função no modelo para excluir a matéria
function excluirMateria($con, $idMateria, $emailUsuario) {
    $queryDelete = "DELETE FROM materias WHERE id = ? AND usuario_id = (
                        SELECT id FROM usuarios WHERE email = ?
                    )"; 
    $stmtDelete = mysqli_prepare($con, $queryDelete);
    mysqli_stmt_bind_param($stmtDelete, "is", $idMateria, $emailUsuario);

    return mysqli_stmt_execute($stmtDelete);
}

?>
