<?php
//borrador de clase para generar los pdf,dejó de funcionar por que no guarda los elementos en la base de datos pero puede funcionar para recordar como es el funcionamiento.
//puede servir como base para futuros cambios de plantilla de documentos o agregar mas
require __DIR__ . '/vendor/autoload.php';
use setasign\Fpdi\Tcpdf\Fpdi;
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

        // Campos comunes
        $this->pdf->SetXY(26, 50);
        $this->pdf->Write(10, $data['nombre_del_trabajador']);
        $this->pdf->SetXY(119, 50);
        $this->pdf->Write(10, $data['institucion']);
        $this->pdf->SetXY(40, 56);
        $this->pdf->Write(10, $data['adscripcion']);
        $this->pdf->SetXY(163, 56);
        $this->pdf->Write(10, $data['matricula']);
        $this->pdf->SetXY(44, 62);
        $this->pdf->Write(10, $data['identificacion']);
        $this->pdf->SetXY(165, 62);
        $this->pdf->Write(10, $data['telefono']);
        $this->pdf->SetXY(72, 73);
        $this->pdf->Write(10, $data['area_de_salida']);
        $this->pdf->SetXY(30, 96);
        $this->pdf->Write(10, $data['cantidad_bienes']);
        $this->pdf->SetXY(60, 96);
        $this->pdf->Write(10, $data['naturaleza_bienes']);
        $this->pdf->SetXY(42, 130);
        $this->pdf->Write(10, $data['proposito_bien']);
        $this->pdf->SetXY(115, 138);
        $this->pdf->Write(10, $data['estado_bienes']);
        $this->pdf->SetXY(195,205);
        $this->pdf->write(8,$data['lugar_fecha']);

    // Coordenadas para las opciones "Sí" y "No"
$xPosSi = 77; 
$yPos = 145;  
$xPosNo = 92; 
// Limpia posiciones previas
$this->pdf->SetXY($xPosSi, $yPos);
$this->pdf->Write(10, ''); 
$this->pdf->SetXY($xPosNo, $yPos);
$this->pdf->Write(10, ''); 
// Marca la opción seleccionada según el formulario
if ($data['devolucion_bienes'] == 'si') {
    $this->pdf->SetXY($xPosSi, $yPos);
    $this->pdf->Write(15, 'X'); // Marca la opción "Sí"
} elseif ($data['devolucion_bienes'] == 'no') {
    $this->pdf->SetXY($xPosNo, $yPos);
    $this->pdf->Write(15, 'X'); // Marca la opción "No"
}
// Escribe la fecha de devolución si existe y se seleccionó "Sí"
if ($data['devolucion_bienes'] == 'si' && !empty($data['fecha_devolucion'])) {
    $this->pdf->SetXY(99, 145); 
    $this->pdf->Write(10, $data['fecha_devolucion']);
}
        $this->pdf->SetXY(120, 193);
        $this->pdf->Write(10, $data['recibe_salida_bienes']);
    } // Cierre de fillCommonFields
    public function fillForm1($file, $data) {
        $this->pdf->AddPage();
        $this->pdf->SetMargins(10, 10, 10); // Márgenes izquierdo, superior y derecho
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->setSourceFile($file);
        $this->pdf->useTemplate($this->pdf->importPage(1));
        $this->fillCommonFields($data);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(195, 183);
        $this->pdf->Write(10, $data['responsable_entrega']);

        $yPosition = 96;
        $descripcionCount = count($data['descripciones']);
    
        if ($descripcionCount > 5) {
            $this->pdf->SetXY(97, $yPosition);
            $this->pdf->Write(10, 'Información en el anexo');
        } else {
            foreach ($data['descripciones'] as $index => $descripcion) {
                $this->pdf->SetXY(97, $yPosition);
                $this->pdf->Write(10, $descripcion);
                $yPosition += 5;
            }
        }
    
        if ($descripcionCount > 5) {
            $this->addDescriptionTable($data['descripciones']);
        }
    }

    public function fillForm2($file, $data) {
        $this->pdf->AddPage();
        $this->pdf->SetMargins(10, 10, 10); // Márgenes izquierdo, superior y derecho
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->setSourceFile($file);
        $this->pdf->useTemplate($this->pdf->importPage(1));
        $this->pdf->SetFont('helvetica', '', 8); 
        $this->pdf->SetTextColor(0, 0, 0);
        
        // Campos en la primera página
        $this->pdf->SetXY(113, 55);
        $this->pdf->Write(7, $data['lugar_fecha']); 
    
        $this->pdf->SetXY(178, 55);
        $this->pdf->Write(8, $data['folio_reporte']); 
        
        $this->pdf->SetXY(55, 71);
        $this->pdf->Write(8, $data['institucion']); 
    
        $this->pdf->SetXY(55, 75);
        $this->pdf->Write(8, $data['nombre_resguardo']); 
    
        $this->pdf->SetXY(55, 79);   
        $this->pdf->Write(8, $data['cargo_resguardo']); 
    
        $this->pdf->SetXY(35, 110);
        $this->pdf->Write(8, $data['cantidad_bienes']);
    
        $this->pdf->SetXY(55, 83);
        $this->pdf->Write(8, $data['direccion_resguardo']); 
    
        $this->pdf->SetXY(55, 87);
        $this->pdf->Write(8, $data['telefono_resguardo']); 
    
        $this->pdf->SetXY(40, 228);
        $this->pdf->Write(8, $data['nombre_resguardo']); 
    
        $this->pdf->SetXY(25, 232);   
        $this->pdf->Write(8, $data['cargo_resguardo']); 
    
        $this->pdf->SetXY(140, 228);
        $this->pdf->Write(8, $data['recibe_resguardo']);
    
        $this->pdf->SetXY(125, 232);   
        $this->pdf->Write(8, $data['entrega_resguardo']); 
    
        // Verificar el número de descripciones
        if (count($data['descripciones']) > 3) {
            // Mostrar mensaje "Información en el anexo"
            $this->pdf->SetXY(127, 109);
            $this->pdf->Write(4, 'Información en el anexo');
    
            $this->pdf->SetXY(127, 116);
            $this->pdf->Write(4, 'Información en el anexo');
    
            $this->pdf->SetXY(127, 123);
            $this->pdf->Write(4, 'Información en el anexo');
    
            // Agregar una nueva página con una tabla para todas las descripciones
            $this->addDescriptionTable($data['descripciones']);
        } else {
            // Mostrar descripciones agrupadas por tipo en una sola línea
            $this->displayDescriptionsGrouped($data['descripciones']);
        }
    }
    
    // Función para mostrar las descripciones agrupadas por tipo
    private function displayDescriptionsGrouped($descripciones) {
        $this->pdf->SetFont('helvetica', '', 8);
        $yPos = 109; // Posición Y inicial para las descripciones
    
        // Variables para almacenar los conceptos, marcas y números de serie
        $conceptos = [];
        $marcas = [];
        $numerosSerie = [];
    
        foreach ($descripciones as $descripcion) {
            // Dividir cada descripción en partes
            $parts = explode(' ', $descripcion, 3);
            $conceptos[] = $parts[0] ?? ''; // Guardar Concepto
            $marcas[] = $parts[1] ?? '';    // Guardar Marca
            $numerosSerie[] = $parts[2] ?? ''; // Guardar Número de Serie
        }
    
        // Concatenar los valores separados por coma
        $conceptosTexto = implode(', ', $conceptos);
        $marcasTexto = implode(', ', $marcas);
        $numerosSerieTexto = implode(', ', $numerosSerie);
    
        // Mostrar los textos en el PDF
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(5, '' . $conceptosTexto);
        $yPos += 5; // Incrementar la posición Y
    
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(8, '' . $marcasTexto);
        $yPos += 5; // Incrementar la posición Y
    
        $this->pdf->SetXY(128, $yPos); 
        $this->pdf->Write(13, '' . $numerosSerieTexto);
    }
    
    private function addDescriptionTable($descripciones) {
        $this->pdf->AddPage();
        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);
    
        // Definir los anchos de las columnas
        $colWidths = [20, 50, 50, 50]; // Ancho de cada columna
        $tableWidth = array_sum($colWidths); // Ancho total de la tabla
    
        // Obtener el ancho de la página y calcular la posición X para centrar la tabla
        $pageWidth = $this->pdf->GetPageWidth();
        $xPos = ($pageWidth - $tableWidth) / 2; // Posición X centrada
    
        // Establecer la posición inicial
        $this->pdf->SetXY($xPos, 20); // 20 es la posición Y inicial
    
        // Encabezados de tabla
        $this->pdf->SetFillColor(230, 230, 230);
        $this->pdf->SetDrawColor(50, 50, 50);
        $this->pdf->Cell($colWidths[0], 10, 'No.', 1, 0, 'C', true);
        $this->pdf->Cell($colWidths[1], 10, 'Marca', 1, 0, 'C', true);
        $this->pdf->Cell($colWidths[2], 10, 'Concepto', 1, 0, 'C', true);
        $this->pdf->Cell($colWidths[3], 10, 'Número de Serie', 1, 1, 'C', true);
    
        // Filas de tabla
        $this->pdf->SetFont('helvetica', '', 8);
        $this->pdf->SetFillColor(255, 255, 255);
        foreach ($descripciones as $i => $descripcion) {
            $parts = explode(' ', $descripcion, 3);
            $concepto = $parts[0] ?? '';
            $marca = $parts[1] ?? '';
            $numeroSerie = $parts[2] ?? '';
    
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
        $this->pdf->Write(10, $data['adscripcion']); 
    
        $this->pdf->SetXY(80, 51);
        $this->pdf->Write(10, $data['naturaleza_bienes']);
    
        $this->pdf->SetXY(30, 51);
        $this->pdf->Write(10, $data['cantidad_bienes']);
    
        $this->pdf->SetXY(68, 150);
        $this->pdf->Write(10, $data['estado_bienes']);
    
        $this->pdf->SetXY(12, 190);
        $this->pdf->Write(10, $data['recibe_prestamos_bienes']);
    
        $this->pdf->SetXY(20, 199);
        $this->pdf->Write(10, $data['matricula_coordinacion']);
    
        $this->pdf->SetXY(110, 190);
        $this->pdf->Write(10, $data['responsable_control_administrativo']);
    
        $this->pdf->SetXY(110, 199);
        $this->pdf->Write(10, $data['matricula_administrativo']);
    
        $this->pdf->SetXY(40, 211);
        $this->pdf->Write(10, $data['lugar_fecha']);

        $this->pdf->SetXY(110,165);
        $this->pdf->Write(10,$data['departamento_per']);
        
        $this->displayDescriptionsLineByLine($data['descripciones']);
    }
    
    
    private function displayDescriptionsLineByLine($descripciones) {
        $this->pdf->SetFont('helvetica', '', 10);
        $yPos = 53; 
    
        foreach ($descripciones as $descripcion) {
            $this->pdf->SetXY(130, $yPos); // Establece la posición X e Y para la descripción
            $this->pdf->Write(5, $descripcion); // Muestra la descripción en el PDF
            $yPos += 4; // Incrementa la posición Y para la siguiente descripción
        }
    }
    

    public function output($filename = 'documento_combinado.pdf') {
        $this->pdf->Output($filename, 'I');
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentos = [
        1 => 'C:\\xampp\\htdocs\\Reportes\\salidaBiene.pdf',
        2 => 'C:\\xampp\\htdocs\\Reportes\\resguardo1.pdf',
        3 => 'C:\\xampp\\htdocs\\Reportes\\prestamo1.pdf'
    ];

    $errors = [];

    $data = [
        'nombre_del_trabajador' => $_POST['nombre_del_trabajador'] ?? '',
        'institucion' => $_POST['institucion'] ?? '',
        'adscripcion' => $_POST['adscripcion'] ?? '',
        'matricula' => $_POST['matricula'] ?? '',
        'identificacion' => $_POST['identificacion'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'area_de_salida' => $_POST['area_de_salida'] ?? '',
        'cantidad_bienes' => $_POST['cantidad_bienes'] ?? '',
        'naturaleza_bienes' => $_POST['naturaleza_bienes'] ?? '',
        'descripciones' => json_decode($_POST['descripciones_json'], true) ?? [],
        'proposito_bien' => $_POST['proposito_bien'] ?? '',
        'estado_bienes' => $_POST['estado_bienes'] ?? '',
        'devolucion_bienes' => $_POST['devolucion_bienes'] ?? '',
        'responsable_entrega' => $_POST['responsable_entrega'] ?? '',
        'recibe_salida_bienes' => $_POST['recibe_salida_bienes'] ?? '',
        'lugar_fecha' => $_POST['lugar_fecha'] ?? '',
        'folio_reporte' => $_POST['folio_reporte'] ?? '',
        'nombre_resguardo' => $_POST['nombre_resguardo'] ?? '',
        'cargo_resguardo' => $_POST['cargo_resguardo'] ?? '',
        'direccion_resguardo' => $_POST['direccion_resguardo'] ?? '',
        'telefono_resguardo' => $_POST['telefono_resguardo'] ?? '',
        'recibe_resguardo' => $_POST['recibe_resguardo'] ?? '',
        'entrega_resguardo' => $_POST['entrega_resguardo'] ?? '',
        'recibe_prestamos_bienes' => $_POST['recibe_prestamos_bienes'] ?? '',
        'matricula_coordinacion' => $_POST['matricula_coordinacion'] ?? '',
        'responsable_control_administrativo' => $_POST['responsable_control_administrativo'] ?? '',
        'matricula_administrativo' => $_POST['matricula_administrativo'] ?? '',
        'departamento_per' => $_POST['departamento_per']??'',
        
    ];

    $pdfFiller = new PdfFiller();

    if (isset($_POST['tipo_documento'])) {
        foreach ($_POST['tipo_documento'] as $opcion) {
            if (array_key_exists($opcion, $documentos)) {
                $file = $documentos[$opcion];

                if (!file_exists($file)) {
                    $errors[] = "El archivo '{$file}' no se encuentra en la ruta especificada.";
                    continue;
                }

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
            $pdfFiller->output('documento_combinado.pdf');
        } else {
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
        }
    } else {
        echo 'No se seleccionó ningún tipo de documento.';
    }
}