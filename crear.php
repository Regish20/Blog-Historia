<?php
session_start();
require_once 'db.php';
require_once 'markdown.php';

$currentPage = 'crear';

if(!isset($_SESSION['usuario'])) {
    header('Location: login.php');
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
        $imagen = '';
        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $directorio_uploads = 'uploads/';
            if(!file_exists($directorio_uploads)) {
                mkdir($directorio_uploads, 0777, true);
            }
            
            $nombre_archivo = time() . '_' . basename($_FILES['imagen']['name']);
            $ruta_destino = $directorio_uploads . $nombre_archivo;
            
            if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $imagen = $ruta_destino;
            }
        }
        
        $insertar = $conexion->prepare("INSERT INTO articulos (titulo, contenido, imagen, categoria, estado) VALUES (?, ?, ?, ?, 1)");
        
        if($insertar->execute([$titulo, $contenido, $imagen, $categoria])) {
            $exito = '¡Artículo publicado exitosamente!';
        } else {
            $error = 'Error al publicar el artículo';
        }
    }
}

$pageTitle = 'Crear Artículo';
require_once 'includes/header.php';
?>

    <!-- Main Content -->
    <main class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-auth">
                    <h2 class="mb-4 text-center">
                        <i class="fas fa-plus-circle"></i> Crear Nuevo Artículo
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
                        <div class="text-center mt-3">
                            <a href="index.php" class="btn btn-success">
                                <i class="fas fa-eye"></i> Ver tu artículo publicado
                            </a>
                        </div>
                    <?php else: ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">
                                <i class="fas fa-heading"></i> Título del Artículo:
                            </label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoria" class="form-label">
                                <i class="fas fa-tag"></i> Categoría:
                            </label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="Política">Política</option>
                                <option value="Guerras">Guerras</option>
                                <option value="Ciencias">Ciencias</option>
                                <option value="Cultura">Cultura</option>
                                <option value="Deportes">Deportes</option>
                                <option value="Economía">Economía</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contenido" class="form-label">
                                <i class="fas fa-align-left"></i> Contenido del Artículo:
                            </label>
                            <div class="alert alert-info alert-custom">
                                <h6><i class="fas fa-info-circle"></i> Formato de Texto Disponible:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Negrita:</strong> **texto**<br>
                                            <strong>Cursiva:</strong> *texto*<br>
                                            <strong>Subtítulo:</strong> ## Título<br>
                                            <strong>Separador:</strong> --- (línea horizontal)
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Lista:</strong> - elemento<br>
                                            <strong>Lista numerada:</strong> 1. elemento<br>
                                            <strong>Cita:</strong> > texto citado<br>
                                            <strong>Link:</strong> [texto](url)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control" id="contenido" name="contenido" rows="10" required 
                                      placeholder="Escribe aquí el contenido completo de tu artículo histórico...

Ejemplo:
# Título Principal
Este es un párrafo normal con **texto en negrita** y *texto en cursiva*.

## Subtítulo
- Primer punto importante
- Segundo punto importante
- Tercer punto

> Esta es una cita destacada

---"></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="imagen" class="form-label">
                                <i class="fas fa-image"></i> Imagen (opcional):
                            </label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-auth">
                                <i class="fas fa-paper-plane"></i> Publicar Artículo
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'includes/footer_end.php'; ?>