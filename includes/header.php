<?php
require_once 'navbar.php';
require_once 'db.php';
require_once 'markdown.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($pageTitle)): ?>
        <title><?php echo htmlspecialchars($pageTitle); ?> - Blog Histórico</title>
    <?php else: ?>
        <title>Blog Histórico</title>
    <?php endif; ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23d4a574'><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z'/><path d='M9 16.17l-4.17-4.17L3.83 13l5.17 5.17L21 6.83 19.59 5.41z'/><path d='M11 7h2v6h-2z'/><path d='M7 9v2h6V9z'/></svg>">
</head>
<body>
    <?php
    if(isset($currentPage)) {
        renderNavbar($currentPage);
    } else {
        renderNavbar();
    }
    ?>
    
    <main>