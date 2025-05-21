document.addEventListener('DOMContentLoaded', function () {
    const consultCard = document.querySelector('.consult-card');
    setTimeout(() => {
        consultCard.classList.add('visible');
    }, 100);

    document.getElementById('consultaNotaForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const numeroNota = document.getElementById('numeroNota').value.trim();
        if (!numeroNota) {
            Swal.fire('Erro', 'Por favor, informe o número da nota fiscal', 'error');
            return;
        }
        Swal.fire({
            title: 'Consultando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        fetch('/PortalMultiGarantia/configs/Router.php?action=getGarantiaByNota', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ numeroNota: numeroNota })
        })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success && data.data && data.data.length > 0) {
                    const produtos = data.data;
                    document.getElementById('noResults').classList.remove('visible');
                    document.getElementById('noResults').style.display = 'none';
                    const resultContainer = document.getElementById('resultContainer');
                    resultContainer.style.display = 'block';
                    setTimeout(() => {
                        resultContainer.classList.add('visible');
                    }, 10);
                    const tabelaProdutos = document.getElementById('tabelaProdutos');
                    tabelaProdutos.innerHTML = '';
                    const produtosAgrupados = {};
                    produtos.forEach(produto => {
                        if (!produtosAgrupados[produto.SKU]) {
                            produtosAgrupados[produto.SKU] = {
                                SKU: produto.SKU,
                                Descricao: produto.Descricao || 'N/A',
                                DataFaturamento: produto.DataFaturamento,
                                SituacaoGarantia: produto.SituacaoGarantia,
                                Seriais: []
                            };
                        }
                        if (produto.Serial) {
                            produtosAgrupados[produto.SKU].Seriais.push(produto.Serial);
                        }
                    });
                    Object.values(produtosAgrupados).forEach((produto, index) => {
                        const row = document.createElement('tr');
                        row.className = 'result-item';
                        row.innerHTML = `
                            <td>${produto.SKU}</td>
                            <td>${produto.Descricao}</td>
                            <td>${produto.Seriais.join(', ')}</td>
                            <td>${produto.DataFaturamento ? formatDate(produto.DataFaturamento) : 'N/A'}</td>
                            <td>
                                <span class="badge bg-${produto.SituacaoGarantia === 'Dentro da Garantia' ? 'success' : 'danger'}">
                                    ${produto.SituacaoGarantia}
                                </span>
                            </td>
                        `;
                        tabelaProdutos.appendChild(row);
                        setTimeout(() => {
                            row.classList.add('visible');
                        }, 100 * (index + 1));
                    });
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

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

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