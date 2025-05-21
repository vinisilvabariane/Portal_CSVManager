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
                        '<button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>'
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

    // Refresh button functionality
    document.getElementById('refreshBtn')?.addEventListener('click', function () {
        fetchData();
    });
});