<?php
session_start();
session_destroy()
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="/PortalMultiGarantia/public/img/m.svg">
    <title>Portal Multi Garantia - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5/dist/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="/PortalMultiGarantia/public/css/fonts.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container my-auto">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card shadow login-card">
                    <img src="/PortalMultiGarantia/public/img/multi.svg" alt="Logo" class="multi-logo">
                    <div class="card-body p-4 pt-5">
                        <h1 class="text-center mb-3 display-5 fw-bold text-primary">Bem-vindo!</h1>
                        <p class="text-center mb-4 text-muted">Portal Multi Garantia</p>
                        <form id="login">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuário</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Insira seu usuário" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-key"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Insira sua senha" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-type">
                                        <i class="bx bx-low-vision" id="toggle-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Recuperação de Senha -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperação de Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Envie um e-mail para <strong>vinicius.bariane@grupomulti.com.br</strong> para recuperar sua
                        senha.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer text-center py-3 mt-auto">
        <small>&copy; <span id="current-year">2024</span> Portal Multi Garantia - Todos os direitos reservados.</small>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const yearSpan = document.getElementById('current-year');
            if (yearSpan) {
                yearSpan.textContent = new Date().getFullYear();
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="/PortalMultiGarantia/public/js/login.js"></script>
</body>

</html>