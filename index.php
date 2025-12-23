<?php
/**
 * Punto de entrada principal del proyecto
 * Redirecciona automáticamente al menú
 */

// Cargar configuración de rutas
require_once __DIR__ . '/config/paths.php';

// Detectar el protocolo (http o https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

// Construir la URL completa
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . BASE_URL;

// Redirigir al menú principal
header('Location: ' . $baseUrl . '/app/views/menu.php');
exit;
?>
