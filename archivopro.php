<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
require 'vendor/autoload.php'; 
if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
    die('IOFactory no se pudo encontrar. Verifica la instalación de PhpSpreadsheet.');
}
$host = 'localhost';
$dbname = 'reportes';
$username = 'root';
$password = 'admin';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $carpetaDestino = 'uploads/';
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $nombreArchivo = basename($_FILES['archivo']['name']);
    $rutaArchivo = $carpetaDestino . $nombreArchivo;
    
    if (move_uploaded_file($archivoTmp, $rutaArchivo)) {
        echo "Archivo subido con éxito: $nombreArchivo <br>";
    } else {
        die("Error al subir el archivo.");
    }

    $spreadsheet = IOFactory::load($rutaArchivo);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    foreach ($sheetData as $row) {
        $data = [
            'nombre_del_trabajador' => isset($row['A']) ? $row['A'] : '',
            'institucion' => isset($row['B']) ? $row['B'] : '',
            'adscripcion' => isset($row['C']) ? $row['C'] : '',
            'matricula' => isset($row['D']) ? $row['D'] : '',
            'identificacion' => isset($row['E']) ? $row['E'] : '',
            'telefono' => isset($row['F']) ? $row['F'] : '',
            'area_de_salida' => isset($row['G']) ? $row['G'] : '',
            'cantidad_bienes' => isset($row['H']) ? $row['H'] : 0,
            'naturaleza_bienes' => isset($row['I']) ? $row['I'] : '',
            'descripciones' => isset($row['J']) ? $row['J'] : '',
            'proposito_bien' => isset($row['K']) ? $row['K'] : '',
            'estado_bienes' => isset($row['L']) ? $row['L'] : '',
            'devolucion_bienes' => isset($row['M']) ? $row['M'] : '',
            'fecha_devolucion' => isset($row['N']) ? $row['N'] : '',
            'responsable_entrega' => isset($row['O']) ? $row['O'] : '',
            'recibe_salida_bienes' => isset($row['P']) ? $row['P'] : '',
            'lugar_fecha' => isset($row['Q']) ? $row['Q'] : '',
            'folio_reporte' => isset($row['R']) ? $row['R'] : '',
            'nombre_resguardo' => isset($row['S']) ? $row['S'] : '',
            'cargo_resguardo' => isset($row['T']) ? $row['T'] : '',
            'direccion_resguardo' => isset($row['U']) ? $row['U'] : '',
            'telefono_resguardo' => isset($row['V']) ? $row['V'] : '',
            'recibe_resguardo' => isset($row['W']) ? $row['W'] : '',
            'recibe_prestamos_bienes' => isset($row['X']) ? $row['X'] : '',
            'matricula_coordinacion' => isset($row['Y']) ? $row['Y'] : '',
            'responsable_control_administrativo' => isset($row['Z']) ? $row['Z'] : '',
            'matricula_administrativo' => isset($row['AA']) ? $row['AA'] : '',
            'departamento_per' => isset($row['AB']) ? $row['AB'] : '',
        ];
        try {
            $sql = "INSERT INTO documentos (
                nombre_del_trabajador, institucion, adscripcion, matricula, identificacion, telefono, area_de_salida,
                cantidad_bienes, naturaleza_bienes, descripciones, proposito_bien, estado_bienes, devolucion_bienes, fecha_devolucion,
                responsable_entrega, recibe_salida_bienes, lugar_fecha, folio_reporte, nombre_resguardo, cargo_resguardo,
                direccion_resguardo, telefono_resguardo, recibe_resguardo, recibe_prestamos_bienes,
                matricula_coordinacion, responsable_control_administrativo, matricula_administrativo, departamento_per
            ) VALUES (
                :nombre_del_trabajador, :institucion, :adscripcion, :matricula, :identificacion, :telefono, :area_de_salida,
                :cantidad_bienes, :naturaleza_bienes, :descripciones, :proposito_bien, :estado_bienes, :devolucion_bienes, :fecha_devolucion,
                :responsable_entrega, :recibe_salida_bienes, :lugar_fecha, :folio_reporte, :nombre_resguardo, :cargo_resguardo,
                :direccion_resguardo, :telefono_resguardo, :recibe_resguardo, :recibe_prestamos_bienes,
                :matricula_coordinacion, :responsable_control_administrativo, :matricula_administrativo, :departamento_per
            )";
            
            $stmt = $pdo->prepare($sql);  
            $stmt->execute($data);

            echo "Datos insertados correctamente en la base de datos.<br>";
        } catch (PDOException $e) {
            die("Error al insertar los datos: " . $e->getMessage());
        }
    }
} else {
    die("No se subió ningún archivo.");
}
?>
