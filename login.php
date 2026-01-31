<?php
session_start();
require_once 'db.php';
$currentPage = 'login';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    
    if(empty($email) || empty($contraseña)) {
        $error = 'Por favor completa todos los campos';
    } else {
        $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $consulta->execute([$email]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        
        if($usuario && password_verify($contraseña, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            
            header('Location: index.php');
            exit();
        } else {
            $error = 'Email o contraseña incorrectos';
        }
    }
}
$pageTitle = 'Inicio de Sesión';
require_once 'includes/header.php';
?>

    <!-- Main Content -->
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="form-auth">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </h2>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email:
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="contraseña" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña:
                            </label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                        </div>
                        
                        <button type="submit" class="btn btn-auth btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-2">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
                        <p><a href="index.php"><i class="fas fa-arrow-left"></i> Volver al inicio</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'includes/footer_end.php'; ?>