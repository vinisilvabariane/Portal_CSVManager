document.addEventListener('DOMContentLoaded', function () {
    const table = $('#tabela-garantia').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        responsive: true,
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
                        item.TempoGarantia || '',
                        item.Bateria || '',
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

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }
    function getStatusBadge(status) {
        const statusMap = {
            'Ativa': 'status-active',
            'Expirada': 'status-expired',
            'Próximo do fim': 'status-warning'
        };
        const badgeClass = statusMap[status] || 'status-active';
        return `<span class="status-badge ${badgeClass}">${status}</span>`;
    }
    document.getElementById('refreshBtn')?.addEventListener('click', function () {
        fetchData();
    });
});