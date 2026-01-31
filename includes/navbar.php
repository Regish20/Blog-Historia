<?php
function renderNavbar($currentPage = '') {
    $currentPageClass = function($page) use ($currentPage) {
        return $currentPage === $page ? 'active' : '';
    };
    ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="text-center">
                <h1><i class="fas fa-book"></i> Blog Histórico</h1>
                <p class="lead">Explora los momentos que cambiaron el mundo</p>
            </div>
        </div>
    </section>

    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-history"></i> Blog Histórico
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPageClass('index'); ?>" href="index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <?php if(isset($_SESSION['usuario'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPageClass('articulos_guardados'); ?>" href="articulos_guardados.php">
                                <i class="fas fa-bookmark"></i> Guardados
                            </a>
                        </li>
                        <?php if($_SESSION['rol'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $currentPageClass('admin'); ?>" href="admin.php">
                                    <i class="fas fa-cogs"></i> Panel Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $currentPageClass('crear'); ?>" href="crear.php">
                                    <i class="fas fa-plus-circle"></i> Crear Artículo
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Salir (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPageClass('login'); ?>" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPageClass('register'); ?>" href="register.php">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}
?>