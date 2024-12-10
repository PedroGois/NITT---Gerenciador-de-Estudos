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
    <title>Adicionar Nova Atividade</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .form-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
        }
        .form-row .form-group {
            flex: 1;
            margin-right: 10px;
        }
        .form-row .form-group:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body class="pagina-atividades">
<div class="container mt-5">
    <h1 class="text-center mb-4 text-white-75">Adicionar Nova Atividade</h1>
    <div class="d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <form method="post" action="../controller/validaatividade.php" class="form-container text-center">
                <div class="mb-3">
                    <label for="nome" class="form-label text-white-50">Título da Atividade</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Revisão de matéria para prova" required>
                </div>
                
                <hr class="text-white-50">

                <div class="mb-3">
                    <label for="descricao" class="form-label text-white-50">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descreva brevemente o que será feito" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label text-white-50">Razão para Realizar</label>
                    <textarea class="form-control" id="motivo" name="motivo" rows="2" placeholder="Por que esta atividade é importante?"></textarea>
                </div>
                
                <hr class="text-white-50">

                <div class="form-row mb-3">
                    <div class="form-group">
                        <label for="responsavel" class="form-label text-white-50">Responsável</label>
                        <select class="form-select" id="responsavel" name="responsavel" required>
                            <option value="Eu">Eu</option>
                            <option value="Grupo">Grupo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipo_atividade" class="form-label text-white-50">Tipo de Atividade</label>
                        <select class="form-select" id="tipo_atividade" name="tipo_atividade" required>
                            <option value="Horas">Horas</option>
                            <option value="Lista de Exercício">Lista de Exercício</option>
                            <option value="Documento">Documento</option>
                            <option value="Projeto">Projeto</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="quantidade" class="form-label text-white-50">Quantidade</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" placeholder="Informe a quantidade necessária">
                </div>
                
                <div class="mb-3">
                    <label for="viavel" class="form-label text-white-50">É Viável?</label>
                    <select class="form-select" id="viavel" name="viavel">
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <hr class="text-white-50">

                <div class="form-row mb-3">
                    <div class="form-group">
                        <label for="prioridade" class="form-label text-white-50">Nível de Prioridade</label>
                        <select class="form-select" id="prioridade" name="prioridade" required>
                            <option value="1">1 (Mais Urgente)</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5 (Menos Urgente)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prazo" class="form-label text-white-50">Data Limite</label>
                        <input type="date" class="form-control" id="prazo" name="prazo">
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="form-group">
                        <label for="status" class="form-label text-white-50">Status Atual</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Pendente">Pendente</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluída">Concluída</option>
                        </select>
                    </div>
                </div>

                <hr class="text-white-50">

                <div class="mb-3">
                    <label for="materia_id" class="form-label text-white-50">Selecione a Matéria</label>
                    <select class="form-select" id="materia_id" name="materia_id" required>
                        <?php
                            while ($row = mysqli_fetch_assoc($resultMat)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Adicionar Atividade</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
