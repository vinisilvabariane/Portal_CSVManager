<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/authenticator.php"); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="/PortalMultiGarantia/public/img/m.svg">
    <title>Multi Garantia - Consulta por Nota Fiscal</title>
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

    <section class="consult-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card consult-card">
                        <div class="consult-header">
                            <h2><i class="bi bi-receipt me-2"></i> Consulta de SKUs por Nota Fiscal</h2>
                            <p class="mb-0">Consulte todos os produtos associados a uma nota fiscal</p>
                        </div>
                        <div class="card-body consult-body">
                            <form id="consultaNotaForm">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="numeroNota" class="form-label">Número da Nota Fiscal</label>
                                        <input type="text" class="form-control" id="numeroNota"
                                            placeholder="Digite o número da nota fiscal" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-consult">
                                        <i class="bi bi-search me-2"></i> Consultar
                                    </button>
                                </div>
                            </form>
                            <div id="resultContainer" class="result-section">
                                <h4 class="result-title"><i class="bi bi-list-check me-2"></i> Produtos na Nota Fiscal</h4>
                                <div id="produtosInfo">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>SKU</th>
                                                    <th>Descrição</th>
                                                    <th>Serial</th>
                                                    <th>Data Compra</th>
                                                    <th>Garantia</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabelaProdutos"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="noResults" class="no-results">
                                <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: #6c757d;"></i>
                                <h5 class="mt-3">Nenhum produto encontrado</h5>
                                <p>Verifique o número da nota fiscal e tente novamente.</p>
                            </div>
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
    <script src="/PortalMultiGarantia/public/js/consult-nota.js"></script>
</body>

</html>