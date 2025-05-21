<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/authenticator.php"); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="/PortalMultiGarantia/public/img/m.svg">
    <title>Multi Garantia - Criar Usuário</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/PortalMultiGarantia/public/css/fonts.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/navbar.php"); ?>

    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="profile-card fade-in">
                        <div class="profile-header">
                            <div class="profile-icon">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h1 class="profile-name">Criar Novo Usuário</h1>
                            <p class="profile-role">Preencha as informações abaixo</p>
                        </div>

                        <div class="profile-body">
                            <form id="userForm">
                                <div class="detail-group fade-in delay-1">
                                    <h5>Informações Pessoais</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="name">Usuário</label>
                                                <input type="text" id="username" name="username" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="orgao">Orgão</label>
                                                <input type="text" id="orgao" name="orgao" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="email">Email</label>
                                                <input type="email" id="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="phone">Telefone</label>
                                                <input type="text" id="phone" name="phone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="cnpj">CNPJ</label>
                                                <input type="text" id="cnpj" name="cnpj" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="password">Senha</label>
                                                <input type="password" id="password" name="password" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label" for="role">Perfil</label>
                                                <select id="role" name="role" class="form-select">
                                                    <option value="membro">Membro</option>
                                                    <option value="admin">Administrador</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4 fade-in delay-3">
                                    <button type="submit" class="btn btn-edit">
                                        <i class="bi bi-person-plus me-2"></i> Cadastrar Usuário
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-auto py-3">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/footer.php"); ?>
    </footer>

    <!-- Adicionando jQuery e jQuery Mask -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Adicionando MD5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
    <script src="/PortalMultiGarantia/public/js/create-user.js"></script>
</body>

</html>