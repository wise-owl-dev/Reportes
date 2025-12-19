<?php
ob_start();
use setasign\Fpdi\Tcpdf\Fpdi;
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
    public function generateFromTemplate($file) {
        if (file_exists($file)) {
            $this->pdf->AddPage();  
            $this->pdf->setSourceFile($file);  
            $templateId = $this->pdf->importPage(1);  
            $this->pdf->useTemplate($templateId, 0, 0, null, null, true);  
        } else {
            die("El archivo PDF no se encuentra en la ruta especificada: " . $file);
        }
    }
    //Funcion para el primer formato 
    public function fillCommonFields($data) {
        $this->pdf->SetFont('Helvetica', '', 10);  
        $this->pdf->SetXY(23, 50);
        $nombre_limitado = mb_strimwidth($data['nombre_del_trabajador'], 0, 27, '...');
        $this->pdf->Write(10, $nombre_limitado);
        $this->pdf->SetXY(115, 52);
        $institucion_limitado = mb_strimwidth($data['institucion'], 0, 38, '...');
        $this->pdf->Write(5, $institucion_limitado);
        $adscripcion_limitado = mb_strimwidth($data['adscripcion'], 0, 42, '...');
        $this->pdf->SetXY(38, 56);
        $this->pdf->Write(10, $adscripcion_limitado);
        $this->pdf->SetXY(170, 58);
        $this->pdf->Write(6, $data['matricula']);
        $this->pdf->SetXY(65, 95);
        $this->pdf->Write(10, $data['naturaleza_bienes']);
        $identificacion_limitado = mb_strimwidth($data['identificacion'], 0, 45, '..');
        $this->pdf->SetXY(44, 61);
        $this->pdf->Write(11, $identificacion_limitado);
        $this->pdf->SetXY(167, 61);
        $this->pdf->Write(11, $data['telefono']);
        $this->pdf->SetXY(55, 73);
        $this->pdf->Write(10, $data['area_de_salida']);
        $this->pdf->SetXY(30, 95);
        $this->pdf->Write(10, $data['cantidad_bienes']);
        $this->pdf->SetXY(42, 129);
        $this->pdf->Write(10, $data['proposito_bien']);
        $this->pdf->SetXY(115, 137);
        $this->pdf->Write(10, $data['estado_bienes']);
        $this->handleNatureOfGoods($data['naturaleza_bienes']);
        $this->handleReturns($data['devolucion_bienes'], $data['fecha_devolucion']);
        $this->pdf->SetXY(120, 191);
        $this->pdf->Write(10, $data['recibe_salida_bienes']);
        $this->pdf->SetXY(195, 204); 
        $lugarFechaDefault = "Oaxaca de Juárez, Oaxaca a: "; 
        $this->pdf->Write(8, $lugarFechaDefault . $data['lugar_fecha']); 
    }
    private function handleNatureOfGoods($naturaleza) {
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
        switch ($naturaleza) {
            case 'BC':
                $this->pdf->Rect($xPosBC, $yPosBC, $rectWidth, $rectHeight, 'F'); 
                break;
            case 'BMC':
                $this->pdf->Rect($xPosBMC, $yPosBMC, $rectWidth, $rectHeight, 'F');
                break;
            case 'BMNC':
                $this->pdf->Rect($xPosBMNC, $yPosBMNC, $rectWidth, $rectHeight, 'F');
                break;
            case 'BPS':
                $this->pdf->Rect($xPosBPS, $yPosBPS, $rectWidth, $rectHeight, 'F');
                break;
        }
        $this->pdf->SetAlpha(1.0);
    }
    private function handleReturns($devolucion, $fechaDevolucion) {
        $xPosSi = 77; 
        $yPos = 144;  
        $xPosNo = 92; 
        if ($devolucion == 'si') {
            $this->pdf->SetXY($xPosSi, $yPos);
            $this->pdf->Write(15, 'X'); 
        } elseif ($devolucion == 'no') {
            $this->pdf->SetXY($xPosNo, $yPos);
            $this->pdf->Write(15, 'X'); 
        }
        if ($devolucion == 'si' && !empty($fechaDevolucion)) {
            $this->pdf->SetXY(115, 146); 
            $this->pdf->Write(10, $fechaDevolucion);
        }
    }
    public function fillForm1($file, $data) {
        $this->pdf->setSourceFile($file);
        $this->fillCommonFields($data);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(199, 181);
        $this->pdf->Write(10, $data['responsable_entrega']);
        $this->handleDescriptions($data['descripciones']);
    }
    private function handleDescriptions($descripciones) {
        $yPosition = 98; 
        $lineHeight = 5; 
        $lineLength = 46; 
        foreach ($descripciones as $descripcion) {
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
    //Funcion para imprimir el segundo formato
    public function fillForm2($file, $data) {
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(116, 55); 
        $lugarFechaDefault = "Oaxaca de Juárez a: "; 
        $this->pdf->Write(8, $lugarFechaDefault . $data['lugar_fecha']); 
        $this->pdf->SetXY(176, 54);
        $this->pdf->Write(10, $data['folio_reporte']); 
        $this->pdf->SetXY(55, 71);
        $this->pdf->Write(10, $data['institucion']); 
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
        $this->pdf->SetXY(132, 228);
        $this->pdf->Write(10, $data['recibe_resguardo']);
        $this->pdf->SetXY(115, 232);   
        $this->pdf->Write(10, $data['entrega_resguardo']); 
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
        $descripcionesLargas = []; 
        foreach ($descripciones as $descripcion) { 
            // Si la descripción es mayor a 42 caracteres, se envía a la tabla adicional
            if (strlen($descripcion) > 42) {
                $descripcionesLargas[] = $descripcion;
                $conceptos[] = 'Información en el anexo';
                $marcas[] = 'Información en el anexo';
                $numerosSerie[] = 'Información en el anexo';
                continue; // Saltar al siguiente
            }
    
            // Divide en partes si cumple el límite
            $parts = explode(' ', $descripcion, 3);
            $conceptos[] = isset($parts[0]) ? $parts[0] : ''; 
            $marcas[] = isset($parts[1]) ? $parts[1] : '';   
            $numerosSerie[] = isset($parts[2]) ? $parts[2] : ''; 
        }
    
        // Combinar textos de conceptos, marcas y números de serie
        $conceptosTexto = implode(', ', $conceptos);
        $marcasTexto = implode(', ', $marcas);
        $numerosSerieTexto = implode(', ', $numerosSerie);
    
        // Renderizar en el PDF
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(5, '' . $conceptosTexto);
        $yPos += 5; 
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(8, '' . $marcasTexto);
        $yPos += 5; 
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(13, '' . $numerosSerieTexto);
    
        // Llamada para agregar la tabla con descripciones largas si existen
        if (!empty($descripcionesLargas)) {
            $this->addDescriptionTable($descripcionesLargas);
        }
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
    
            // Función para dividir en líneas de 26 caracteres
            $conceptoLines = str_split($concepto, 26);
            $marcaLines = str_split($marca, 26);
            $numeroSerieLines = str_split($numeroSerie, 26);
            $maxLines = max(count($conceptoLines), count($marcaLines), count($numeroSerieLines));
    
            // Renderizar cada línea en su propia fila
            for ($j = 0; $j < $maxLines; $j++) {
                $this->pdf->SetX($xPos); 
                $this->pdf->Cell($colWidths[0], 10, $j === 0 ? $i + 1 : '', 1, 0, 'C'); // Solo muestra el número en la primera línea
                $this->pdf->Cell($colWidths[1], 10, isset($marcaLines[$j]) ? $marcaLines[$j] : '', 1, 0, 'L');
                $this->pdf->Cell($colWidths[2], 10, isset($conceptoLines[$j]) ? $conceptoLines[$j] : '', 1, 0, 'L');
                $this->pdf->Cell($colWidths[3], 10, isset($numeroSerieLines[$j]) ? $numeroSerieLines[$j] : '', 1, 1, 'L');
            }
        }
    }
     
    
    //Funcion para imprimir el tercer formato 
    public function fillForm3($file, $data) {
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(110, 20);  
        $this->pdf->Write(10, $data['adscripcion']); 
        $this->pdf->SetXY(77, 51);
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
            $lineas = str_split($descripcion, 29);
            
            foreach ($lineas as $linea) {
                $this->pdf->SetXY(129, $yPos); 
                $this->pdf->Write(5, $linea); 
                $yPos += 4; 
            }
            $yPos += 0;
        }
    }
    
    public function output($filename = 'documento.pdf') {
        ob_end_clean();
        $this->pdf->Output($filename, 'I');   
       
    }
}
$dsn = 'mysql:host=localhost;dbname=reportes;charset=utf8mb4';
$username = 'root';
$password = 'admin';
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT nombre_del_trabajador, institucion, adscripcion, matricula, identificacion, telefono, area_de_salida, cantidad_bienes, naturaleza_bienes, descripciones_json, proposito_bien, estado_bienes, devolucion_bienes, fecha_devolucion, responsable_entrega, recibe_salida_bienes, lugar_fecha, folio_reporte, nombre_resguardo, cargo_resguardo,direccion_resguardo,telefono_resguardo,recibe_resguardo,entrega_resguardo,recibe_prestamos_bienes,matricula_coordinacion,responsable_control_administrativo,matricula_administrativo FROM documentos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $data['descripciones'] = json_decode($data['descripciones_json'], true);
            $files = [
                'D:\\xampp\\htdocs\\Reportes\\salidaBiene.pdf',
                'D:\\xampp\\htdocs\\Reportes\\resguardo1.pdf',
                'D:\\xampp\\htdocs\\Reportes\\prestamo1.pdf'
            ];
            $pdfFiller = new PdfFiller();
            $pdfFiller->generateFromTemplate($files[0]);
            $pdfFiller->fillForm1($files[0], $data); 
            $pdfFiller->generateFromTemplate($files[1]);
            $pdfFiller->fillForm2($files[1], $data); 
            $pdfFiller->generateFromTemplate($files[2]);
            $pdfFiller->fillForm3($files[2], $data); 
            $pdfFiller->output('documento.pdf');
        } else {
            echo "No se encontraron datos para el ID especificado.";
        }
    } else {
        echo "No se ha especificado ningún ID.";
    }
} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage();
}

?>
