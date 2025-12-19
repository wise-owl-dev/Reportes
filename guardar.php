<?php
ob_start();
use setasign\Fpdi\Tcpdf\Fpdi;
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
$data = [
    'nombre_del_trabajador' => isset($_POST['nombre_del_trabajador']) ? $_POST['nombre_del_trabajador'] : '',
    'institucion' => isset($_POST['institucion']) ? $_POST['institucion'] : '',
    'adscripcion' => isset($_POST['adscripcion']) ? $_POST['adscripcion'] : '',
    'matricula' => isset($_POST['matricula']) ? $_POST['matricula'] : '',
    'identificacion' => isset($_POST['identificacion']) ? $_POST['identificacion'] : '',
    'telefono' => isset($_POST['telefono']) ? $_POST['telefono'] : '',
    'area_de_salida' => isset($_POST['area_de_salida']) ? $_POST['area_de_salida'] : '',
    'cantidad_bienes' => isset($_POST['cantidad_bienes']) ? $_POST['cantidad_bienes'] : 0,
    'naturaleza_bienes' => isset($_POST['naturaleza_bienes']) ? $_POST['naturaleza_bienes'] : '',
    'descripciones' => isset($_POST['descripciones_json']) ? json_decode($_POST['descripciones_json'], true) : [],
    'proposito_bien' => isset($_POST['proposito_bien']) ? $_POST['proposito_bien'] : '',
    'estado_bienes' => isset($_POST['estado_bienes']) ? $_POST['estado_bienes'] : '',
    'devolucion_bienes' => isset($_POST['devolucion_bienes']) ? $_POST['devolucion_bienes'] : '',
    'fecha_devolucion' => isset($_POST['fecha_devolucion']) ? $_POST['fecha_devolucion'] : '',
    'responsable_entrega' => isset($_POST['responsable_entrega']) ? $_POST['responsable_entrega'] : '',
    'recibe_salida_bienes' => isset($_POST['recibe_salida_bienes']) ? $_POST['recibe_salida_bienes'] : '',
    'lugar_fecha' => isset($_POST['lugar_fecha']) ? $_POST['lugar_fecha'] : '',
    'folio_reporte' => isset($_POST['folio_reporte']) ? $_POST['folio_reporte'] : '',
    'nombre_resguardo' => isset($_POST['nombre_resguardo']) ? $_POST['nombre_resguardo'] : '',
    'cargo_resguardo' => isset($_POST['cargo_resguardo']) ? $_POST['cargo_resguardo'] : '',
    'direccion_resguardo' => isset($_POST['direccion_resguardo']) ? $_POST['direccion_resguardo'] : '',
    'telefono_resguardo' => isset($_POST['telefono_resguardo']) ? $_POST['telefono_resguardo'] : '',
    'recibe_resguardo' => isset($_POST['recibe_resguardo']) ? $_POST['recibe_resguardo'] : '',
    'entrega_resguardo' => isset($_POST['entrega_resguardo']) ? $_POST['entrega_resguardo'] : '',
    'recibe_prestamos_bienes' => isset($_POST['recibe_prestamos_bienes']) ? $_POST['recibe_prestamos_bienes'] : '',
    'matricula_coordinacion' => isset($_POST['matricula_coordinacion']) ? $_POST['matricula_coordinacion'] : '',
    'responsable_control_administrativo' => isset($_POST['responsable_control_administrativo']) ? $_POST['responsable_control_administrativo'] : '',
    'matricula_administrativo' => isset($_POST['matricula_administrativo']) ? $_POST['matricula_administrativo'] : '',
    'departamento_per' => isset($_POST['departamento_per']) ? $_POST['departamento_per'] : '',
];
$stmt = $conn->prepare("INSERT INTO documentos (
    nombre_del_trabajador, institucion, adscripcion, matricula, identificacion,
    telefono, area_de_salida, cantidad_bienes, naturaleza_bienes, descripciones_json, proposito_bien, 
    estado_bienes, devolucion_bienes, fecha_devolucion, responsable_entrega, recibe_salida_bienes, lugar_fecha,
    folio_reporte, nombre_resguardo, cargo_resguardo, direccion_resguardo, telefono_resguardo, recibe_resguardo, 
    entrega_resguardo, recibe_prestamos_bienes, matricula_coordinacion, responsable_control_administrativo, matricula_administrativo
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param(
    "ssssssssssssssssssssssssssss", 
    $data['nombre_del_trabajador'], $data['institucion'], $data['adscripcion'], $data['matricula'], $data['identificacion'],
    $data['telefono'], $data['area_de_salida'], $data['cantidad_bienes'], $data['naturaleza_bienes'], json_encode($data['descripciones']), $data['proposito_bien'], 
    $data['estado_bienes'], $data['devolucion_bienes'], $data['fecha_devolucion'], $data['responsable_entrega'], $data['recibe_salida_bienes'], $data['lugar_fecha'],
    $data['folio_reporte'], $data['nombre_resguardo'], $data['cargo_resguardo'], $data['direccion_resguardo'], $data['telefono_resguardo'], $data['recibe_resguardo'], 
    $data['entrega_resguardo'], $data['recibe_prestamos_bienes'], $data['matricula_coordinacion'], $data['responsable_control_administrativo'], $data['matricula_administrativo']
);
if ($stmt->execute()) {
    require __DIR__ . '/vendor/autoload.php';
class PdfFiller {
    private $pdf;
    public function __construct() {
        $this->pdf = new Fpdi();
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(10, 10, 10); 
        $this->pdf->SetAutoPageBreak(true, 10);
    }
    private function fillCommonFields($data) {
        $this->pdf->SetMargins(10, 10, 10); 
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->SetMargins(10, 10, 10); 
        $this->pdf->SetAutoPageBreak(true, 10); 
        $this->pdf->SetFont('helvetica', '', 10); 
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(26, 50);
        // Limitado a 27 caracteres 
        $nombre_limitado = mb_strimwidth($data['nombre_del_trabajador'], 0, 27, '...');
        $this->pdf->Write(10, $nombre_limitado);
        $this->pdf->SetXY(119, 50);
        $this->pdf->Write(10, $data['institucion']);
        // Limitado a 59 caracteres
        $adscripcion_limitado = mb_strimwidth($data['adscripcion'], 0, 55, '...');
        $this->pdf->SetXY(40, 56);
        $this->pdf->Write(10, $adscripcion_limitado);
        $this->pdf->SetXY(173, 56);
        $this->pdf->Write(10, $data['matricula']);
        
        $this->pdf->SetXY(44, 61);
        $this->pdf->Write(10, $data['identificacion']);
        $this->pdf->SetXY(171, 61);
        $this->pdf->Write(10, $data['telefono']);
        $this->pdf->SetXY(72, 73);
        $this->pdf->Write(10, $data['area_de_salida']);
        $this->pdf->SetXY(30, 95);
        $this->pdf->Write(10, $data['cantidad_bienes']);
        $this->pdf->SetXY(65, 95);
        $this->pdf->Write(10, $data['naturaleza_bienes']);
    $xPosBC = 14;   
    $yPosBC = 232;
    $xPosBMC = 14;   
    $yPosBMC = 241;
    $xPosBMNC = 112;  
    $yPosBMNC = 232;
    $xPosBPS = 112;  
    $yPosBPS = 241;
  $rectWidth = 15;
  $rectHeight = 5;
  $this->pdf->SetFillColor(173, 216, 230);
  $this->pdf->SetAlpha(0.5); 
  if ($data['naturaleza_bienes'] == 'BC') {
      $this->pdf->Rect($xPosBC, $yPosBC, $rectWidth, $rectHeight, 'F'); 
  } elseif ($data['naturaleza_bienes'] == 'BMC') {
      $this->pdf->Rect($xPosBMC, $yPosBMC, $rectWidth, $rectHeight, 'F');
  } elseif ($data['naturaleza_bienes'] == 'BMNC') {
      $this->pdf->Rect($xPosBMNC, $yPosBMNC, $rectWidth, $rectHeight, 'F');
  } elseif ($data['naturaleza_bienes'] == 'BPS') {
      $this->pdf->Rect($xPosBPS, $yPosBPS, $rectWidth, $rectHeight, 'F');
  }
  $this->pdf->SetAlpha(1.0);
        $this->pdf->SetXY(42, 129);
        $this->pdf->Write(10, $data['proposito_bien']);
        $this->pdf->SetXY(115, 137);
        $this->pdf->Write(10, $data['estado_bienes']);
        $this->pdf->SetXY(195, 204); 
        $lugarFechaDefault = "Oaxaca de Juárez,Oaxaca a: "; 
        $this->pdf->write(8, $lugarFechaDefault . $data['lugar_fecha']); 
        var_dump($data);
$xPosSi = 77; 
$yPos = 144;  
$xPosNo = 92; 
$this->pdf->SetXY($xPosSi, $yPos);
$this->pdf->Write(10, ''); 
$this->pdf->SetXY($xPosNo, $yPos);
$this->pdf->Write(10, ''); 
if ($data['devolucion_bienes'] == 'si') {
    $this->pdf->SetXY($xPosSi, $yPos);
    $this->pdf->Write(15, 'X'); 
} elseif ($data['devolucion_bienes'] == 'no') {
    $this->pdf->SetXY($xPosNo, $yPos);
    $this->pdf->Write(15, 'X'); 
}
if ($data['devolucion_bienes'] == 'si' && !empty($data['fecha_devolucion'])) {
    $this->pdf->SetXY(115, 146); 
    $this->pdf->Write(10, $data['fecha_devolucion']);
}  
        $this->pdf->SetXY(120, 191);
        $this->pdf->Write(10, $data['recibe_salida_bienes']);
    }
    public function fillForm1($file, $data) {
        $this->pdf->AddPage();
        $this->pdf->SetMargins(10, 10, 10); 
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->setSourceFile($file);
        $this->pdf->useTemplate($this->pdf->importPage(1));
        $this->fillCommonFields($data);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(195, 181);
        $this->pdf->Write(10, $data['responsable_entrega']);
        $yPosition = 98; 
        $lineHeight = 5; 
        $lineLength = 46; 
        $totalLines = 0; 
        foreach ($data['descripciones'] as $descripcion) {
            $lines = (strlen($descripcion) <= $lineLength) ? 1 : ceil(strlen($descripcion) / $lineLength);
            $totalLines += $lines;
        }
        if ($totalLines > 5) {
            $this->pdf->SetXY(98,95);
            $this->pdf->Write(10, 'Información en el anexo');
            $this->addDescriptionTable($data['descripciones']);
        } else {
            foreach ($data['descripciones'] as $descripcion) {
                $this->pdf->SetXY(98, $yPosition);
                if (strlen($descripcion) <= $lineLength) {
                    $this->pdf->Write($lineHeight, $descripcion);
                    $yPosition += $lineHeight; 
                } else {
                    $lines = ceil(strlen($descripcion) / $lineLength);
                    $this->pdf->MultiCell(100, $lineHeight, $descripcion, 0, 'L');
                    $yPosition += $lineHeight * $lines; 
                }
            }
        }
    }
    public function fillForm2($file, $data) {
        $this->pdf->AddPage();
        $this->pdf->SetMargins(10, 10, 10); 
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->setSourceFile($file);
        $this->pdf->useTemplate($this->pdf->importPage(1));
        $this->pdf->SetFont('helvetica', '', 8); 
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(113, 56);
        $this->pdf->Write(7, $data['lugar_fecha']); 
        $this->pdf->SetXY(178, 55);
        $this->pdf->Write(8, $data['folio_reporte']); 
        $this->pdf->SetXY(55, 72);
        $this->pdf->Write(8, $data['institucion']); 
        $this->pdf->SetXY(55, 76);
        $this->pdf->Write(8, $data['nombre_resguardo']); 
        $this->pdf->SetXY(55, 80);   
        $this->pdf->Write(8, $data['cargo_resguardo']); 
        $this->pdf->SetXY(35, 110);
        $this->pdf->Write(8, $data['cantidad_bienes']);
        $this->pdf->SetXY(55, 84);
        $this->pdf->Write(8, $data['direccion_resguardo']); 
        $this->pdf->SetXY(55, 88);
        $this->pdf->Write(8, $data['telefono_resguardo']); 
        $this->pdf->SetXY(40, 228);
        $this->pdf->Write(8, $data['nombre_resguardo']); 
        $this->pdf->SetXY(25, 232);   
        $this->pdf->Write(8, $data['cargo_resguardo']); 
        $this->pdf->SetXY(140, 228);
        $this->pdf->Write(8, $data['recibe_resguardo']);
        $this->pdf->SetXY(125, 232);   
        $this->pdf->Write(8, $data['entrega_resguardo']); 
        if (count($data['descripciones']) > 3) {
            $this->pdf->SetXY(127, 109);
            $this->pdf->Write(4, 'Información en el anexo');
            $this->pdf->SetXY(127, 116);
            $this->pdf->Write(4, 'Información en el anexo');
            $this->pdf->SetXY(127, 123);
            $this->pdf->Write(4, 'Información en el anexo');
            var_dump($data);
            $this->addDescriptionTable($data['descripciones']);
        } else {
            $this->displayDescriptionsGrouped($data['descripciones']);
        }
    }
    private function displayDescriptionsGrouped($descripciones) {
        $this->pdf->SetFont('helvetica', '', 8);
        $yPos = 109; 
        $conceptos = [];
        $marcas = [];
        $numerosSerie = [];
        foreach ($descripciones as $descripcion) { 
            $parts = explode(' ', $descripcion, 3);
            $conceptos[] = isset($parts[0]) ? $parts[0] : ''; 
            $marcas[] = isset($parts[1]) ? $parts[1] : '';   
            $numerosSerie[] = isset($parts[2]) ? $parts[2] : ''; 
        }
        $conceptosTexto = implode(', ', $conceptos);
        $marcasTexto = implode(', ', $marcas);
        $numerosSerieTexto = implode(', ', $numerosSerie);
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(5, '' . $conceptosTexto);
        $yPos += 5; 
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(8, '' . $marcasTexto);
        $yPos += 5; 
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(13, '' . $numerosSerieTexto);
    }
    private function addDescriptionTable($descripciones) {
        $this->pdf->AddPage();
        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);
        $colWidths = [20, 50, 50, 50]; 
        $tableWidth = array_sum($colWidths); 
        $pageWidth = $this->pdf->GetPageWidth();
        $xPos = ($pageWidth - $tableWidth) / 2; 
        $this->pdf->SetXY($xPos, 20);
        $this->pdf->SetFillColor(230, 230, 230);
        $this->pdf->SetDrawColor(50, 50, 50);
        $this->pdf->Cell($colWidths[0], 10, 'No.', 1, 0, 'C', true);
        $this->pdf->Cell($colWidths[1], 10, 'Marca', 1, 0, 'C', true);
        $this->pdf->Cell($colWidths[2], 10, 'Concepto', 1, 0, 'C', true);
        $this->pdf->Cell($colWidths[3], 10, 'Número de Serie', 1, 1, 'C', true);
        $this->pdf->SetFont('helvetica', '', 8);
$this->pdf->SetFillColor(255, 255, 255);
foreach ($descripciones as $i => $descripcion) {
    $parts = explode(' ', $descripcion, 3);
    $concepto = isset($parts[0]) ? $parts[0] : '';
    $marca = isset($parts[1]) ? $parts[1] : '';
    $numeroSerie = isset($parts[2]) ? $parts[2] : '';
    
            $this->pdf->SetX($xPos); 
            $this->pdf->Cell($colWidths[0], 10, $i + 1, 1, 0, 'C');
            $this->pdf->Cell($colWidths[1], 10, $marca, 1, 0, 'L');
            $this->pdf->Cell($colWidths[2], 10, $concepto, 1, 0, 'L');
            $this->pdf->Cell($colWidths[3], 10, $numeroSerie, 1, 1, 'L');
        }
    }
    public function fillForm3($file, $data) {
        $this->pdf->SetMargins(10, 10, 10); 
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->AddPage();
        $this->pdf->setSourceFile($file);
        $this->pdf->useTemplate($this->pdf->importPage(1));
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(110, 20);
        $ads_limitado = mb_strimwidth($data['adscripcion'],0,36,'...');
        $this->pdf->write(10, $ads_limitado);
        $this->pdf->SetXY (77, 51);
        $this->pdf->Write(10, $data['naturaleza_bienes']);
    $xPosBC = 3;
    $yPosBC = 231;
    $xPosBMC = 3;
    $yPosBMC = 235;
    $xPosBMNC = 108;
    $yPosBMNC = 231;
    $xPosBPS = 108;
    $yPosBPS = 235;
  $rectWidth = 13;
  $rectHeight = 3;
  $this->pdf->SetFillColor(173, 216, 230); 
  $this->pdf->SetAlpha(0.4); 
  if ($data['naturaleza_bienes'] == 'BC') {
      $this->pdf->Rect($xPosBC, $yPosBC, $rectWidth, $rectHeight, 'F');
  } elseif ($data['naturaleza_bienes'] == 'BMC') {
      $this->pdf->Rect($xPosBMC, $yPosBMC, $rectWidth, $rectHeight, 'F');
  } elseif ($data['naturaleza_bienes'] == 'BMNC') {
      $this->pdf->Rect($xPosBMNC, $yPosBMNC, $rectWidth, $rectHeight, 'F');
  } elseif ($data['naturaleza_bienes'] == 'BPS') {
      $this->pdf->Rect($xPosBPS, $yPosBPS, $rectWidth, $rectHeight, 'F');
  }
  $this->pdf->SetAlpha(1.0);
        $this->pdf->SetXY(26, 51);
        $this->pdf->Write(10, $data['cantidad_bienes']);
        $this->pdf->SetXY(68, 153);
        $this->pdf->Write(10, $data['estado_bienes']);
        $this->pdf->SetXY(8, 194);
        $this->pdf->Write(10, $data['recibe_prestamos_bienes']);
        $this->pdf->SetXY(16, 203);
        $this->pdf->Write(10, $data['matricula_coordinacion']);
        $this->pdf->SetXY(108, 194);
        $this->pdf->Write(10, $data['responsable_control_administrativo']);
        $this->pdf->SetXY(110, 203);
        $this->pdf->Write(10, $data['matricula_administrativo']);
        $this->pdf->SetXY(40, 214);
        $this->pdf->Write(10, $data['lugar_fecha']);
        $this->pdf->SetXY(110,167);
        $this->pdf->Write(10,$data['departamento_per']);
        var_dump($data);
        $this->displayDescriptionsLineByLine($data['descripciones']);
    }
    private function displayDescriptionsLineByLine($descripciones) {
        $this->pdf->SetFont('helvetica', '', 10);
        $yPos = 54; 
        foreach ($descripciones as $descripcion) {
            $this->pdf->SetXY(130, $yPos); 
            $this->pdf->Write(5, $descripcion); 
            $yPos += 4; 
        }
    }
    public function output($filename = 'documento_combinado.pdf') {
        $this->pdf->Output($filename, 'I');
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentos = [
        1 => 'D:\\xampp\\htdocs\\Reportes\\salidaBiene.pdf',
        2 => 'D:\\xampp\\htdocs\\Reportes\\resguardo1.pdf',
        3 => 'D:\\xampp\\htdocs\\Reportes\\prestamo1.pdf'
    ];
    $errors = [];
    $pdfFiller = new PdfFiller();
    if (isset($_POST['tipo_documento'])) {
        foreach ($_POST['tipo_documento'] as $opcion) {
            if (array_key_exists($opcion, $documentos)) {
                $file = $documentos[$opcion];
                if (!file_exists($file)) {
                    $errors[] = "El archivo '{$file}' no se encuentra en la ruta especificada.";
                    continue;
                }
                var_dump($data);
                try {
                    switch ($opcion) {
                        case 1:
                            $pdfFiller->fillForm1($file, $data);
                            break;
                        case 2:
                            $pdfFiller->fillForm2($file, $data);
                            break;
                        case 3:
                            $pdfFiller->fillForm3($file, $data);
                            break;
                    }
                } catch (Exception $e) {
                    $errors[] = "Error al procesar el archivo '{$file}': " . $e->getMessage();
                }
            }
        }
        if (empty($errors)) {
            ob_end_clean();
            $pdfFiller->output('documento_combinado.pdf');
        } else {
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
        }
    } else {
        echo 'No se seleccionó ningún tipo de documento.';
    }
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
}
?>