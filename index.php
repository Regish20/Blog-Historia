<?php
session_start();
require_once 'db.php';
require_once 'markdown.php';

$currentPage = 'index';

if(isset($_SESSION['usuario_id'])) {
    // Si hay usuario logueado, traer información de likes
    $consulta = $conexion->prepare("
        SELECT a.*, 
               CASE WHEN l.usuario_id IS NOT NULL THEN 1 ELSE 0 END as ya_dio_like
        FROM articulos a 
        LEFT JOIN likes l ON a.id = l.articulo_id AND l.usuario_id = ?
        WHERE a.estado = 1 
        ORDER BY a.fecha_publicacion DESC
    ");
    $consulta->execute([$_SESSION['usuario_id']]);
} else {
    // Si no hay usuario, solo traer artículos
    $consulta = $conexion->prepare("SELECT *, 0 as ya_dio_like FROM articulos WHERE estado = 1 ORDER BY fecha_publicacion DESC");
    $consulta->execute();
}
$articulos = $consulta->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

    <!-- Main Content -->
    <main class="container-fluid mb-5 px-4">
        <div class="row g-4 justify-content-center">
            <?php if(count($articulos) > 0): ?>
                <?php foreach($articulos as $articulo): ?>
                    <div class="col-12 col-md-6 col-xl-5 col-xxxl-4">
                        <div class="card card-article h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-bookmark <?php echo $articulo['ya_dio_like'] ? '-fill text-warning' : '-fill text-secondary'; ?>"></i>
                                    <?php echo htmlspecialchars($articulo['titulo']); ?>
                                </h5>
                                
                                <?php if($articulo['imagen']): ?>
                                    <div class="article-image-card mb-3">
                                        <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" 
                                            alt="<?php echo htmlspecialchars($articulo['titulo']); ?>" 
                                            class="img-fluid rounded">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="meta-info">
                                    <span class="badge badge-categoria me-2">
                                        <i class="fas fa-tag"></i> <?php echo htmlspecialchars($articulo['categoria']); ?>
                                    </span>
                                    <span class="me-3">
                                        <i class="fas fa-calendar-alt"></i> 
                                        <?php echo date('d/m/Y', strtotime($articulo['fecha_publicacion'])); ?>
                                    </span>
                                    <span class="likes-count">
                                        <i class="<?php echo $articulo['ya_dio_like'] ? 'fas fa-heart text-danger' : 'far fa-heart'; ?>"></i> 
                                        <?php echo $articulo['likes']; ?> likes
                                    </span>
                                </div>
                                
                                <p class="card-text mt-3">
                                    <?php 
                                    $contenido_html = parseMarkdown($articulo['contenido']);
                                    $contenido_plano = strip_tags($contenido_html);
                                    echo substr($contenido_plano, 0, 200); 
                                    ?>...
                                </p>
                                
                                <a href="articulo.php?id=<?php echo $articulo['id']; ?>" class="btn btn-leer-mas">
                                    Leer más <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> No hay artículos publicados aún.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

<?php require_once 'includes/footer_end.php'; ?>