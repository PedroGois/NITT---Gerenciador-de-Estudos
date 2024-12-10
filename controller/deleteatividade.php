<?php
include("../model/conexao.php");
include("../model/atividades.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

if (isset($_GET['id'])) {
    $idAtividade = intval($_GET['id']);
    $emailUsuario = $_SESSION["email"];
    
    

    // Tente excluir a atividade
    $resultado = excluirAtividade($con, $idAtividade, $emailUsuario);
    
    if ($resultado) {
        header('Location: ../view/index.php?sucesso=Atividade+excluida+com+sucesso.');
        exit;
    } else {
        header('Location: ../view/index.php?erro=Falha+ao+excluir+atividade.');
        exit;
    }
} 
?>
