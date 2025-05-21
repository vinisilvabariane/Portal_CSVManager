<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/authenticator.php"); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="/PortalMultiGarantia/public/img/m.svg">
    <title>Multi Garantia - Home</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PortalMultiGarantia/public/css/fonts.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/navbar.php"); ?>

    <!-- Particles.js Container -->
    <div id="particles-js"></div>

    <section class="hero-section vh-100 d-flex align-items-center">
        <div class="container h-100">
            <div class="row align-items-center h-100">
                <div class="col-lg-6 order-lg-2 text-center">
                    <div class="hero-animation fade-in delay-3">
                    </div>
                </div>

                <!-- Coluna do conteúdo -->
                <div class="col-lg-6 order-lg-1">
                    <div class="hero-content">
                        <h1 class="hero-title fade-in slide-up">
                            <span>Gestão de Garantias</span>
                            <span class="text-primary">Simplificada</span>
                        </h1>
                        <p class="hero-subtitle fade-in slide-up delay-1">
                            O Portal MultiGarantia oferece uma solução completa para gerenciar todas as suas garantias
                            em um único lugar. Acompanhe, solicite e gerencie com facilidade e segurança.
                        </p>
                        <div class="hero-buttons d-flex gap-3 fade-in slide-up delay-2">
                            <a href="/PortalMultiGarantia/views/consult/" class="btn btn-hero-primary">
                                <i class="bi bi-wallet2 me-2"></i> Consultar Garantia
                            </a>
                            <a href="#features" class="btn btn-hero-outline">
                                <i class="bi bi-play-circle me-2"></i> Como Funciona
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divisor de Nuvens -->
        <div class="cloud-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                preserveAspectRatio="none">
                <path
                    d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                    opacity=".25" class="shape-fill"></path>
                <path
                    d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                    opacity=".5" class="shape-fill"></path>
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                    class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Seção de Recursos -->
    <section id="features" class="py-5 bg-white">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="fw-bold fade-in">Como o Portal MultiGarantia pode te ajudar</h2>
                    <p class="text-muted fade-in delay-1">Conheça nossos principais recursos</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm fade-in">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-speedometer2 text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <h5 class="card-title">Acompanhamento em Tempo Real</h5>
                            <p class="card-text text-muted">Monitore o status de todas as suas garantias em um único
                                painel intuitivo.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm fade-in delay-1">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-file-earmark-text text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <h5 class="card-title">Documentação Digital</h5>
                            <p class="card-text text-muted">Acesse todos os documentos relacionados às suas garantias
                                quando precisar.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm fade-in delay-2">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-headset text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <h5 class="card-title">Suporte Especializado</h5>
                            <p class="card-text text-muted">Nossa equipe está pronta para ajudar com qualquer dúvida
                                sobre suas garantias.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="mt-auto py-3">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/footer.php"); ?>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="/PortalMultiGarantia/public/js/home.js"></script>
</body>

</html>