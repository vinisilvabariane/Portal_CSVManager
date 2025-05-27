document.addEventListener('DOMContentLoaded', function () {
    const table = $('#tabela-serial').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 2 },
            { responsivePriority: 3, targets: 6 },
            { responsivePriority: 4, targets: 7 },
            { responsivePriority: 5, targets: -1 }
        ],
        order: [[1, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        initComplete: function () {
            fetchData();
        }
    });
    function fetchData() {
        fetch('/PortalMultiGarantia/configs/Router.php?action=getSerial')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                table.clear();
                data.forEach(item => {
                    table.row.add([
                        item.NotaFiscal || '',
                        formatDate(item.DataFaturamento),
                        item.Cliente || '',
                        item.Serial || '',
                        item.Imei || '',
                        item.SKU || '',
                        formatDate(item.DataFinalGarantia),
                        getStatusBadge(item.Status || ''),
                        `
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-sku="${item.SKU}">
                            <i class="bi bi-trash"></i>
                        </button>
                        `
                    ]);
                });
                table.draw();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Format date from YYYY-MM-DD to DD/MM/YYYY
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }

    // Get appropriate status badge
    function getStatusBadge(status) {
        const statusMap = {
            'Ativa': 'status-active',
            'Expirada': 'status-expired',
            'Próximo do fim': 'status-warning'
        };
        const badgeClass = statusMap[status] || 'status-active';
        return `<span class="status-badge ${badgeClass}">${status}</span>`;
    }

    // Abrir modal de upload ao clicar no botão
    document.getElementById('openUploadModal')?.addEventListener('click', function () {
        const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
        modal.show();
    });

    // Gerenciamento do modal de upload
    const csvFileInput = document.getElementById('csvFile');
    const fileNameDisplay = document.getElementById('fileName');
    if (csvFileInput && fileNameDisplay) {
        csvFileInput.addEventListener('change', function () {
            fileNameDisplay.textContent = this.files.length > 0
                ? this.files[0].name
                : 'Nenhum arquivo selecionado';
        });
        document.getElementById("uploadBtn").addEventListener("click", function () {
            const fileInput = document.getElementById("csvFile");
            const file = fileInput.files[0];
            if (!file) {
                Swal.fire("Erro", "Selecione um arquivo CSV.", "error");
                return;
            }
            const formData = new FormData();
            formData.append("csvFile", file);
            Swal.fire({
                title: 'Enviando arquivo',
                html: 'Por favor, aguarde...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            fetch("/PortalMultiGarantia/configs/Router.php?action=uploadSerialCSV", {
                method: "POST",
                body: formData,
            })
                .then(async response => {
                    const text = await response.text();
                    // Tenta extrair o JSON mesmo se vier lixo antes/depois
                    const jsonMatch = text.match(/{.*}/s);
                    if (jsonMatch) {
                        try {
                            return JSON.parse(jsonMatch[0]);
                        } catch (e) {
                            throw new Error(text || 'Erro desconhecido');
                        }
                    } else {
                        throw new Error(text || 'Erro desconhecido');
                    }
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                        modal.hide();
                        fetchData();
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Erro no upload:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: error.message || 'Falha no upload do arquivo.',
                    });
                });
        });
        $('#uploadModal').on('hidden.bs.modal', function () {
            document.getElementById('uploadForm').reset();
            fileNameDisplay.textContent = 'Nenhum arquivo selecionado';
        });
    }

    // Função para deletar uma linha
    $('#tabela-serial tbody').on('click', '.delete-btn', function () {
        const sku = $(this).data('sku');
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, deletar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/PortalMultiGarantia/configs/Router.php?action=deleteSerial', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `sku=${encodeURIComponent(sku)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deletado!',
                                'O registro foi deletado.',
                                'success'
                            );
                            fetchData();
                        } else {
                            throw new Error(data.message || 'Erro ao deletar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Erro!',
                            error.message || 'Ocorreu um erro ao deletar o registro.',
                            'error'
                        );
                    });
            }
        });
    });

    // Refresh button functionality
    document.getElementById('refreshBtn')?.addEventListener('click', function () {
        fetchData();
    });
});