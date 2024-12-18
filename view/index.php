<?php
include("../model/conexao.php");
include("useful/footeratv.php");
include("useful/header.php");

session_start();

if (!isset($_SESSION["email"])) {
    header('Location: ../view/login.php?erro=Realize+o+login.');
    exit;
} else {
    $emailUsuario = $_SESSION["email"];
    $queryAtividades = "SELECT a.*, m.nome AS nome_materia 
                    FROM atividades a
                    JOIN materias m ON a.materia_id = m.id
                    WHERE a.usuario_id = (
                        SELECT id FROM usuarios WHERE email = ?
                    )
                    ORDER BY a.prioridade ASC"; 
   
    $stmtAtividades = mysqli_prepare($con, $queryAtividades);
    mysqli_stmt_bind_param($stmtAtividades, "s", $emailUsuario);
    mysqli_stmt_execute($stmtAtividades);
    $resultAtividades = mysqli_stmt_get_result($stmtAtividades);

    $queryFraseMotivacional = "SELECT Frase FROM FrasesMotivac ORDER BY RAND() LIMIT 1";
    $resultFraseMotivacional = mysqli_query($con, $queryFraseMotivacional);
    $fraseMotivacional = mysqli_fetch_assoc($resultFraseMotivacional)['Frase'];

    $queryMateria = "SELECT COUNT(*) AS total FROM materias";
    $result = mysqli_query($con, $queryMateria);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row['total'] == 0) {
            header('Location: ../view/materias.php?mensagem=Cadastre+uma+materia+antes+de+iniciar+as+atividades.');
            exit();
        }
    } else {
        echo "Erro na consulta: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../css/cssmetas.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body class="pagina-atividades"> 
    <main class="mb-6">
        <?php 
        if (isset($_GET['erro'])) {
            $mensagemErro = urldecode($_GET['erro']);
            echo '<div class="alert alert-danger" role="alert">' . $mensagemErro . '</div>';
        } elseif (isset($_GET['successo'])) {
            $mensagemSucesso = urldecode($_GET['successo']);
            echo '<div class="alert alert-info" role="alert">' . $mensagemSucesso . '</div>';
        } elseif (isset($_GET['mensagem'])) {
            $mensagem = urldecode($_GET['mensagem']);
            echo '<div class="alert alert-danger" role="alert">' . $mensagem . '</div>';
        }    
        ?>
        <div class="meta-container row">
            <?php while ($row = mysqli_fetch_assoc($resultAtividades)) { ?>
                <div class="card-body1 col-5">
                    <div class="meta">
                        <div class="meta-item">
                            <div class="meta-value-large"><?php echo htmlspecialchars($row['nome']); ?></div>
                        </div>
                        <?php 
                        $priority = htmlspecialchars($row['prioridade']);
                        $color = '';
                        if ($priority == 1) {
                            $color = '#dc3545'; // Vermelho
                        } elseif ($priority == 2) {
                            $color = '#ff7f50'; // Coral
                        } elseif ($priority == 3) {
                            $color = '#ffd700'; // Amarelo
                        } elseif ($priority == 4) {
                            $color = '#adff2f'; // Verde limão
                        } else {
                            $color = '#1e90ff'; // Azul
                        }
                        ?>
                        <div class="meta-item">
                            <div class="meta-priority" style="color: <?php echo $color; ?>;">
                                Prioridade: <?php echo $priority; ?>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value-medium">Sobre: <?php echo htmlspecialchars($row['nome_materia']); ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value-small">Prazo: <?php echo htmlspecialchars($row['prazo']); ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value-small">Status: <?php echo htmlspecialchars($row['status']); ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-description">Descrição: <?php echo htmlspecialchars($row['descricao']); ?></div>
                        </div>
                        <div class="meta-button">
                            <div class="meta-value-play">
                                <button class="iniciar-btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#pomodoroModal" data-id="<?php echo $row['id']; ?>" data-nome="<?php echo htmlspecialchars($row['nome']); ?>" data-descricao="<?php echo htmlspecialchars($row['descricao']); ?>">Iniciar</button>
                            </div>
                            <div class="meta-value-edit">
                                <a href="editatividade.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Editar</a>
                            </div>
                            <div class="meta-value-delete">
                                <a href="../controller/deleteatividade.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Excluir</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Modal do Pomodoro -->
        
        <div class="modal fade" id="pomodoroModal" tabindex="-1" aria-labelledby="pomodoroModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header bg-dark text-light">
                        <h5 class="modal-title" id="pomodoroModalLabel">Pomodoro Timer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="timer" class="display-4 mb-4">25:00</div>
                        <div id="activityInfo" class="mb-4"></div> <!-- Informação da atividade -->
                        <button id="startTimerBtn" class="btn btn-success me-2">Iniciar</button>
                        <button id="pauseTimerBtn" class="btn btn-warning me-2" style="display: none;">Pausar</button>
                        <button id="resumeTimerBtn" class="btn btn-info me-2" style="display: none;">Retomar</button>
                        <button id="resetTimerBtn" class="btn btn-secondary me-2">Zerar</button>
                        <button id="stopTimerBtn" class="btn btn-danger">Parar</button>
                        <button id="finishTimerBtn" class="btn btn-primary me-2" style="display: none;">Concluir</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Caixa de Feedback -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel">Feedback da Atividade</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea id="feedbackText" class="form-control" placeholder="Como você se sentiu?" rows="3"></textarea>
                        <div class="mt-3">
                            <label>Como você se sentiu?</label>
                            <div>
                                <i class="fa fa-smile-o" style="cursor:pointer;" data-feeling="happy"></i>
                                <i class="fa fa-meh-o" style="cursor:pointer;" data-feeling="neutral"></i>
                                <i class="fa fa-frown-o" style="cursor:pointer;" data-feeling="sad"></i>
                            </div>
                        </div>
                        <input type="hidden" id="selectedFeeling" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="submitFeedbackBtn" class="btn btn-primary">Enviar Feedback</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de Feedback -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel">Feedback da Atividade</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea id="feedbackText" class="form-control" placeholder="Como você se sentiu?" rows="3"></textarea>
                        <div class="mt-3">
                            <label>Como você se sentiu?</label>
                            <div>
                                <i class="fa fa-smile-o" style="cursor:pointer;" data-feeling="happy"></i>
                                <i class="fa fa-meh-o" style="cursor:pointer;" data-feeling="neutral"></i>
                                <i class="fa fa-frown-o" style="cursor:pointer;" data-feeling="sad"></i>
                            </div>
                        </div>
                        <input type="hidden" id="selectedFeeling" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="submitFeedbackBtn" class="btn btn-primary">Enviar Feedback</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Scripts do Bootstrap e temporizador -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let timerInterval;
        let remainingTime = 25 * 60; // Tempo total em segundos (25 minutos)
        const timerElement = document.getElementById('timer');
        const activityInfoElement = document.getElementById('activityInfo');
        const startTimerBtn = document.getElementById('startTimerBtn');
        const pauseTimerBtn = document.getElementById('pauseTimerBtn');
        const resumeTimerBtn = document.getElementById('resumeTimerBtn');
        const resetTimerBtn = document.getElementById('resetTimerBtn');
        const stopTimerBtn = document.getElementById('stopTimerBtn');
        const finishTimerBtn = document.getElementById('finishTimerBtn');
        let activityId;

        // Atualiza o activityId e informações quando um botão de iniciar é clicado
        document.querySelectorAll('.iniciar-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                activityId = this.getAttribute('data-id');
                const activityName = this.getAttribute('data-nome');
                const activityDescription = this.getAttribute('data-descricao');
                activityInfoElement.innerHTML = `<strong>Atividade:</strong> ${activityName}<br><strong>Descrição:</strong> ${activityDescription}`;
                resetTimer(); // Reseta o temporizador para 25:00 ao abrir o modal
            });
        });

        startTimerBtn.addEventListener('click', function() {
            startTimer(activityId);
            startTimerBtn.style.display = 'none';
            pauseTimerBtn.style.display = 'inline-block';
            finishTimerBtn.style.display = 'inline-block';
        });

        pauseTimerBtn.addEventListener('click', function() {
            clearInterval(timerInterval);
            pauseTimerBtn.style.display = 'none';
            resumeTimerBtn.style.display = 'inline-block';
        });

        resumeTimerBtn.addEventListener('click', function() {
            startTimer(activityId);
            resumeTimerBtn.style.display = 'none';
            pauseTimerBtn.style.display = 'inline-block';
        });

        resetTimerBtn.addEventListener('click', function() {
            resetTimer();
        });

        stopTimerBtn.addEventListener('click', function() {
            clearInterval(timerInterval);
            resetTimer();
            var modal = bootstrap.Modal.getInstance(document.getElementById('pomodoroModal'));
            modal.hide(); // Fecha o modal
        });
        finishTimerBtn.addEventListener('click', function() {
            clearInterval(timerInterval);
            var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show(); // Abre o modal de feedback
        });

        function startTimer(activityId) {
            timerInterval = setInterval(function() {
                let minutes = Math.floor(remainingTime / 60);
                let seconds = remainingTime % 60;

                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    registerTime(activityId, 25 * 60); // Registra o tempo quando o temporizador termina
                    
                } else {
                    timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`; // Atualiza o temporizador na interface do usuário
                    remainingTime--; // Decrementa o tempo restante
                }
            }, 1000);
        }

        function resetTimer() {
            clearInterval(timerInterval);
            remainingTime = 25 * 60;
            timerElement.textContent = "25:00";
            activityInfoElement.innerHTML = ""; // Limpa as informações da atividade
            startTimerBtn.style.display = 'inline-block';
            pauseTimerBtn.style.display = 'none';
            resumeTimerBtn.style.display = 'none';
        }

        function registerTime(activityId, totalTime) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'registrar_tempo.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log('Tempo registrado com sucesso!');
                }
            };
            xhr.send(`activity_id=${activityId}&elapsed_time=${totalTime}`);
        }
    });
    </script>
</body>
</html>
