<?php
session_start();
require_once 'db.php';
$currentPage = 'articulos_guardados';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$articulos_guardados = [];
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'fecha';

try {
    $sql_base = "SELECT a.*, l.fecha as fecha_guardado 
                 FROM articulos a 
                 INNER JOIN likes l ON a.id = l.articulo_id 
                 WHERE l.usuario_id = ? AND a.estado = 1";
    
    $params = [$_SESSION['usuario_id']];
    
    if (!empty($categoria_filtro)) {
        $sql_base .= " AND a.categoria = ?";
        $params[] = $categoria_filtro;
    }
    
    switch ($orden) {
        case 'titulo':
            $sql_base .= " ORDER BY a.titulo ASC";
            break;
        case 'likes':
            $sql_base .= " ORDER BY a.likes DESC";
            break;
        case 'categoria':
            $sql_base .= " ORDER BY a.categoria ASC, a.fecha_publicacion DESC";
            break;
        default:
            $sql_base .= " ORDER BY l.fecha DESC";
    }
    
    $sql_limit = $sql_base . " LIMIT $offset, $por_pagina";
    $consulta = $conexion->prepare($sql_limit);
    $consulta->execute($params);
    $articulos_guardados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
    $consulta_total = $conexion->prepare("SELECT COUNT(*) as total FROM ($sql_base) as conteo");
    $consulta_total->execute($params);
    $total_articulos = $consulta_total->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total_articulos / $por_pagina);
    
    $consulta_categorias = $conexion->prepare("
        SELECT DISTINCT a.categoria 
        FROM articulos a 
        INNER JOIN likes l ON a.id = l.articulo_id 
        WHERE l.usuario_id = ? AND a.estado = 1 AND a.categoria IS NOT NULL AND a.categoria != ''
        ORDER BY a.categoria
    ");
    $consulta_categorias->execute([$_SESSION['usuario_id']]);
    $categorias = $consulta_categorias->fetchAll(PDO::FETCH_COLUMN);
    
} catch(PDOException $e) {
    die("Error al consultar artículos guardados: " . $e->getMessage());
}

$pageTitle = 'Mis Artículos Guardados';
require_once 'includes/header.php';
?>
    <main class="mb-5">
        <!-- Filtros y ordenamiento -->
        <div class="container mb-4">
            <div class="row">
                <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="articulos_guardados.php" class="row g-3">
                            <div class="col-md-4">
                                <label for="categoria" class="form-label">
                                    <i class="fas fa-filter"></i> Categoría
                                </label>
                                <select name="categoria" id="categoria" class="form-select">
                                    <option value="">Todas las categorías</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>" 
                                                <?php echo $categoria_filtro == $cat ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="orden" class="form-label">
                                    <i class="fas fa-sort"></i> Ordenar por
                                </label>
                                <select name="orden" id="orden" class="form-select">
                                    <option value="fecha" <?php echo $orden == 'fecha' ? 'selected' : ''; ?>>
                                        Fecha guardado
                                    </option>
                                    <option value="titulo" <?php echo $orden == 'titulo' ? 'selected' : ''; ?>>
                                        Título A-Z
                                    </option>
                                    <option value="likes" <?php echo $orden == 'likes' ? 'selected' : ''; ?>>
                                        Más populares
                                    </option>
                                    <option value="categoria" <?php echo $orden == 'categoria' ? 'selected' : ''; ?>>
                                        Categoría
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="articulos_guardados.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!-- Estadísticas -->
        <div class="container mb-4">
            <div class="row">
                <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Tienes <strong><?php echo $total_articulos; ?></strong> artículos guardados
                    <?php if (!empty($categoria_filtro)): ?>
                        en la categoría <strong><?php echo htmlspecialchars($categoria_filtro); ?></strong>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Lista de artículos guardados -->
        <div class="container">
            <div class="row g-4 justify-content-center">
            <?php if(count($articulos_guardados) > 0): ?>
                <?php foreach($articulos_guardados as $articulo): ?>
                    <div class="col-12 col-lg-6">
                        <div class="card card-article h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-bookmark-fill text-warning"></i>
                                    <a href="articulo.php?id=<?php echo $articulo['id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($articulo['titulo']); ?>
                                    </a>
                                </h5>
                                
                                <div class="meta-info">
                                    <span class="badge badge-categoria me-2">
                                        <i class="fas fa-tag"></i> <?php echo htmlspecialchars($articulo['categoria']); ?>
                                    </span>
                                    <span class="me-3">
                                        <i class="fas fa-calendar-alt"></i> 
                                        <?php echo date('d/m/Y', strtotime($articulo['fecha_publicacion'])); ?>
                                    </span>
                                    <span class="me-3">
                                        <i class="fas fa-bookmark"></i> 
                                        Guardado: <?php echo date('d/m/Y', strtotime($articulo['fecha_guardado'])); ?>
                                    </span>
                                    <span class="likes-count">
                                        <i class="fas fa-heart text-danger"></i> 
                                        <?php echo $articulo['likes']; ?> likes
                                    </span>
                                </div>
                                
                                <p class="card-text mt-3">
                                    <?php echo substr(htmlspecialchars($articulo['contenido']), 0, 200); ?>...
                                </p>
                                
                                <a href="articulo.php?id=<?php echo $articulo['id']; ?>" 
                                       class="btn btn-leer-mas">
                                        Leer completo <i class="fas fa-arrow-right"></i>
                                </a>
                                    <br><br>
                                <small class="text-muted guardado">
                                        <i class="fas fa-clock"></i> 
                                        Guardado <?php 
                                            $dias = (strtotime(date('Y-m-d H:i:s')) - strtotime($articulo['fecha_guardado'])) / 86400;
                                            if($dias < 1) echo "hoy";
                                            elseif($dias < 2) echo "ayer";
                                            elseif($dias < 7) echo "hace ". floor($dias) . " días";
                                            else echo "hace ". floor($dias/7) . " semanas";
                                        ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-bookmark"></i> 
                        No tienes artículos guardados aún. 
                        <a href="index.php" class="alert-link">Explora los artículos</a> y guarda tus favoritos.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        </div>

        <!-- Paginación -->
        <?php if($total_paginas > 1): ?>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12">
                    <nav aria-label="Paginación de artículos guardados">
                        <ul class="pagination justify-content-center">
                            <?php if($pagina > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina-1; 
                                        echo !empty($categoria_filtro) ? '&categoria=' . urlencode($categoria_filtro) : '';
                                        echo '&orden=' . urlencode($orden); ?>">
                                        <i class="fas fa-chevron-left"></i> Anterior
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                                <?php if($i == $pagina): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo $i; ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pagina=<?php echo $i; 
                                            echo !empty($categoria_filtro) ? '&categoria=' . urlencode($categoria_filtro) : '';
                                            echo '&orden=' . urlencode($orden); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if($pagina < $total_paginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina+1; 
                                        echo !empty($categoria_filtro) ? '&categoria=' . urlencode($categoria_filtro) : '';
                                        echo '&orden=' . urlencode($orden); ?>">
                                        Siguiente <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
            </div>
        <?php endif; ?>
    </main>

<?php require_once 'includes/footer_end.php'; ?>