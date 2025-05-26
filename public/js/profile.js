/**
 * Formata um CNPJ para o padrão XX.XXX.XXX/XXXX-XX
 * @param {string} cnpj - CNPJ a ser formatado
 * @returns {string} CNPJ formatado ou 'Não informado' se vazio
 */
function formatCNPJ(cnpj) {
    if (!cnpj || cnpj === 'Não informado') return 'Não informado';
    cnpj = cnpj.replace(/\D/g, '');
    if (cnpj.length !== 14) return cnpj;
    return cnpj.replace(
        /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,
        '$1.$2.$3/$4-$5'
    );
}

/**
 * Formata uma data string para o padrão brasileiro (DD/MM/AAAA)
 * @param {string} dateString - Data em formato string
 * @returns {string} Data formatada ou 'Não informado' se inválida
 */
function formatDate(dateString) {
    if (!dateString) return 'Não informado';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return 'Não informado';
    return date.toLocaleDateString('pt-BR');
}

/**
 * Formata um telefone para o padrão (XX) XXXX-XXXX ou (XX) XXXXX-XXXX
 * @param {string} phone - Telefone a ser formatado
 * @returns {string} Telefone formatado ou 'Não informado' se vazio
 */
function formatPhone(phone) {
    if (!phone || phone === 'Não informado') return 'Não informado';
    phone = phone.replace(/\D/g, '');
    if (phone.length === 10) {
        return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else if (phone.length === 11) {
        return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }
    return phone;
}

/**
 * Formata um órgão para exibição padronizada
 * @param {string} orgao - Nome do órgão
 * @returns {string} Órgão formatado ou 'Não informado' se vazio
 */
function formatOrgao(orgao) {
    if (!orgao) return 'Não informado';
    return orgao.toUpperCase();
}

/**
 * Busca os dados do perfil do usuário no servidor
 * e atualiza a interface com os dados recebidos
 */
function fetchUserProfile() {
    fetch('/PortalMultiGarantia/configs/Router.php?action=getUserProfile', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'include'
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'Erro na resposta do servidor'); });
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (data.success) {
                updateProfileData(data.data);
            } else {
                showError(data.message || 'Falha ao carregar perfil');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError(error.message || 'Falha na comunicação com o servidor');
        });
}

/**
 * Atualiza os elementos da interface com os dados do usuário formatados
 * @param {object} userData - Dados do usuário recebidos do servidor
 */
function updateProfileData(userData) {
    if (userData.name) {
        document.querySelector('.profile-name').textContent = userData.name;
    }
    if (userData.role) {
        document.querySelector('.profile-role').textContent = userData.role;
    }
    if (userData.email) {
        document.getElementById('user-email').textContent = userData.email || 'Não informado';
    }
    if (userData.phone) {
        document.getElementById('user-phone').textContent = formatPhone(userData.phone);
    }
    if (userData.cnpj) {
        document.getElementById('user-cnpj').textContent = formatCNPJ(userData.cnpj);
    }
    if (userData.created_at) {
        document.getElementById('user-created-at').textContent = formatDate(userData.created_at);
    }
    if (userData.orgao) {
        let orgaoElement = document.getElementById('user-orgao');
        if (!orgaoElement) {
            const orgaoHTML = `
                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Órgão</div>
                        <div class="detail-value" id="user-orgao">${formatOrgao(userData.orgao)}</div>
                    </div>
                </div>
            `;
            document.querySelector('.profile-body .row').insertAdjacentHTML('beforeend', orgaoHTML);
        } else {
            orgaoElement.textContent = formatOrgao(userData.orgao);
        }
    }
}

/**
 * Habilita o modo de edição do perfil, substituindo textos por inputs
 * e removendo as máscaras para edição
 */
function enableEditMode() {
    const fields = {
        'user-email': 'email',
        'user-phone': 'tel',
        'user-cnpj': 'text',
        'user-orgao': 'text'
    };

    for (const [id, type] of Object.entries(fields)) {
        const element = document.getElementById(id);
        if (element) {
            // Remove formatação para edição
            let currentValue = element.textContent.trim();
            
            if (id === 'user-phone') {
                currentValue = currentValue.replace(/\D/g, '');
            } else if (id === 'user-cnpj') {
                currentValue = currentValue.replace(/\D/g, '');
            } else if (currentValue === 'Não informado') {
                currentValue = '';
            }
            
            element.innerHTML = `<input type="${type}" class="form-control edit-input" value="${currentValue}" id="edit-${id}">`;
        }
    }

    // Adiciona máscara dinâmica para CNPJ durante a edição
    const cnpjInput = document.getElementById('edit-user-cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) value = value.slice(0, 14);
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Adiciona máscara dinâmica para telefone durante a edição
    const phoneInput = document.getElementById('edit-user-phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d)/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d)/, '($1) $2-$3');
            }
            e.target.value = value;
        });
    }

    // Atualiza botão de edição para botão de salvar
    const editButton = document.querySelector('.btn-edit');
    editButton.innerHTML = `<i class="bi bi-check-circle me-2"></i> Salvar`;
    editButton.classList.remove('btn-edit');
    editButton.classList.add('btn-save');

    // Adiciona botão de cancelar
    const cancelButton = document.createElement('button');
    cancelButton.className = 'btn btn-secondary ms-2';
    cancelButton.innerHTML = '<i class="bi bi-x-circle me-2"></i> Cancelar';
    cancelButton.onclick = cancelEdit;
    editButton.parentNode.appendChild(cancelButton);
}

/**
 * Cancela a edição e recarrega a página para restaurar os valores originais
 */
function cancelEdit() {
    location.reload();
}

/**
 * Envia os dados editados para o servidor para atualização do perfil
 */
async function saveProfile() {
    const data = {
        email: document.getElementById('edit-user-email')?.value.trim() || '',
        phone: document.getElementById('edit-user-phone')?.value.replace(/\D/g, '') || '',
        cnpj: document.getElementById('edit-user-cnpj')?.value.replace(/\D/g, '') || '',
        orgao: document.getElementById('edit-user-orgao')?.value.trim() || ''
    };

    console.log('Dados a serem enviados:', data);

    try {
        const response = await fetch('/PortalMultiGarantia/configs/Router.php?action=updateUserProfile', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
            credentials: 'include'
        });

        const result = await response.json();
        console.log('Resposta do servidor:', result);

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Perfil atualizado com sucesso!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(result.message || 'Erro ao atualizar perfil');
        }
    } catch (error) {
        console.error('Error:', error);
        showError(error.message || 'Falha ao salvar as alterações');
    }
}

/**
 * Exibe uma mensagem de erro usando SweetAlert
 * @param {string} message - Mensagem de erro a ser exibida
 */
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Erro',
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
}

/**
 * Inicializa a página quando o DOM estiver carregado
 */
document.addEventListener('DOMContentLoaded', function () {
    // Carrega os dados do perfil
    fetchUserProfile();

    // Configura os eventos de clique
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-edit')) {
            e.preventDefault();
            enableEditMode();
        } else if (e.target.closest('.btn-save')) {
            e.preventDefault();
            saveProfile();
        }
    });

    // Animação de fade-in para os elementos
    const animateElements = () => {
        document.querySelectorAll('.fade-in').forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('visible');
            }, index * 150);
        });
    };

    // Observer para animação quando os elementos entram na viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    // Aplica as animações
    animateElements();
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
});