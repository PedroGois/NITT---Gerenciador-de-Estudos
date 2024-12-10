<?php
// Exibe todos os erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui arquivos necessários
include("../model/conexao.php");
include("useful/footermat.php");
include("useful/header.php");

session_start();

// Verifica se a sessão está ativa
if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
}

// Verifica a conexão com o banco de dados
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Verifica se há mensagens de erro na URL
if (isset($_GET['erro'])) {
    $mensagemErro = urldecode($_GET['erro']);
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($mensagemErro) . '</div>';
} elseif (isset($_GET['successo'])) {
    $mensagemSucesso = urldecode($_GET['successo']);
    echo '<div class="alert alert-info" role="alert">' . htmlspecialchars($mensagemSucesso) . '</div>';
} elseif (isset($_GET['mensagem'])) {
    $mensagem = urldecode($_GET['mensagem']);
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($mensagem) . '</div>';
}

// Obtém o email do usuário atualmente logado
$emailUsuario = $_SESSION["email"];

// Consulta SQL para selecionar as matérias associadas ao usuário atual
$query = "SELECT id, nome, professor, status FROM materias WHERE usuario_id = (
            SELECT id FROM usuarios WHERE email = ?
          )";

// Prepara a consulta
$stmt = mysqli_prepare($con, $query);

if ($stmt === false) {
    die("Erro ao preparar a consulta: " . mysqli_error($con));
}

// Vincula o parâmetro de email
mysqli_stmt_bind_param($stmt, "s", $emailUsuario);

// Executa a consulta
if (!mysqli_stmt_execute($stmt)) {
    die("Erro ao executar a consulta: " . mysqli_error($con));
}

// Obtém o resultado da consulta
$result = mysqli_stmt_get_result($stmt);


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matérias</title>
    
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="pagina-materias">

    <div class="container-fluid px-4">
        <h1 class="mt-4 text-primary m-4">Aqui estão suas matérias!</h1>
        <div class="row">
            <?php
            // Verifica se há matérias retornadas pela consulta
            if (mysqli_num_rows($result) > 0) {
                // Loop através de cada linha de dados
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body1">
                                <div class="meta">
                                    <div class="meta-item">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['nome']); ?></h5>
                                    </div>
                                    <div class="meta-item">
                                        <p class="card-text">Professor: <?php echo ($row['professor']); ?></p>
                                    </div>
                                    <div class="meta-item">
                                        <p class="card-text">Status: <?php echo ($row['status']); ?></p>
                                    </div>
                                    <div class="meta-item d-flex justify-content-between">
                                        <a href="editmateria.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                            <i class="fa-solid fa-pencil"></i> Editar
                                        </a>
                                        <a href="../controller/deletemateria.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">
                                            <i class="fa-solid fa-trash"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo '<div class="col">';
                echo '<div class="alert alert-info" role="alert">Nenhuma matéria encontrada.</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
            
</body>

</html>

