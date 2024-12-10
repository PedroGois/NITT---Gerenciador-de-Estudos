<?php
include("../model/conexao.php");
include("../model/materias.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

if (isset($_GET['id'])) {
    $idMateria = intval($_GET['id']);
    $emailUsuario = $_SESSION["email"];
    
    // Verifica se há atividades associadas a essa matéria
    $queryAtividades = "SELECT COUNT(*) FROM atividades WHERE materia_id = ?";
    $stmtAtividades = mysqli_prepare($con, $queryAtividades);
    mysqli_stmt_bind_param($stmtAtividades, "i", $idMateria);
    mysqli_stmt_execute($stmtAtividades);
    mysqli_stmt_bind_result($stmtAtividades, $numAtividades);
    mysqli_stmt_fetch($stmtAtividades);
    mysqli_stmt_close($stmtAtividades);
    
    if ($numAtividades > 0) {
        header('Location: ../view/materias.php?erro=Não+é+possível+excluir+a+matéria.+Há+atividades+associadas.');
        exit;
    }
    

    // Tente excluir a matéria
    $resultado = excluirMateria($con, $idMateria, $emailUsuario);
    
    if ($resultado) {
        header('Location: ../view/materias.php?sucesso=Materia+excluida+com+sucesso.');
        exit;
    } else {
        header('Location: ../view/materias.php?erro=Falha+ao+excluir+matéria.');
        exit;
    }
} 
?>
