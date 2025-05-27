<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/authenticator.php"); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="/PortalMultiGarantia/public/img/m.svg">
    <title>Multi Garantia - Tabela de Garantias</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/PortalMultiGarantia/public/css/fonts.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/navbar.php"); ?>

    <div class="container-fluid py-4">
        <div class="container table-container">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2><i class="bi bi-file-earmark-text me-2"></i> Registros de Garantias</h2>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-plus-circle me-1"></i> Novo Registro
                        </button>
                    </div>
                </div>
            </div>

            <table id="tabela-garantia" class="table table-hover table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>SKU</th>
                        <th>Tempo Garantia (Meses)</th>
                        <th>Bateria</th>
                        <th>Data Final Garantia</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Upload de CSV -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel"><i class="bi bi-upload me-2"></i>Upload de CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="file-upload-wrapper">
                            <input type="file" id="csvFile" class="file-upload-input" accept=".csv" required>
                            <label for="csvFile" class="file-upload-label">
                                <i class="bi bi-cloud-arrow-up me-2"></i>Selecione um arquivo CSV
                            </label>
                            <div id="fileName" class="file-name">Nenhum arquivo selecionado</div>
                        </div>
                        <div class="progress" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="uploadBtn">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/includes/footer.php"); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/PortalMultiGarantia/public/js/tabela-garantia.js"></script>
</body>

</html>