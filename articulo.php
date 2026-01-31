<?php
session_start();
require_once 'db.php';
require_once 'markdown.php';

$currentPage = 'articulo';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id_articulo = $_GET['id'];

if(isset($_SESSION['usuario_id'])) {
    // Si hay usuario logueado, traer artículo con información de like
    $consulta = $conexion->prepare("
        SELECT a.*, 
               CASE WHEN l.usuario_id IS NOT NULL THEN 1 ELSE 0 END as ya_dio_like
        FROM articulos a 
        LEFT JOIN likes l ON a.id = l.articulo_id AND l.usuario_id = ?
        WHERE a.id = ? AND a.estado = 1
    ");
    $consulta->execute([$_SESSION['usuario_id'], $id_articulo]);
    $articulo = $consulta->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no hay usuario, solo traer artículo
    $consulta = $conexion->prepare("SELECT *, 0 as ya_dio_like FROM articulos WHERE id = ? AND estado = 1");
    $consulta->execute([$id_articulo]);
    $articulo = $consulta->fetch(PDO::FETCH_ASSOC);
}

if(!$articulo) {
    header('Location: index.php');
    exit();
}

$ya_dio_like = $articulo['ya_dio_like'];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    if(!$ya_dio_like) {
        $insertar_like = $conexion->prepare("INSERT INTO likes (usuario_id, articulo_id) VALUES (?, ?)");
        $insertar_like->execute([$_SESSION['usuario_id'], $id_articulo]);
        
        $actualizar_likes = $conexion->prepare("UPDATE articulos SET likes = likes + 1 WHERE id = ?");
        $actualizar_likes->execute([$id_articulo]);
        
        header("Location: articulo.php?id=$id_articulo");
        exit();
    } else {
        $eliminar_like = $conexion->prepare("DELETE FROM likes WHERE usuario_id = ? AND articulo_id = ?");
        $eliminar_like->execute([$_SESSION['usuario_id'], $id_articulo]);
        
        $actualizar_likes = $conexion->prepare("UPDATE articulos SET likes = likes - 1 WHERE id = ?");
        $actualizar_likes->execute([$id_articulo]);
        
        header("Location: articulo.php?id=$id_articulo");
        exit();
    }
}

$pageTitle = $articulo['titulo'];
require_once 'includes/header.php';
?>

    <!-- Main Content -->
    <main class="container mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="card card-article">
                    <div class="card-body">
                        <h1 class="card-title mb-4">
                            <i class="fas fa-bookmark <?php echo $articulo['ya_dio_like'] ? '-fill text-warning' : '-fill text-secondary'; ?>"></i>
                            <?php echo htmlspecialchars($articulo['titulo']); ?>
                        </h1>
                        
                        <div class="meta-info mb-4">
                            <span class="badge badge-categoria me-2">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($articulo['categoria']); ?>
                            </span>
                            <span class="me-3">
                                <i class="fas fa-calendar-alt"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($articulo['fecha_publicacion'])); ?>
                            </span>
                            <span class="likes-count">
                                <i class="<?php echo $articulo['ya_dio_like'] ? 'fas fa-heart text-danger' : 'far fa-heart'; ?>"></i> 
                                <?php echo $articulo['likes']; ?> likes
                            </span>
                        </div>
                        
                        <?php if($articulo['imagen']): ?>
                            <div class="article-full-image-large mb-4">
                                <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" 
                                     alt="<?php echo htmlspecialchars($articulo['titulo']); ?>" 
                                     class="img-fluid rounded shadow">
                            </div>
                        <?php endif; ?>
                        
                        <div class="contenido">
                            <?php echo parseMarkdown($articulo['contenido']); ?>
                        </div>
                        
                        <div class="mt-4 pt-4 border-top">
                            <?php if(isset($_SESSION['usuario'])): ?>
                                <form method="POST" class="d-inline">
                                    <button type="submit" class="btn btn-like">
                                        <?php echo $ya_dio_like ? '<i class="fas fa-heart"></i> Quitar Like' : '<i class="far fa-heart"></i> Dar Like'; ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <a href="login.php">Inicia sesión</a> para dar like a este artículo
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a todos los artículos
                    </a>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'includes/footer_end.php'; ?>