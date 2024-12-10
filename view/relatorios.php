<?php
include("../model/conexao.php");
include("useful/footeratv.php");
include("useful/header.php");

session_start();

if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

// Consultar o número de matérias
$queryMateria = "SELECT COUNT(*) AS total FROM materias";
$result = mysqli_query($con, $queryMateria);
if (!$result) {
    echo "Erro na consulta: " . mysqli_error($con);
    exit();
}
$row = mysqli_fetch_assoc($result);
if ($row['total'] == 0) {
    header('Location: ../view/materias.php?mensagem=Cadastre+uma+materia+antes+de+acessar+relatorios.');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <title>Relatórios</title>
</head>
<body class="pagina-relatorios"> 
    <main>
        <div class="container-fluid px-4 text-center">
            <h1 class="mt-4 text-primary">Relatórios de Atividades</h1>
            <h5 class="mt-4 text-white">Escolha uma opção abaixo :</h5> <br>
            <div class="button-container d-flex justify-content-center">
                <a href="historico_feedback.php" class="btn btn-info me-3">Histórico de Feedback</a>
                <a href="grafico_progresso.php" class="btn btn-success me-3">Gráfico de Progresso</a>
            </div>
        </div>
    </main>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
