<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/authenticator.php"); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="/PortalMultiGarantia/public/img/m.svg">
    <title>Multi Garantia - FAQ</title>
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

    <!-- Cabeçalho do FAQ -->
    <header class="faq-header fade-in">
        <div class="container text-center position-relative z-2">
            <div class="hero-shape-1"></div>
            <div class="hero-shape-2"></div>

            <div class="mb-4">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 d-inline-block fade-in">
                    <i class="bi bi-question-circle-fill me-2"></i>Central de Ajuda
                </span>
            </div>

            <h1 class="display-3 fw-bold mb-4 text-white hero-title">
                <span class="d-block">Perguntas</span>
                <span class="span-title">Frequentes</span>
            </h1>

            <p class="lead text-white-80 mb-5 mx-auto fade-in delay-1" style="max-width: 700px;">
                Encontre respostas rápidas para todas as suas dúvidas sobre o Portal MultiGarantia.
            </p>

            <div class="search-box fade-in delay-2">
                <div class="input-group input-group-lg mb-3 mx-auto" style="max-width: 600px;">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" id="faq-search" class="form-control border-0 py-3" placeholder="Buscar no FAQ..."
                        autocomplete="off">
                </div>
            </div>

            <div class="scroll-indicator fade-in delay-3">
                <a href="#faq-content" class="text-white d-inline-block">
                    <i class="bi bi-chevron-down fs-4 animate-bounce"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container my-5" id="faq-content">
        <div id="no-results" class="no-results">
            <i class="bi bi-search fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">Nenhum resultado encontrado</h4>
            <p class="text-muted">Tente usar termos diferentes ou verifique a ortografia</p>
        </div>

        <div class="row">
            <div class="col-lg-8" id="faq-accordion">
                <!-- Categoria: Acesso ao Sistema -->
                <div class="faq-category fade-in delay-1" data-category="acesso">
                    <h3><i class="bi bi-door-open me-2"></i> Acesso ao Sistema</h3>
                    <div class="accordion" id="accordionAccess">
                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-2"
                            data-search="acesso portal multigarantia login credenciais primeiro acesso senha">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accessQuestion1">
                                    Como faço para acessar o Portal MultiGarantia?
                                </button>
                            </h2>
                            <div id="accessQuestion1" class="accordion-collapse collapse"
                                data-bs-parent="#accordionAccess">
                                <div class="accordion-body">
                                    Para acessar o Portal MultiGarantia, você precisa utilizar as credenciais fornecidas
                                    pela sua empresa.
                                    Basta acessar a página de login e inserir seu e-mail corporativo e senha. Caso seja
                                    seu primeiro acesso,
                                    você receberá um e-mail com instruções para criar sua senha.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-2"
                            data-search="esqueci senha redefinir recuperar login e-mail spam suporte técnico">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accessQuestion2">
                                    Esqueci minha senha. O que fazer?
                                </button>
                            </h2>
                            <div id="accessQuestion2" class="accordion-collapse collapse"
                                data-bs-parent="#accordionAccess">
                                <div class="accordion-body">
                                    Na página de login, clique em "Esqueci minha senha" e insira o e-mail cadastrado.
                                    Você receberá um link
                                    para redefinir sua senha. Caso não receba o e-mail em alguns minutos, verifique sua
                                    pasta de spam ou
                                    entre em contato com o suporte técnico.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categoria: Gestão de Garantias -->
                <div class="faq-category fade-in delay-2" data-category="gestao">
                    <h3><i class="bi bi-shield-check me-2"></i> Gestão de Garantias</h3>
                    <div class="accordion" id="accordionWarranty">
                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-3"
                            data-search="consultar status garantia minhas garantias validade documentos filtrar">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#warrantyQuestion1">
                                    Como consulto o status de uma garantia?
                                </button>
                            </h2>
                            <div id="warrantyQuestion1" class="accordion-collapse collapse"
                                data-bs-parent="#accordionWarranty">
                                <div class="accordion-body">
                                    Após fazer login no sistema, acesse a seção "Minhas Garantias". Lá você encontrará
                                    uma lista de todas
                                    as garantias associadas à sua conta, com informações sobre status, validade e
                                    documentos relacionados.
                                    Você pode filtrar por status ou período para encontrar a garantia desejada.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-3"
                            data-search="solicitar ativar nova garantia formulário produto serviço nota fiscal contrato documentos anexar">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#warrantyQuestion2">
                                    Como solicito a ativação de uma nova garantia?
                                </button>
                            </h2>
                            <div id="warrantyQuestion2" class="accordion-collapse collapse"
                                data-bs-parent="#accordionWarranty">
                                <div class="accordion-body">
                                    Para ativar uma nova garantia, acesse a seção "Solicitar Garantia" no menu
                                    principal. Preencha o
                                    formulário com as informações do produto/serviço e anexe os documentos necessários
                                    (nota fiscal,
                                    contrato, etc.). Após a análise, você receberá uma confirmação por e-mail quando a
                                    garantia for
                                    ativada.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-3"
                            data-search="documentos necessários ativar garantia nota fiscal contrato termos fabricante fornecedor obrigatórios">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#warrantyQuestion3">
                                    Quais documentos são necessários para ativar uma garantia?
                                </button>
                            </h2>
                            <div id="warrantyQuestion3" class="accordion-collapse collapse"
                                data-bs-parent="#accordionWarranty">
                                <div class="accordion-body">
                                    Os documentos necessários variam conforme o tipo de garantia, mas geralmente
                                    incluem:
                                    <ul>
                                        <li>Nota fiscal ou comprovante de compra</li>
                                        <li>Contrato de serviço (quando aplicável)</li>
                                        <li>Documentos técnicos ou especificações</li>
                                        <li>Termos de garantia do fabricante/fornecedor</li>
                                    </ul>
                                    O sistema indicará quais documentos são obrigatórios durante o processo de
                                    solicitação.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categoria: Problemas Técnicos -->
                <div class="faq-category fade-in delay-3" data-category="tecnico">
                    <h3><i class="bi bi-tools me-2"></i> Problemas Técnicos</h3>
                    <div class="accordion" id="accordionTech">
                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-4"
                            data-search="sistema lento não carrega desempenho navegador atualizar cache cookies internet conexão chrome firefox edge">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#techQuestion1">
                                    O sistema está lento ou não carrega corretamente. O que fazer?
                                </button>
                            </h2>
                            <div id="techQuestion1" class="accordion-collapse collapse" data-bs-parent="#accordionTech">
                                <div class="accordion-body">
                                    Se estiver enfrentando problemas de desempenho, recomendamos:
                                    <ol>
                                        <li>Atualizar seu navegador para a versão mais recente</li>
                                        <li>Limpar o cache e os cookies do navegador</li>
                                        <li>Verificar sua conexão com a internet</li>
                                        <li>Tentar acessar por outro navegador (Chrome, Firefox, Edge)</li>
                                    </ol>
                                    Se o problema persistir, entre em contato com nosso suporte técnico informando qual
                                    navegador e sistema
                                    operacional você está utilizando.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm fade-in delay-4"
                            data-search="visualizar baixar documentos pdf planilhas adobe reader pop-ups firewall antivírus download">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#techQuestion2">
                                    Não consigo visualizar ou baixar documentos. Como resolver?
                                </button>
                            </h2>
                            <div id="techQuestion2" class="accordion-collapse collapse" data-bs-parent="#accordionTech">
                                <div class="accordion-body">
                                    Alguns documentos podem exigir softwares específicos para visualização (como PDFs ou
                                    planilhas). Verifique se:
                                    <ul>
                                        <li>Você tem um leitor de PDF instalado (como Adobe Reader)</li>
                                        <li>Seu navegador não está bloqueando pop-ups (alguns documentos abrem em novas
                                            janelas)</li>
                                        <li>Seu firewall ou antivírus não está bloqueando o download</li>
                                    </ul>
                                    Caso continue com problemas, tente acessar de outro dispositivo ou navegador.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar de Contato -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 fade-in delay-2">
                    <div class="card-body contact-card">
                        <h4 class="card-title mb-3"><i class="bi bi-headset me-2"></i> Precisa de mais ajuda?</h4>
                        <p class="card-text">Se não encontrou resposta para sua dúvida, nossa equipe de suporte está
                            pronta para ajudar.</p>

                        <div class="mb-3">
                            <h5><i class="bi bi-envelope me-2"></i> E-mail</h5>
                            <p>customer.service@grupomulti.com.br</p>
                        </div>

                        <div class="mb-3">
                            <h5><i class="bi bi-telephone me-2"></i> Telefone</h5>
                            <p>+55 (35) 8416-0013 (Seg-Sex, 9h-17h)</p>
                        </div>

                 
                    </div>
                </div>

                <div class="card shadow-sm fade-in delay-3">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="bi bi-info-circle me-2"></i> Dicas Rápidas</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center fade-in delay-4">
                                <i class="bi bi-search me-3 text-primary"></i>
                                <span>Use palavras-chave na busca para encontrar respostas mais rápido</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center fade-in delay-4">
                                <i class="bi bi-bookmark me-3 text-primary"></i>
                                <span>Marque páginas importantes nos seus favoritos para acesso rápido</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center fade-in delay-4">
                                <i class="bi bi-download me-3 text-primary"></i>
                                <span>Mantenha cópias digitais dos seus documentos importantes</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="mt-auto py-3">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/footer.php"); ?>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/PortalMultiGarantia/public/js/doubts.js"></script>
</body>

</html>