<?php
$host = 'localhost';
$dbname = 'reportes';
$username = 'root';
$password = 'admin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre_del_trabajador = $_POST['nombre_del_trabajador'];
    $institucion = $_POST['institucion'];
    $adscripcion = $_POST['adscripcion'];
    $matricula = $_POST['matricula'];
    $identificacion = $_POST['identificacion'];
    $telefono = $_POST['telefono'];
    $area_de_salida = $_POST['area_de_salida'];
    $cantidad_bienes = $_POST['cantidad_bienes'];
    $naturaleza_bienes = $_POST['naturaleza_bienes'];
    $descripciones_json = json_encode(explode(", ", $_POST['descripciones_json']));
    $proposito_bien = $_POST['proposito_bien'];
    $estado_bienes = $_POST['estado_bienes'];
    $devolucion_bienes = $_POST['devolucion_bienes'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $responsable_entrega = $_POST['responsable_entrega'];
    $recibe_salida_bienes = $_POST['recibe_salida_bienes'];
    $lugar_fecha = $_POST['lugar_fecha'];
    $folio_reporte = $_POST['folio_reporte'];
    $nombre_resguardo = $_POST['nombre_resguardo'];
    $cargo_resguardo = $_POST['cargo_resguardo'];
    $direccion_resguardo = $_POST['direccion_resguardo'];
    $telefono_resguardo = $_POST['telefono_resguardo'];
    $recibe_resguardo = $_POST['recibe_resguardo'];
    $entrega_resguardo = $_POST['entrega_resguardo'];
    $recibe_prestamos_bienes = $_POST['recibe_prestamos_bienes'];
    $matricula_coordinacion = $_POST['matricula_coordinacion'];
    $responsable_control_administrativo = $_POST['responsable_control_administrativo'];
    $matricula_administrativo = $_POST['matricula_administrativo'];
    $sql = "UPDATE documentos SET 
                nombre_del_trabajador = :nombre_del_trabajador,
                institucion = :institucion,
                adscripcion = :adscripcion,
                matricula = :matricula,
                identificacion = :identificacion,
                telefono = :telefono,
                area_de_salida = :area_de_salida,
                cantidad_bienes = :cantidad_bienes,
                naturaleza_bienes = :naturaleza_bienes,
                descripciones_json = :descripciones_json,
                proposito_bien = :proposito_bien,
                estado_bienes = :estado_bienes,
                devolucion_bienes = :devolucion_bienes,
                fecha_devolucion = :fecha_devolucion,
                responsable_entrega = :responsable_entrega,
                recibe_salida_bienes = :recibe_salida_bienes,
                lugar_fecha = :lugar_fecha,
                folio_reporte = :folio_reporte,
                nombre_resguardo = :nombre_resguardo,
                cargo_resguardo = :cargo_resguardo,
                direccion_resguardo = :direccion_resguardo,
                telefono_resguardo = :telefono_resguardo,
                recibe_resguardo = :recibe_resguardo,
                entrega_resguardo = :entrega_resguardo,
                recibe_prestamos_bienes = :recibe_prestamos_bienes,
                matricula_coordinacion = :matricula_coordinacion,
                responsable_control_administrativo = :responsable_control_administrativo,
                matricula_administrativo = :matricula_administrativo
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre_del_trabajador', $nombre_del_trabajador);
    $stmt->bindParam(':institucion', $institucion);
    $stmt->bindParam(':adscripcion', $adscripcion);
    $stmt->bindParam(':matricula', $matricula);
    $stmt->bindParam(':identificacion', $identificacion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':area_de_salida', $area_de_salida);
    $stmt->bindParam(':cantidad_bienes', $cantidad_bienes);
    $stmt->bindParam(':naturaleza_bienes', $naturaleza_bienes);
    $stmt->bindParam(':descripciones_json', $descripciones_json);
    $stmt->bindParam(':proposito_bien', $proposito_bien);
    $stmt->bindParam(':estado_bienes', $estado_bienes);
    $stmt->bindParam(':devolucion_bienes', $devolucion_bienes);
    $stmt->bindParam(':fecha_devolucion', $fecha_devolucion);
    $stmt->bindParam(':responsable_entrega', $responsable_entrega);
    $stmt->bindParam(':recibe_salida_bienes', $recibe_salida_bienes);
    $stmt->bindParam(':lugar_fecha', $lugar_fecha);
    $stmt->bindParam(':folio_reporte', $folio_reporte);
    $stmt->bindParam(':nombre_resguardo', $nombre_resguardo);
    $stmt->bindParam(':cargo_resguardo', $cargo_resguardo);
    $stmt->bindParam(':direccion_resguardo', $direccion_resguardo);
    $stmt->bindParam(':telefono_resguardo', $telefono_resguardo);
    $stmt->bindParam(':recibe_resguardo', $recibe_resguardo);
    $stmt->bindParam(':entrega_resguardo', $entrega_resguardo);
    $stmt->bindParam(':recibe_prestamos_bienes', $recibe_prestamos_bienes);
    $stmt->bindParam(':matricula_coordinacion', $matricula_coordinacion);
    $stmt->bindParam(':responsable_control_administrativo', $responsable_control_administrativo);
    $stmt->bindParam(':matricula_administrativo', $matricula_administrativo);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header("Location: mostrar.php");
        exit();
    } else {
        echo "Error al actualizar el registro";
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM documentos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($registro) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Formulario de Actualización</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap -->
            <style>
                .form-container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f9f9f9;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }
                .form-container h2 {
                    text-align: center;
                    margin-bottom: 20px;
                    color: #333;
                    font-family: 'Arial', sans-serif;
                }
                .form-group label {
                    font-weight: bold;
                    color: #555;
                }
                .form-group input[type="text"],
                .form-group input[type="date"],
                .form-group textarea {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #f1f1f1;
                }
                .form-group input[type="submit"] {
                    background-color: #4CAF50;
                    color: white;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .form-group input[type="submit"]:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <div class="form-container">
                <h2>Actualizar</h2>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $registro['id'] ?>">
                    <div class="form-group">
                        <label for="nombre_del_trabajador">Nombre del Trabajador:</label>
                        <input type="text" name="nombre_del_trabajador"  value="<?= $registro['nombre_del_trabajador'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="institucion">Institución:</label>
                        <input type="text" name="institucion"  value="<?= $registro['institucion'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="adscripcion">Adscripción:</label>
                        <input type="text" name="adscripcion"  value="<?= $registro['adscripcion'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="matricula">Matrícula:</label>
                        <input type="text" name="matricula"  value="<?= $registro['matricula'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="identificacion">Identificación:</label>
                        <input type="text" name="identificacion"  value="<?= $registro['identificacion'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono"  value="<?= $registro['telefono'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="area_de_salida">Área de Salida:</label>
                        <input type="text" name="area_de_salida"  value="<?= $registro['area_de_salida'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="cantidad_bienes">Cantidad de Bienes:</label>
                        <input type="text" name="cantidad_bienes"  value="<?= $registro['cantidad_bienes'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="naturaleza_bienes">Naturaleza de Bienes:</label>
                        <input type="text" name="naturaleza_bienes"  value="<?= $registro['naturaleza_bienes'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="descripciones_json">Descripciones:</label>
                        <textarea name="descripciones_json" ><?= implode(", ", json_decode($registro['descripciones_json'])) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="proposito_bien">Propósito del Bien:</label>
                        <input type="text" name="proposito_bien"  value="<?= $registro['proposito_bien'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="estado_bienes">Estado de Bienes:</label>
                        <input type="text" name="estado_bienes"  value="<?= $registro['estado_bienes'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="devolucion_bienes">Devolución de Bienes:</label>
                        <input type="text" name="devolucion_bienes"  value="<?= $registro['devolucion_bienes'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="fecha_devolucion">Fecha de Devolución:</label>
                        <input type="date" name="fecha_devolucion"  value="<?= $registro['fecha_devolucion'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="responsable_entrega">Responsable de Entrega:</label>
                        <input type="text" name="responsable_entrega"  value="<?= $registro['responsable_entrega'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="recibe_salida_bienes">Recibe Salida de Bienes:</label>
                        <input type="text" name="recibe_salida_bienes"  value="<?= $registro['recibe_salida_bienes'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="lugar_fecha">Lugar y Fecha:</label>
                        <input type="text" name="lugar_fecha"  value="<?= $registro['lugar_fecha'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="folio_reporte">Folio de Reporte:</label>
                        <input type="text" name="folio_reporte"  value="<?= $registro['folio_reporte'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="nombre_resguardo">Nombre del Resguardo:</label>
                        <input type="text" name="nombre_resguardo"  value="<?= $registro['nombre_resguardo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="cargo_resguardo">Cargo del Resguardo:</label>
                        <input type="text" name="cargo_resguardo"  value="<?= $registro['cargo_resguardo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="direccion_resguardo">Dirección del Resguardo:</label>
                        <input type="text" name="direccion_resguardo"  value="<?= $registro['direccion_resguardo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono_resguardo">Teléfono del Resguardo:</label>
                        <input type="text" name="telefono_resguardo"  value="<?= $registro['telefono_resguardo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="recibe_resguardo">Recibe Resguardo:</label>
                        <input type="text" name="recibe_resguardo"  value="<?= $registro['recibe_resguardo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="entrega_resguardo">Entrega Resguardo:</label>
                        <input type="text" name="entrega_resguardo"  value="<?= $registro['entrega_resguardo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="recibe_prestamos_bienes">Recibe Préstamos de Bienes:</label>
                        <input type="text" name="recibe_prestamos_bienes"  value="<?= $registro['recibe_prestamos_bienes'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="matricula_coordinacion">Matrícula de Coordinación:</label>
                        <input type="text" name="matricula_coordinacion"  value="<?= $registro['matricula_coordinacion'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="responsable_control_administrativo">Responsable Control Administrativo:</label>
                        <input type="text" name="responsable_control_administrativo"  value="<?= $registro['responsable_control_administrativo'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="matricula_administrativo">Matrícula Administrativo:</label>
                        <input type="text" name="matricula_administrativo"  value="<?= $registro['matricula_administrativo'] ?>">
                    </div>
                    <input type="submit" value="Actualizar Registro">
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Registro no encontrado";
    }
} else {
    echo "No se ha proporcionado un ID de registro";
}
?>
