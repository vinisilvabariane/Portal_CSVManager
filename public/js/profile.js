// Função para formatar CNPJ
function formatCNPJ(cnpj) {
    if (!cnpj) return 'Não informado';
    // Remove caracteres não numéricos
    cnpj = cnpj.replace(/\D/g, '');
    // Aplica a formatação
    return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
}

// Função para formatar data
function formatDate(dateString) {
    if (!dateString) return '00/00/0000';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

// Função para carregar o perfil do usuário
function fetchUserProfile() {
    fetch('/PortalMultiGarantia/configs/Router.php?action=getUserProfile', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateProfileData(data.data);
            } else {
                showError(data.message || 'Falha ao carregar perfil');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Falha na comunicação com o servidor');
        });
}

// Função para atualizar os dados do perfil
function updateProfileData(userData) {
    // Atualiza os dados básicos do perfil
    if (userData.name) {
        document.querySelector('.profile-name').textContent = userData.name;
    }

    if (userData.role) {
        document.querySelector('.profile-role').textContent = userData.role;
    }

    // Atualiza os campos de detalhes
    if (userData.email) {
        document.getElementById('user-email').textContent = userData.email;
    }

    if (userData.phone) {
        document.getElementById('user-phone').textContent = userData.phone;
    }

    if (userData.cnpj) {
        document.getElementById('user-cnpj').textContent = formatCNPJ(userData.cnpj);
    }

    if (userData.created_at) {
        document.getElementById('user-created-at').textContent = formatDate(userData.created_at);
    }

    // Adiciona o campo Órgão se existir
    if (userData.orgao) {
        let orgaoElement = document.getElementById('user-orgao');
        if (!orgaoElement) {
            const orgaoHTML = `
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="detail-label">Órgão</div>
                            <div class="detail-value" id="user-orgao">${userData.orgao}</div>
                        </div>
                    </div>
                `;
            document.querySelector('.profile-body .row').insertAdjacentHTML('beforeend', orgaoHTML);
        } else {
            orgaoElement.textContent = userData.orgao;
        }
    }
}

// Função para mostrar erros
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Erro',
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
}

// Carrega o perfil quando a página é carregada
document.addEventListener('DOMContentLoaded', function () {
    fetchUserProfile();
});
document.addEventListener('DOMContentLoaded', function () {
    // Carrega os dados do perfil
    fetchUserProfile();

    // Configura as animações
    const animateElements = () => {
        document.querySelectorAll('.fade-in').forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('visible');
            }, index * 150);
        });
    };

    // Observer para animação durante o scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    // Inicia as animações e o observer
    animateElements();
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
});