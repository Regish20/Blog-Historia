<?php
session_start();
require_once 'db.php';
$currentPage = 'editar';

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$id_articulo = $_GET['id'];

$consulta = $conexion->prepare("SELECT * FROM articulos WHERE id = ?");
$consulta->execute([$id_articulo]);
$articulo = $consulta->fetch(PDO::FETCH_ASSOC);

if(!$articulo) {
    header('Location: admin.php');
    exit();
}

$error = '';
$exito = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    
    if(empty($titulo) || empty($contenido) || empty($categoria)) {
        $error = 'Por favor completa todos los campos';
    } else {
        $imagen_actual = $articulo['imagen'];
        
        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $directorio_uploads = 'uploads/';
            if(!file_exists($directorio_uploads)) {
                mkdir($directorio_uploads, 0777, true);
            }
            
            $nombre_archivo = time() . '_' . basename($_FILES['imagen']['name']);
            $ruta_destino = $directorio_uploads . $nombre_archivo;
            
            if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $imagen_actual = $ruta_destino;
            }
        }
        
        $actualizar = $conexion->prepare("UPDATE articulos SET titulo = ?, contenido = ?, categoria = ?, imagen = ? WHERE id = ?");
        
        if($actualizar->execute([$titulo, $contenido, $categoria, $imagen_actual, $id_articulo])) {
            $exito = '¡Artículo actualizado exitosamente!';
            
            $consulta_actualizado = $conexion->prepare("SELECT * FROM articulos WHERE id = ?");
            $consulta_actualizado->execute([$id_articulo]);
            $articulo = $consulta_actualizado->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Error al actualizar el artículo';
        }
    }
}
$pageTitle = "Editar Artículo";
require_once 'includes/header.php';
?>

    <!-- Main Content -->
    <main class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="form-auth">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-edit"></i> Editar Artículo
                    </h2>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($exito): ?>
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle"></i> <?php echo $exito; ?>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-auth">
                                <a href="articulo.php?id=<?php echo $articulo['id']; ?>" class="enlace-inactivo">
                                    <i class="fas fa-eye"></i> Ver Artículo
                                </a>
                            </button>
                        </div>
                    <?php else: ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">
                                <i class="fas fa-heading"></i> Título del Artículo:
                            </label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required 
                                   value="<?php echo htmlspecialchars($articulo['titulo']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoria" class="form-label">
                                <i class="fas fa-tag"></i> Categoría:
                            </label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="Política" <?php echo $articulo['categoria'] == 'Política' ? 'selected' : ''; ?>>Política</option>
                                <option value="Guerras" <?php echo $articulo['categoria'] == 'Guerras' ? 'selected' : ''; ?>>Guerras</option>
                                <option value="Ciencias" <?php echo $articulo['categoria'] == 'Ciencias' ? 'selected' : ''; ?>>Ciencias</option>
                                <option value="Cultura" <?php echo $articulo['categoria'] == 'Cultura' ? 'selected' : ''; ?>>Cultura</option>
                                <option value="Deportes" <?php echo $articulo['categoria'] == 'Deportes' ? 'selected' : ''; ?>>Deportes</option>
                                <option value="Economía" <?php echo $articulo['categoria'] == 'Economía' ? 'selected' : ''; ?>>Economía</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contenido" class="form-label">
                                <i class="fas fa-align-left"></i> Contenido del Artículo:
                            </label>
                            <textarea class="form-control" id="contenido" name="contenido" rows="8" required><?php echo htmlspecialchars($articulo['contenido']); ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="imagen" class="form-label">
                                <i class="fas fa-image"></i> Imagen (opcional):
                            </label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <div class="form-text">Deja vacío para mantener la imagen actual</div>
                            <?php if($articulo['imagen']): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Imagen actual:</small><br>
                                    <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-eye"></i> Estado:
                                </label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1" <?php echo $articulo['estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $articulo['estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-auth">
                                <i class="fas fa-save"></i> Actualizar Artículo
                            </button>
                        </div>
                    </form>
                    
                    <?php endif; ?>
                    
                    <div class="text-center mt-4">
                        <a href="admin.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'includes/footer_end.php'; ?>