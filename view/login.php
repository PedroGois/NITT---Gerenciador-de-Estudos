<?php
    include("../model/conexao.php");
    // Verifica se há uma mensagem de erro na URL
    if (isset($_GET['erro'])) {
        // Exibe a mensagem de erro
        $mensagemErro = urldecode($_GET['erro']);
        echo '<div class="alert alert-danger" role="alert">';
        echo $mensagemErro;
        echo '</div>';
    } elseif (isset($_GET['success0'])) {
        // Exibe a mensagem de erro
        $mensagem = urldecode($_GET['success']);
        echo '<div class="alert alert-info" role="alert">';
        echo $mensagem;
        echo '</div>';
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Realize o login</title>
        <link rel="shortcut icon" href="../imgs/nittlogo.png" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                <div class="container">
                    <div class="row justify-content-center ">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5 ">
                                    <div class="card-header col text-center">
                                        <img class="my-3" src="../imgs/nittlogo.png" width="80px" />
                                        <h3 class="text-center font-weight-light my-4 text-primary">Efetue login no NITT</h3> 
                                    </div>
                                    <div class="card-body col justify-content-center align-items-center px-5">
                                        <form method="POST" action="../controller/validalogin.php">
                                            <div class="form-floating mb-3 position-relative">
                                                <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" />
                                                <label for="inputEmail">Email</label>
                                            </div>
                                            <div class="form-floating mb-3 position-relative">
                                            <input class="form-control" id="inputPassword" name="senha" type="password" placeholder="Password" required>
                                            <label for="inputPassword">Senha</label>
                                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute end-0 top-50 translate-middle-y" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                            </button>     
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label text-link" for="inputRememberPassword">Lembrar senha</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small " href="senha.html">Esqueceu sua senha?</a>
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small "><a href="registro.php">Precisa de uma conta? Inscrever-se!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">NITT&copy;2024</div>
                            <div>
                                <a href="#">Politica de Privacidade</a>
                                &middot;
                                <a href="#">
                                    Termos &amp; Condições</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordField = document.getElementById('inputPassword');
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
            });
        </script>
    </body>
</html>
