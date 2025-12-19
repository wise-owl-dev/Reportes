<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Navegación</title>
    <link rel="stylesheet" href="menu.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <nav class="navbar">
    <h1 class="menu-title">Menú</h1> 
        <ul class="menu">
            <?php
            $menu_items = [
                ['Salidas', 'rellenado.php', 'fa-file'],
                ['Cargar archivo', 'archivo.html', 'fa-upload'],
                ['Tabla de registros', 'mostrar.php', 'fa-table']
                
            ];
            foreach ($menu_items as $item) {
                echo "<li><a href='" . $item[1] . "'><i class='fas " . $item[2] . "'></i> " . $item[0] . "</a></li>";
            }
            ?>
        </ul>
    </nav>
</body>
</html>
