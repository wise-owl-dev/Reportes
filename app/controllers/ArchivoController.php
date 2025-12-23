<?php
/**
 * ArchivoController.php
 * PHP 5.6.3
 * Soporta CSV y XLSX
 * NO guarda archivos en disco
 * NO requiere permisos
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/paths.php';
require_once __DIR__ . '/../../lib/simplexlsx-master/src/SimpleXLSX.php';

$pdo = getDBConnection();

/* =======================
   VALIDACIÓN DE SUBIDA
======================= */
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    die("<p style='color:red;'>No se subió ningún archivo válido.</p>");
}

$tmpFile   = $_FILES['archivo']['tmp_name'];
$fileName  = $_FILES['archivo']['name'];
$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$sheetData = array();

/* =======================
   DETECTAR Y LEER FORMATO
======================= */
if ($extension === 'csv') {

    if (($handle = fopen($tmpFile, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
            $sheetData[] = $row;
        }
        fclose($handle);
    }
    echo "<p>📄 Formato detectado: <strong>CSV</strong></p>";

} elseif ($extension === 'xlsx' || $extension === 'xls') {

    if ($xlsx = \Shuchkin\SimpleXLSX::parse($tmpFile)) {
        $sheetData = $xlsx->rows();
        echo "<p>📊 Formato detectado: <strong>Excel (XLSX)</strong></p>";
    } else {
        die("<p style='color:red;'>Error Excel: " . SimpleXLSX::parseError() . "</p>");
    }

} else {
    die("<p style='color:red;'>Formato no soportado. Use CSV o XLSX.</p>");
}

/* =======================
   PROCESAMIENTO
======================= */
$rowNum = 0;
$registrosInsertados = 0;
$errores = array();

echo "<div style='background:#f8f9fa;padding:20px;border-radius:5px;margin:20px 0;'>";
echo "<h3>Procesando " . count($sheetData) . " filas...</h3>";

foreach ($sheetData as $row) {
    $rowNum++;

    // Saltar encabezados
    if ($rowNum === 1) {
        echo "<p><em>Fila 1: Encabezados omitidos</em></p>";
        continue;
    }

    // Saltar filas vacías
    if (empty($row[0]) && empty($row[3])) {
        continue;
    }

    $data = array(
        'nombre_del_trabajador' => isset($row[0]) ? trim($row[0]) : '',
        'institucion' => isset($row[1]) ? trim($row[1]) : '',
        'adscripcion' => isset($row[2]) ? trim($row[2]) : '',
        'matricula' => isset($row[3]) ? trim($row[3]) : '',
        'identificacion' => isset($row[4]) ? trim($row[4]) : '',
        'telefono' => isset($row[5]) ? trim($row[5]) : '',
        'area_de_salida' => isset($row[6]) ? trim($row[6]) : '',
        'cantidad_bienes' => isset($row[7]) && is_numeric($row[7]) ? intval($row[7]) : 0,
        'naturaleza_bienes' => isset($row[8]) ? trim($row[8]) : '',
        'descripciones' => isset($row[9]) ? json_encode(array_map('trim', explode(',', $row[9]))) : '[]',
        'proposito_bien' => isset($row[10]) ? trim($row[10]) : '',
        'estado_bienes' => isset($row[11]) ? trim($row[11]) : '',
        'devolucion_bienes' => isset($row[12]) ? trim($row[12]) : '',
        'fecha_devolucion' => isset($row[13]) ? trim($row[13]) : '',
        'responsable_entrega' => isset($row[14]) ? trim($row[14]) : '',
        'recibe_salida_bienes' => isset($row[15]) ? trim($row[15]) : '',
        'lugar_fecha' => isset($row[16]) ? trim($row[16]) : '',
        'folio_reporte' => isset($row[17]) ? trim($row[17]) : '',
        'nombre_resguardo' => isset($row[18]) ? trim($row[18]) : '',
        'cargo_resguardo' => isset($row[19]) ? trim($row[19]) : '',
        'direccion_resguardo' => isset($row[20]) ? trim($row[20]) : '',
        'telefono_resguardo' => isset($row[21]) ? trim($row[21]) : '',
        'recibe_resguardo' => isset($row[22]) ? trim($row[22]) : '',
        'entrega_resguardo' => isset($row[23]) ? trim($row[23]) : '',
        'recibe_prestamos_bienes' => isset($row[24]) ? trim($row[24]) : '',
        'matricula_coordinacion' => isset($row[25]) ? trim($row[25]) : '',
        'responsable_control_administrativo' => isset($row[26]) ? trim($row[26]) : '',
        'matricula_administrativo' => isset($row[27]) ? trim($row[27]) : '',
        'departamento_per' => isset($row[28]) ? trim($row[28]) : ''
    );

    if (empty($data['nombre_del_trabajador']) && empty($data['matricula'])) {
        continue;
    }

    try {
        $sql = "INSERT INTO documentos (
            nombre_del_trabajador, institucion, adscripcion, matricula, identificacion,
            telefono, area_de_salida, cantidad_bienes, naturaleza_bienes, descripciones_json,
            proposito_bien, estado_bienes, devolucion_bienes, fecha_devolucion,
            responsable_entrega, recibe_salida_bienes, lugar_fecha, folio_reporte,
            nombre_resguardo, cargo_resguardo, direccion_resguardo, telefono_resguardo,
            recibe_resguardo, entrega_resguardo, recibe_prestamos_bienes,
            matricula_coordinacion, responsable_control_administrativo,
            matricula_administrativo, departamento_per
        ) VALUES (
            :nombre_del_trabajador, :institucion, :adscripcion, :matricula, :identificacion,
            :telefono, :area_de_salida, :cantidad_bienes, :naturaleza_bienes, :descripciones,
            :proposito_bien, :estado_bienes, :devolucion_bienes, :fecha_devolucion,
            :responsable_entrega, :recibe_salida_bienes, :lugar_fecha, :folio_reporte,
            :nombre_resguardo, :cargo_resguardo, :direccion_resguardo, :telefono_resguardo,
            :recibe_resguardo, :entrega_resguardo, :recibe_prestamos_bienes,
            :matricula_coordinacion, :responsable_control_administrativo,
            :matricula_administrativo, :departamento_per
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $registrosInsertados++;
        echo "<p style='color:green;'>✓ Fila $rowNum insertada</p>";

    } catch (PDOException $e) {
        $errores[] = "Fila $rowNum: " . $e->getMessage();
        echo "<p style='color:red;'>✗ Fila $rowNum error</p>";
    }
}

echo "</div>";

/* =======================
   RESUMEN
======================= */
echo "<div style='background:" . (count($errores) ? "#fff3cd" : "#d4edda") . ";padding:20px;border-radius:5px;'>";
echo "<h3>📊 Resumen</h3>";
echo "<p><strong>Insertados:</strong> <span style='color:green;font-size:20px;'>$registrosInsertados</span></p>";
echo "<p><strong>Errores:</strong> <span style='color:red;font-size:20px;'>" . count($errores) . "</span></p>";
echo "<p>
    <a href='../views/archivo.html' style='background:#007bff;color:#fff;padding:10px 20px;border-radius:5px;text-decoration:none;'>Subir otro</a>
    <a href='../views/menu.php' style='background:#28a745;color:#fff;padding:10px 20px;border-radius:5px;text-decoration:none;'>Menú</a>
</p>";
echo "</div>";

