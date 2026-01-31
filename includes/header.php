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