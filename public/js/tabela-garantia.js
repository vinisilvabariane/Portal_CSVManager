document.addEventListener('DOMContentLoaded', function () {
    // Inicialização da DataTable
    const table = $('#tabela-garantia').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        responsive: true,
        columns: [
            { title: "Cliente" },
            { title: "SKU" },
            { title: "Tempo Garantia (meses)" },
            { title: "Bateria" },
            { title: "Data Final da Garantia a Partir da Data Atual" },
            {
                title: "Ações",
                orderable: false,
                searchable: false,
                className: "text-center"
            }
        ],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: 4 },
            { responsivePriority: 4, targets: 5 }
        ],
        order: [[1, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        initComplete: function () {
            fetchData();
        }
    });

    // Função para buscar dados
    function fetchData() {
        fetch('/PortalMultiGarantia/configs/Router.php?action=getGarantia')
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
                        item.Cliente || '',
                        item.SKU || '',
                        item.TempoGarantiaMeses || '',
                        item.Bateria || 0,
                        formatDate(item.DataFinalGarantia),
                        createActionButtons(item.SKU)
                    ]);
                });
                table.draw();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Função para criar botões de ação
    function createActionButtons(sku) {
        return `
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-danger delete-btn" data-sku="${sku}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    }

    // Função para formatar data
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }

    // Delegar evento de clique para os botões de delete
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
            const button = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
            const sku = button.getAttribute('data-sku');
            deleteRow(sku);
        }
    });

    // Função para deletar uma linha
    function deleteRow(sku) {
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
                fetch('/PortalMultiGarantia/configs/Router.php?action=deleteGarantia', {
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
    }

    // Gerenciamento do modal de upload - Código atualizado para tratar o erro
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

            fetch("/PortalMultiGarantia/configs/Router.php?action=uploadGarantiasCSV", {
                method: "POST",
                body: formData,
            })
                .then(async response => {
                    const text = await response.text();

                    // Tratamento especial para extrair o JSON mesmo com erros antes
                    const jsonMatch = text.match(/\{.*\}/s);
                    if (jsonMatch) {
                        try {
                            const data = JSON.parse(jsonMatch[0]);
                            if (data.success) {
                                return data;
                            }
                            throw new Error(data.message || 'Erro no upload');
                        } catch (e) {
                            throw new Error(text);
                        }
                    }
                    throw new Error(text || 'Resposta inválida do servidor');
                })
                .then(data => {
                    let htmlMsg = data.message;
                    if (data.erros > 0 && Array.isArray(data.detalhes) && data.detalhes.length > 0) {
                        htmlMsg += `<br><br><b>Erros detalhados:</b><ul style="text-align:left;">`;
                        data.detalhes.forEach((detalhe, idx) => {
                            htmlMsg += `<li>Linha ${idx + 2}: ${detalhe.linha.join('; ')}<br><small>${JSON.stringify(detalhe.erro)}</small></li>`;
                        });
                        htmlMsg += '</ul>';
                    }
                    Swal.fire({
                        icon: data.erros > 0 ? 'warning' : 'success',
                        title: data.erros > 0 ? 'Importação com Erros' : 'Sucesso',
                        html: htmlMsg,
                        timer: 6000,
                        showConfirmButton: true
                    });
                    $('#uploadModal').modal('hide');
                    fetchData();
                })
                .catch(error => {
                    console.error("Erro no upload:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro no upload',
                        text: error.message.includes('{') ?
                            'O arquivo foi processado, mas ocorreu um erro na resposta.' :
                            error.message || 'Falha no upload do arquivo.',
                    });
                });
        });

        $('#uploadModal').on('hidden.bs.modal', function () {
            document.getElementById('uploadForm').reset();
            fileNameDisplay.textContent = 'Nenhum arquivo selecionado';
        });
    }
});