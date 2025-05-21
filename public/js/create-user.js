document.addEventListener('DOMContentLoaded', function () {
    // Animação
    const animateElements = () => {
        document.querySelectorAll('.fade-in').forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('visible');
            }, index * 150);
        });
    };
    animateElements();
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
    // Máscaras
    $('#cnpj').mask('000.000.000-00', {
        reverse: true
    });
    $('#phone').mask('(00) 00000-0000');
    // Validação do formulário
    const form = document.getElementById('userForm');
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        // Coletar dados do formulário
        const formData = {
            username: document.getElementById('username').value.trim(),
            orgao: document.getElementById('orgao').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            cnpj: document.getElementById('cnpj').value.trim(),
            password: document.getElementById('password').value,
            role: document.getElementById('role').value
        };
        if (!formData.username || !formData.orgao || !formData.email || !formData.password) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Por favor, preencha todos os campos obrigatórios!'
            });
            return;
        }
        // Validação de email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Por favor, insira um email válido!'
            });
            return;
        }
        try {
            formData.password = md5(formData.password);
            const response = await fetch('/PortalMultiGarantia/configs/Router.php?action=createUser', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            const data = await response.json();
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message || 'Usuário cadastrado com sucesso!',
                    willClose: () => {
                        form.reset();
                    }
                });
            } else {
                throw new Error(data.message || 'Erro ao criar usuário');
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: error.message || 'Falha ao cadastrar usuário'
            });
        }
    });
});