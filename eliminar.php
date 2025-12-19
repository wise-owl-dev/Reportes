<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "reportes";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
if (!$conn->set_charset("utf8")) {
    die("Error cargando el conjunto de caracteres utf8: " . htmlspecialchars($conn->error));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM documentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al eliminar el registro.";
    }
    $stmt->close();
}
$conn->close();
?>
