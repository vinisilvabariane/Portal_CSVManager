<!-- Adicione isso no head do seu documento para os ícones do Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm py-2" style="border-bottom: 1px solid #f0f0f0;">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center fs-4 fw-bold" href="/PortalMultiGarantia/views/home/" style="color: #2a2a2a;">
      <img src="/PortalMultiGarantia/public/img/multi.svg" width="110" class="me-2" alt="Logo Multi Garantia">
    </a>

    <!-- Botão para mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Itens do menu -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarToggle">
      <ul class="navbar-nav align-items-center gap-2 gap-lg-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-2 px-lg-3 py-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #333; font-weight: 500;">
            <i class="bi bi-search me-1"></i>
            <span>Consultar Garantia</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="min-width: 200px;">
            <li><a class="dropdown-item py-2" href="/PortalMultiGarantia/views/consult/index.php"><i class="bi bi-list-ul me-2"></i> Consultar Garantia</a></li>
            <li><a class="dropdown-item py-2" href="/PortalMultiGarantia/views/consult-nota/index.php"><i class="bi bi-list-ol me-2"></i> Consultar Nota Fiscal</a></li>
          </ul>
        </li>

        <!-- Menu Admin -->
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle px-2 px-lg-3 py-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #333; font-weight: 500;">
              <i class="bi bi-gear me-1"></i>
              <span>Administração</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="min-width: 200px;">
              <li><a class="dropdown-item py-2" href="/PortalMultiGarantia/views/create-user/index.php"><i class="bi bi-person-plus me-2"></i> Criar Usuários</a></li>
              <li><a class="dropdown-item py-2" href="/PortalMultiGarantia/views/tabela-garantia/index.php"><i class="bi bi-table me-2"></i> Tabela de Garantias</a></li>
              <li><a class="dropdown-item py-2" href="/PortalMultiGarantia/views/tabela-serial/index.php"><i class="bi bi-list me-2"></i> Tabela de Seriais</a></li>
            </ul>
          </li>
        <?php endif; ?>

        <!-- Dúvidas -->
        <li class="nav-item">
          <a class="nav-link px-2 px-lg-3 py-2" href="/PortalMultiGarantia/views/doubts/" style="color: #333; font-weight: 500;">
            <i class="bi bi-question-circle me-1"></i>
            <span>Dúvidas</span>
          </a>
        </li>

        <!-- Menu do Usuário -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-2 px-lg-3 py-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #333; font-weight: 500;">
            <i class="bi bi-person-circle me-1"></i>
            <span class="d-none d-lg-inline">Minha Conta</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="min-width: 200px;">
            <li><a class="dropdown-item py-2" href="/PortalMultiGarantia/views/profile/index.php"><i class="bi bi-person me-2"></i> Perfil</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item py-2 text-danger" href="/PortalMultiGarantia/views/login/"><i class="bi bi-box-arrow-right me-2"></i> Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Debug Session (opcional, remova em produção) -->
<!-- <div style="position: fixed; bottom: 0; left: 0; background: white; z-index: 9999; padding: 10px; border: 1px solid red;"> -->
<?php
// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';
?>
<!-- </div> -->