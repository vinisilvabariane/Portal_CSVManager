document.addEventListener('DOMContentLoaded', function () {
    const consultCard = document.querySelector('.consult-card');
    setTimeout(() => {
        consultCard.classList.add('visible');
    }, 100);

    document.getElementById('consultaForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const serialNumber = document.getElementById('numeroSerial').value.trim();
        if (!serialNumber) {
            Swal.fire('Erro', 'Por favor, informe o número serial', 'error');
            return;
        }
        Swal.fire({
            title: 'Consultando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        fetch('/PortalMultiGarantia/configs/Router.php?action=consultGarantia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ serialNumber: serialNumber })
        })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    const warrantyData = data.data;
                    document.getElementById('noResults').classList.remove('visible');
                    document.getElementById('noResults').style.display = 'none';
                    const resultContainer = document.getElementById('resultContainer');
                    resultContainer.style.display = 'block';
                    setTimeout(() => {
                        resultContainer.classList.add('visible');
                    }, 10);
                    const garantiaInfo = document.getElementById('garantiaInfo');
                    garantiaInfo.innerHTML = `
                    <div class="result-item">
                        <span class="result-label">Status:</span>
                        <span class="result-value badge bg-${warrantyData.SituacaoGarantia === 'Dentro da Garantia' ? 'success' : 'danger'}">
                            ${warrantyData.SituacaoGarantia}
                        </span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Número Serial:</span>
                        <span class="result-value">${warrantyData.Serial}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">IMEI:</span>
                        <span class="result-value">${warrantyData.Imei || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Cliente:</span>
                        <span class="result-value">${warrantyData.Cliente}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Nota Fiscal:</span>
                        <span class="result-value">${warrantyData.NotaFiscal}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Data de Faturamento:</span>
                        <span class="result-value">${warrantyData.DataFaturamento}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Data Final da Garantia:</span>
                        <span class="result-value">${warrantyData.DataFinalGarantia}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Dias Restantes:</span>
                        <span class="result-value">${warrantyData.DiasRestantes > 0 ? warrantyData.DiasRestantes : 'Expirado'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">SKU/Modelo:</span>
                        <span class="result-value">${warrantyData.SKU}</span>
                    </div>
                `;
                    setTimeout(() => {
                        const resultItems = document.querySelectorAll('.result-item');
                        resultItems.forEach(item => {
                            item.classList.add('visible');
                        });
                    }, 100);
                } else {
                    document.getElementById('resultContainer').style.display = 'none';
                    const noResults = document.getElementById('noResults');
                    noResults.style.display = 'block';
                    setTimeout(() => {
                        noResults.classList.add('visible');
                    }, 10);
                }
            })
            .catch(error => {
                Swal.fire('Erro', 'Ocorreu um erro na consulta. Por favor, tente novamente.', 'error');
                console.error('Error:', error);
            });
    });
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
});

document.querySelectorAll('.consult-card, .result-section, .no-results, .result-item').forEach(el => {
    observer.observe(el);
});