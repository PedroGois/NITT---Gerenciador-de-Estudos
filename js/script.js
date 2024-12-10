document.addEventListener('DOMContentLoaded', function() {
    let timerInterval;
    const timerElement = document.getElementById('timer');
    const startTimerBtn = document.getElementById('startTimerBtn');
    const stopTimerBtn = document.getElementById('stopTimerBtn');

    startTimerBtn.addEventListener('click', function() {
        startTimer();
    });

    stopTimerBtn.addEventListener('click', function() {
        clearInterval(timerInterval);
    });

    function startTimer() {
        let minutes = 25;
        let seconds = 0;

        timerInterval = setInterval(function() {
            if (minutes === 0 && seconds === 0) {
                clearInterval(timerInterval);
                // Aqui você pode registrar os dados no banco de dados
            } else {
                seconds--;
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                }
                timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
        }, 1000);
    }
});


 function updateTextareaColor() {
        const statusSelect = document.getElementById('status');
        const descricaoTextarea = document.getElementById('descricao');

        if (statusSelect.value === 'Em andamento') {
            descricaoTextarea.style.backgroundColor = '#ffeb3b'; // Amarelo
            descricaoTextarea.style.color = '#000'; // Texto preto
        } else if (statusSelect.value === 'Sem pendências') {
            descricaoTextarea.style.backgroundColor = '#4caf50'; // Verde
            descricaoTextarea.style.color = '#fff'; // Texto branco
        } else {
            descricaoTextarea.style.backgroundColor = ''; // Resetar
            descricaoTextarea.style.color = ''; // Resetar
        }
    }

