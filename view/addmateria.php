<?php
include("../model/conexao.php");
include("useful/header2.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

// Consulta SQL para buscar as matérias do usuário logado
$emailUsuario = $_SESSION["email"];
$queryMat = "SELECT id, nome FROM materias WHERE usuario_id = (
                SELECT id FROM usuarios WHERE email = ?
            )";
$stmtMat = mysqli_prepare($con, $queryMat);
mysqli_stmt_bind_param($stmtMat, "s", $emailUsuario);
mysqli_stmt_execute($stmtMat);
$resultMat = mysqli_stmt_get_result($stmtMat);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar uma Matéria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="pagina-materias">

    <div class="container mt-5 text-center">
        <h1 class="text-center mb-4 text-white-75">Adicionar Nova Matéria</h1> 
        <div class="d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <form method="post" action="../controller/validamateria.php">
                    <div class="mb-3">
                        <label for="nome" class="form-label text-white-50">Nome Matéria</label>
                        <textarea class="form-control" id="nome" name="nome" rows="1" required placeholder="Sigla ou título da matéria..."></textarea>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-8">
                            <label for="professor" class="form-label text-white-50">Professor</label>
                            <input type="text" class="form-control" id="professor" name="professor" required placeholder="Nome do professor...">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label text-white-50">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Selecione um status</option>
                                <option value="Em andamento" class="status-amarelo">Em andamento</option>
                                <option value="Sem pendências" class="status-verde">Sem pendências</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label text-white-50"> Como você enxerga essa matéria ? </label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required placeholder="Digite o que você acha sobre essa matéria, vamos entender se isso pode mudar com o tempo..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar Matéria</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
