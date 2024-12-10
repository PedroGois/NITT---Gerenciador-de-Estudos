<?php
include("../model/conexao.php");
include("useful/header2.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

$idMateria = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idMateria <= 0) {
    header('Location: ../view/index.php?erro=Materia+invalida.');
    exit;
}

// Consulta SQL para buscar os detalhes da matéria específica
$queryMat = "SELECT nome, professor, descricao FROM materias WHERE id = ? AND usuario_id = (
                SELECT id FROM usuarios WHERE email = ?
            )";
$stmtMat = mysqli_prepare($con, $queryMat);
mysqli_stmt_bind_param($stmtMat, "is", $idMateria, $_SESSION["email"]);
mysqli_stmt_execute($stmtMat);
$resultMat = mysqli_stmt_get_result($stmtMat);

if ($row = mysqli_fetch_assoc($resultMat)) {
    $nomeMateria = $row['nome'];
    $professorMateria = $row['professor'];

    $descricaoMateria = $row['descricao'];
} else {
    header('Location: ../view/index.php?erro=Materia+nao+encontrada.');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Matéria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-dark">

    <div class="container mt-5 text-center">
        <h1 class="text-center mb-4 text-white-75">Editar Matéria</h1>
        <div class="d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <form method="post" action="../controller/updateMateria.php">
                    <div class="mb-3">
                        <label for="nome" class="form-label text-white-50">Nome da Matéria</label>
                        <textarea class="form-control" id="nome" name="nome" rows="1" required><?php echo htmlspecialchars($nomeMateria); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="professor" class="form-label text-white-50">Professor</label>
                        <input type="text" class="form-control" id="professor" name="professor" value="<?php echo htmlspecialchars($professorMateria); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label text-white-50">Descrição (Descreva seus motivos para finalizar essa matéria)</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo htmlspecialchars($descricaoMateria); ?></textarea>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $idMateria; ?>">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
