<?php
session_start();
require_once 'db.php';
$currentPage = 'register';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    $confirmar_contraseña = $_POST['confirmar_contraseña'] ?? '';
    
    if(empty($nombre) || empty($email) || empty($contraseña) || empty($confirmar_contraseña)) {
        $error = 'Por favor completa todos los campos';
    } elseif($contraseña !== $confirmar_contraseña) {
        $error = 'Las contraseñas no coinciden';
    } elseif(strlen($contraseña) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $consulta->execute([$email]);
        
        if($consulta->fetch()) {
            $error = 'Este email ya está registrado';
        } else {
            $hash_contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
            $insertar = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'usuario')");
            
            if($insertar->execute([$nombre, $email, $hash_contraseña])) {
                $_SESSION['usuario_id'] = $conexion->lastInsertId();
                $_SESSION['usuario'] = $nombre;
                $_SESSION['rol'] = 'usuario';
                
                header('Location: index.php');
                exit();
            } else {
                $error = 'Error al registrar usuario';
            }
        }
    }
}
$pageTitle = 'Registrarse';
require_once 'includes/header.php';
?>

    <!-- Main Content -->
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="form-auth">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </h2>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-user"></i> Nombre:
                            </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email:
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contraseña" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña:
                            </label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña" required minlength="6">
                            <div class="form-text">Mínimo 6 caracteres</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirmar_contraseña" class="form-label">
                                <i class="fas fa-lock"></i> Confirmar Contraseña:
                            </label>
                            <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-auth w-100">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-2">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                        <p><a href="index.php"><i class="fas fa-arrow-left"></i> Volver al inicio</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'includes/footer_end.php'; ?>