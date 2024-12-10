<?php
include("../model/conexao.php");
include("useful/header2.php");

session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

$idAtividade = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta SQL para buscar os detalhes da atividade específica
$queryAtv = "SELECT nome, data_entrega, materia_id FROM atividades WHERE id = ? AND usuario_id = (
                SELECT id FROM usuarios WHERE email = ?
            )";
$stmtAtv = mysqli_prepare($con, $queryAtv);
mysqli_stmt_bind_param($stmtAtv, "is", $idAtividade, $_SESSION["email"]);
mysqli_stmt_execute($stmtAtv);
$resultAtv = mysqli_stmt_get_result($stmtAtv);

if ($row = mysqli_fetch_assoc($resultAtv)) {
    $nomeAtividade = $row['nome'];
    $dataEntrega = $row['data_entrega'];
    $materiaId = $row['materia_id'];
} else {
    header('Location: ../view/index.php?erro=Atividade+nao+encontrada.');
    exit;
}

// Consulta para obter todas as matérias do usuário
$queryMaterias = "SELECT id, nome FROM materias WHERE usuario_id = (
                    SELECT id FROM usuarios WHERE email = ?
                )";
$stmtMaterias = mysqli_prepare($con, $queryMaterias);
mysqli_stmt_bind_param($stmtMaterias, "s", $_SESSION["email"]);
mysqli_stmt_execute($stmtMaterias);
$resultMaterias = mysqli_stmt_get_result($stmtMaterias);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Atividade</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-dark">

    <div class="container mt-5 text-center">
        <h1 class="text-center mb-4 text-white-75">Editar Atividade</h1>
        <div class="d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <form method="post" action="../controller/updateAtividade.php">
                    <div class="mb-3">
                        <label for="nome" class="form-label text-white-50">Nome da Atividade</label>
                        <textarea class="form-control" id="nome" name="nome" rows="1" required><?php echo htmlspecialchars($nomeAtividade); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="data_entrega" class="form-label text-white-50">Data de Entrega</label>
                        <input type="date" class="form-control" id="data_entrega" name="data_entrega" value="<?php echo htmlspecialchars($dataEntrega); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="materia" class="form-label text-white-50">Matéria</label>
                        <select class="form-select" id="materia" name="materia_id" required>
                            <?php while ($materia = mysqli_fetch_assoc($resultMaterias)) { ?>
                                <option value="<?php echo $materia['id']; ?>" <?php echo ($materia['id'] == $materiaId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($materia['nome']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $idAtividade; ?>">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
