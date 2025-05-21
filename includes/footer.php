<footer style="background-color: #fff; padding: 40px 20px; font-family: Arial, sans-serif; color: #1d1d1b;">
    <div
        style="display: flex; flex-wrap: wrap; justify-content: space-between; max-width: 1200px; margin: auto; padding: 20px 0;">
        <!-- Coluna 1: Redes sociais + logo -->
        <div style="flex: 1 1 300px; margin-bottom: 20px;">
            <p><strong>Siga-nos</strong></p>
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram" /></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" /></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/1384/1384060.png" alt="YouTube" /></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/145/145807.png" alt="LinkedIn" /></a>
            </div>
            <img src="/PortalMultiGarantia/public/img/grupomulti.svg" alt="Multi Logo" style="width: 200px;">
        </div>

        <!-- Coluna 2: Informações da empresa -->
        <div style="flex: 1 1 300px; margin-bottom: 20px;">
            <p style="font-size: 12px; color: #666;">
                <strong>MULTILASER INDUSTRIAL SA</strong><br>
                CNPJ 59.717.553/0001-02<br>
                Inscrição 112.159.766.117<br>
                Av. Brigadeiro Faria Lima, Nº1811, 15º andar<br>
                CEP 01452-001 - São Paulo – SP
            </p>
        </div>
    </div>

    <!-- Linha de direitos autorais -->
    <div style="border-top: 1px solid #ddd; max-width: 1200px; margin: 0 auto; padding: 20px 0;">
        <div style="text-align: center;">
            <small>&copy; <span id="current-year">2024</span> Portal Multi Garantia - Todos os direitos
                reservados.</small>
        </div>
    </div>

</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const yearSpan = document.getElementById('current-year');
        if (yearSpan) {
            yearSpan.textContent = new Date().getFullYear();
        }
    });
</script>