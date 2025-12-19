<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "reportes";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo "<tr><td colspan='30'>Error en la conexión con la base de datos.</td></tr>";
    exit();
}
if (!$conn->set_charset("utf8")) {
    echo "<tr><td colspan='30'>Error cargando el conjunto de caracteres utf8: " . htmlspecialchars($conn->error) . "</td></tr>";
    exit();
}
$searchTerm = '';
if (isset($_POST['search_term'])) {
    $searchTerm = trim($_POST['search_term']);
}
$sql = "SELECT * FROM documentos";
$params = [];
$types = '';
if (!empty($searchTerm)) {
    $searchTerm = strtolower($searchTerm);
    $sql .= " WHERE LOWER(descripciones_json) LIKE ? 
              OR LOWER(adscripcion) LIKE ?
              OR LOWER(folio_reporte) LIKE ?
              OR LOWER(nombre_del_trabajador) LIKE ?
              OR LOWER(lugar_fecha) LIKE ?
              OR LOWER(proposito_bien) LIKE ?";
    $types = "ssssss";
    $params = array_fill(0, 6, "%$searchTerm%");
}
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td>
            <a href='editar.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-info btn-action'>Editar</a>
            <button class='btn btn-danger btn-action btn-delete' data-id='" . htmlspecialchars($row['id']) . "'>Eliminar</button>
            <a href='reimprimir.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-info btn-action'>Imprimir</a>
        </td>";
        $output .= "<td>" . htmlspecialchars($row['id']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['nombre_del_trabajador']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['institucion']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['adscripcion']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['matricula']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['identificacion']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['telefono']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['area_de_salida']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['cantidad_bienes']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['naturaleza_bienes']) . "</td>";
        $descripciones = json_decode($row['descripciones_json'], true);
if (is_array($descripciones)) {
    $output .= "<td>" . htmlspecialchars(implode(", ", $descripciones)) . "</td>";
} else {
    $output .= "<td>Formato inválido</td>";
}
        $output .= "<td>" . htmlspecialchars($row['proposito_bien']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['estado_bienes']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['devolucion_bienes']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['fecha_devolucion']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['responsable_entrega']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['recibe_salida_bienes']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['lugar_fecha']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['folio_reporte']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['nombre_resguardo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['cargo_resguardo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['direccion_resguardo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['telefono_resguardo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['recibe_resguardo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['entrega_resguardo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['recibe_prestamos_bienes']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['matricula_coordinacion']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['responsable_control_administrativo']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['matricula_administrativo']) . "</td>";
        $output .= "</tr>";
    }
} else {
    $output .= "<tr><td colspan='30'>No se encontraron registros.</td></tr>";
}
echo $output;
$stmt->close();
$conn->close();
?>
