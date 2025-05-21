$(document).ready(async () => {
    $('.card').hide().fadeIn(300);

    $('#toggle-type').click(() => {
        const password = $('#password');
        const icon = $('#toggle-icon');
        const isPassword = password.attr('type') === 'password';
        password.attr('type', isPassword ? 'text' : 'password');
        icon.toggleClass('bx-low-vision bx-show');
    });

    $('.login-card').hide().fadeIn(800);

    $('#login').submit(async function (event) {
        event.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...');
        const formData = new FormData(this);
        fetch('/PortalMultiGarantia/configs/Router.php?action=login',
            {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Primeiro mostra o alerta
                    Swal.fire({
                        icon: 'success',
                        title: 'Login realizado com sucesso!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.card').fadeOut(400, () => {
                                location.href = '/PortalMultiGarantia/views/home';
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Usuário ou Senha Incorretos!',
                        text: 'Verifique suas credenciais de acesso.',
                        confirmButtonText: 'Fechar',
                        allowOutsideClick: false,
                        allowEscapeKey: true
                    }).then(() => {
                        // Restaura o botão
                        submitBtn.prop('disabled', false);
                        submitBtn.text('Entrar');
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Contate o time de desenvolvimento.',
                    confirmButtonText: 'Fechar',
                    allowOutsideClick: false,
                    allowEscapeKey: true
                }).then(() => {
                    // Restaura o botão
                    submitBtn.prop('disabled', false);
                    submitBtn.text('Entrar');
                });
            });
    });
});