<?php
session_start();
require_once 'db.php';
$currentPage = 'admin';

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$mensaje = '';
$accion = $_GET['accion'] ?? '';

if($accion === 'eliminar_articulo' && isset($_GET['id'])) {
    $id_articulo = $_GET['id'];
    $eliminar = $conexion->prepare("DELETE FROM articulos WHERE id = ?");
    if($eliminar->execute([$id_articulo])) {
        $mensaje = 'Artículo eliminado exitosamente';
    }
}

if($accion === 'cambiar_estado_articulo' && isset($_GET['id']) && isset($_GET['estado'])) {
    $id_articulo = $_GET['id'];
    $nuevo_estado = $_GET['estado'] === '1' ? 0 : 1;
    $actualizar = $conexion->prepare("UPDATE articulos SET estado = ? WHERE id = ?");
    if($actualizar->execute([$nuevo_estado, $id_articulo])) {
        $mensaje = 'Estado del artículo actualizado';
    }
}

if($accion === 'eliminar_usuario' && isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    if($id_usuario != $_SESSION['usuario_id']) {
        $eliminar = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        if($eliminar->execute([$id_usuario])) {
            $mensaje = 'Usuario eliminado exitosamente';
        }
    } else {
        $mensaje = 'No puedes eliminar tu propia cuenta';
    }
}

if($accion === 'cambiar_rol' && isset($_GET['id']) && isset($_GET['rol'])) {
    $id_usuario = $_GET['id'];
    $nuevo_rol = $_GET['rol'] === 'admin' ? 'usuario' : 'admin';
    if($id_usuario != $_SESSION['usuario_id']) {
        $actualizar = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
        if($actualizar->execute([$nuevo_rol, $id_usuario])) {
            $mensaje = 'Rol de usuario actualizado';
        }
    } else {
        $mensaje = 'No puedes cambiar tu propio rol';
    }
}

$consulta_articulos = $conexion->prepare("SELECT * FROM articulos ORDER BY fecha_publicacion DESC");
$consulta_articulos->execute();
$articulos = $consulta_articulos->fetchAll(PDO::FETCH_ASSOC);

$consulta_usuarios = $conexion->prepare("SELECT * FROM usuarios ORDER BY id");
$consulta_usuarios->execute();
$usuarios = $consulta_usuarios->fetchAll(PDO::FETCH_ASSOC);
$pageTitle = 'Panel de Administración';
require_once 'includes/header.php';
?>
    <!-- Main Content -->
    <main class="container mb-5">
        <?php if($mensaje): ?>
            <div class="alert alert-success alert-custom mb-4">
                <i class="fas fa-check-circle"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <h3><?php echo count($articulos); ?></h3>
                    <p><i class="fas fa-newspaper"></i> Total Artículos</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <h3><?php echo count($usuarios); ?></h3>
                    <p><i class="fas fa-users"></i> Total Usuarios</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <h3><?php echo count(array_filter($articulos, fn($a) => $a['estado'] == 1)); ?></h3>
                    <p><i class="fas fa-eye"></i> Artículos Activos</p>
                </div>
            </div>
        </div>
        
        <!-- Gestión de Artículos -->
        <div class="admin-panel mb-5">
            <h2 class="mb-4">
                <i class="fas fa-newspaper"></i> Gestión de Artículos
            </h2>
            
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Likes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($articulos as $articulo): ?>
                        <tr>
                            <td><?php echo $articulo['id']; ?></td>
                            <td><?php echo htmlspecialchars(substr($articulo['titulo'], 0, 50)); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($articulo['categoria']); ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($articulo['fecha_publicacion'])); ?></td>
                            <td>
                                <span class="badge <?php echo $articulo['estado'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $articulo['estado'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>
                            <td>
                                <i class="fas fa-heart text-danger"></i> <?php echo $articulo['likes']; ?>
                            </td>
                            <td>
                                <a href="editar.php?id=<?php echo $articulo['id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="?accion=cambiar_estado_articulo&id=<?php echo $articulo['id']; ?>&estado=<?php echo $articulo['estado']; ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-toggle-<?php echo $articulo['estado'] ? 'on' : 'off'; ?>"></i>
                                    <?php echo $articulo['estado'] ? 'Desactivar' : 'Activar'; ?>
                                </a>
                                <a href="?accion=eliminar_articulo&id=<?php echo $articulo['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Estás seguro de eliminar este artículo?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Gestión de Usuarios -->
        <div class="admin-panel">
            <h2 class="mb-4">
                <i class="fas fa-users"></i> Gestión de Usuarios
            </h2>
            
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <span class="badge <?php echo $usuario['rol'] == 'admin' ? 'bg-purple' : 'bg-primary'; ?>">
                                    <i class="fas fa-<?php echo $usuario['rol'] == 'admin' ? 'crown' : 'user'; ?>"></i>
                                    <?php echo ucfirst($usuario['rol']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($usuario['id'] != $_SESSION['usuario_id']): ?>
                                    <a href="?accion=cambiar_rol&id=<?php echo $usuario['id']; ?>&rol=<?php echo $usuario['rol']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-exchange-alt"></i> Cambiar Rol
                                    </a>
                                    <a href="?accion=eliminar_usuario&id=<?php echo $usuario['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user"></i> Tú
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Blog
            </a>
        </div>
    </main>

    <?php require_once 'includes/footer_end.php'; ?>