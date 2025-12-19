<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de bienes</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        .suggestions-box {
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    z-index: 1000;
    display: none; 
    position: absolute;
    background-color: white;
}

.suggestion-item {
    padding: 10px;
    cursor: pointer;
}

.suggestion-item:hover {
    background-color: #f0f0f0;
}
        input[type="text"],
        input[type="number"],
        select {
            padding: 5px; 
            font-size: 14px; 
            margin-top: 5px;
            margin-bottom: 10px;
            width: 200px; 
            box-sizing: border-box; 
        }
        .opcion-detalle {
            display: none;
        }
        #fecha-devolucion {
            display: none;
        }
        input[type="text"], input[type="number"], input[type="date"], input[type="tel"] {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        .btn {
            cursor: pointer;
            margin-top: 5px;
        }
        .campo-matricula-nie {
            display: none;
        }
    </style>
</head>
<body>
    <form action="guardar.php" method="post" onsubmit="prepararEnvio()">
    
    <h2>Seleccione el tipo de documento</h2>
        <input type="checkbox" name="tipo_documento[]" value="1" onclick="mostrarOpcion(1); verificarCheckbox();">Constancia de salida.<br>
        <input type="checkbox" name="tipo_documento[]" value="2" onclick="mostrarOpcion(2); verificarCheckbox();">Formato de resguardo.<br>
        <input type="checkbox" name="tipo_documento[]" value="3" onclick="mostrarOpcion(3); verificarCheckbox();">Constancia de préstamos.<br>
        <!-- Formulario para el primer PDF -->
        <div id="opcion1" class="opcion-detalle">
            <h2>Datos generales</h2>
            Identificación presentada: 
            <select name="identificacion" id="identificacion" style="width: 100%;" onchange="cambiarEtiquetaMatricula()">
                <option value="">Selecciona una opcion</option>
                <option value="GAFETE INSTITUTO MEXICANO DEL SEGURO SOCIAL">GAFETE INSTITUTO MEXICANO DEL SEGURO SOCIAL</option>
                <option value="CREDENCIAL INSTITUTO NACIONAL ELECTORAL">CREDENCIAL INSTITUTO NACIONAL ELECTORAL</option>
            </select><br>
            Nombre del trabajador: <input type="text" name="nombre_del_trabajador" tabindex="1" onkeyup="fetchSuggestions(this, 'nombre_del_trabajador');"><br>
        <div id="suggestions-nombre_del_trabajador" class="suggestions-box"></div>
            Institución: <input type="text" name="institucion" tabindex="2"><br>
            <div id="campo-adscripcion-lugardeprocedencia" class="campo-adscripcion-lugardeprocedencia">
                <label id="label-adscripcion" for="adscripcion">Adscripción:</label> 
                <input type="text" name="adscripcion" id="adscripcion" tabindex="3"><br>
            </div>
            <div id="campo-matricula-nie" class="campo-matricula-nie">
                <label id="label-matricula" for="matricula">Matrícula:</label> 
                <input type="text" name="matricula" id="matricula" tabindex="4"><br>
            </div>
            <script>
    function cambiarEtiquetaMatricula() {
        const identificacion = document.getElementById('identificacion').value;
        const inputInstitucion = document.querySelector('input[name="institucion"]'); 
        const labelMatricula = document.getElementById('label-matricula'); 
        const campoMatriculaNie = document.getElementById('campo-matricula-nie'); 
        const inputMatricula = document.getElementById('matricula'); 
        const labelAdscripcion = document.getElementById('label-adscripcion'); 
        const campoAdscripcionlugardeprocedencia = document.getElementById('campo-adscripcion-lugardeprocedencia'); 
        const inputAdscripcion = document.getElementById('adscripcion'); 
        if (identificacion === "GAFETE INSTITUTO MEXICANO DEL SEGURO SOCIAL") {
            inputInstitucion.value = "INSTITUTO MEXICANO DEL SEGURO SOCIAL"; 
            inputInstitucion.disabled = false; 
            labelMatricula.textContent = "Matrícula:";
            inputMatricula.name = "matricula"; 
            inputMatricula.disabled = false; 
            campoMatriculaNie.style.display = "block"; 
            labelAdscripcion.textContent = "Adscripcion:";
            inputAdscripcion.name = "adscripcion"; 
            inputAdscripcion.disabled = false; 
            campoAdscripcionlugar.style.display = "block"; 
        } else if (identificacion === "CREDENCIAL INSTITUTO NACIONAL ELECTORAL") {
            inputInstitucion.value = ""; 
            inputInstitucion.placeholder = ""; 
            inputInstitucion.disabled = true; 
            labelMatricula.textContent = "NIE:";
            inputMatricula.name = "matricula"; 
            inputMatricula.disabled = false; 
            campoMatriculaNie.style.display = "block"; 
            labelAdscripcion.textContent = "Lugar de procedencia:";
            inputAdscripcion.name = "adscripcion"; 
            inputAdscripcion.disabled = false; 
            campoAdscripcionlugardeprocedencia.style.display = "block";
        } else {
            inputInstitucion.value = ""; 
            inputInstitucion.placeholder = "Institución"; 
            inputInstitucion.disabled = false; 
            campoMatriculaNie.style.display = "none"; 
            campoAdscripcionlugardeprocedencia.style.display = "none";
        }
    }
</script>
    Teléfono: <input type="text" name="telefono"><br>
    Área de salida: <input type="text" name="area_de_salida"><br>
    Cantidad de equipos: 
<input type="number" name="cantidad_bienes" id="cantidad_bienes" oninput="generarCamposDescripcion()" min="1" max="35" step="1"><br>
    Naturaleza del equipo:
    <select name="naturaleza_bienes" style="width: 100%;">
        <option value="">Selecciona una opcion</option>
        <option value="BC">BC-bien de consumo</option>
        <option value="BMC">BMC-bien mueble capitalizable</option>
        <option value="BMNC">BMNC-bien mueble no capitalizable</option>
        <option value="BPS">BPS-bien propiedad solicitante</option>
    </select><br>
            <div id="contenedor_descripciones"></div>
            <input type="hidden" name="descripciones_json" id="descripciones_json">
            Propósito del equipo: <input type="text" name="proposito_bien"><br>
            Estado del equipo: <input type="text" name="estado_bienes"><br>
            Equipo sujeto a devolución: 
            <label><input type="radio" name="devolucion_bienes" value="si" onclick="mostrarFecha(true)"> Sí</label>
            <label><input type="radio" name="devolucion_bienes" value="no" onclick="mostrarFecha(false)"> No</label><br>
            <div id="fecha-devolucion">
            Fecha de devolución: <input type="date" name="fecha_devolucion"><br>
            </div>
            Entrega el responsable: <input type="text" name="responsable_entrega"><br>
            Recibe el solicitante: <input type="text" name="recibe_salida_bienes"><br>
            Lugar y fecha: <input type="date" name="lugar_fecha" lang="es-ES"><br>
        </div>
        <!-- Formulario para el segundo PDF -->
        <div id="opcion2" class="opcion-detalle">
            <h2>Formato para el resguardo</h2>
            Folio: <input type="text" name="folio_reporte"><br>
            Nombre: <input type="text" name="nombre_resguardo"><br>
            Cargo: <input type="text" name="cargo_resguardo"><br>
            Dirección de la unidad: <input type="text" name="direccion_resguardo"><br>
            Teléfono de la unidad: <input type="text" name="telefono_resguardo"><br>
            Nombre del que entrega: <input type="text" name="recibe_resguardo"><br>
            Cargo: <input type="text" name="entrega_resguardo"><br>
        </div>
        <!-- Formulario para el tercer PDF -->
        <div id="opcion3" class="opcion-detalle">
            <h2>Constancia de préstamo de bienes</h2>
            Departamento en permanencia: <input type="text" name="departamento_per"><br>
            Responsable de la entrega del equipo: <input type="text" name="recibe_prestamos_bienes"><br>
            Matrícula: <input type="text" name="matricula_coordinacion"><br>
            Nombre de quien recibe: <input type="text" name="responsable_control_administrativo"><br>
            Matrícula: <input type="text" name="matricula_administrativo"><br>
        </div>
        <div class="botones">
        <a href="menu.php" class="boton">Volver al menú</a>
        <button type="submit" class="boton">Generar PDF</button>
    </div>
    </form>
    </form>
    <script>
        let descripciones = [];
        function mostrarOpcion(opcion) {
            var opcionDetalle = document.getElementById('opcion' + opcion);
            var checkbox = document.querySelector('input[name="tipo_documento[]"][value="' + opcion + '"]');
            if (checkbox.checked) {
                opcionDetalle.style.display = 'block';
            } else {
                opcionDetalle.style.display = 'none';
            }
        }
        function mostrarFecha(mostrar) {
            document.getElementById('fecha-devolucion').style.display = mostrar ? 'block' : 'none';
        }
        function verificarCheckbox() {
            const checkboxes = document.querySelectorAll('input[name="tipo_documento[]"]');
            let alMenosUnoSeleccionado = false;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    alMenosUnoSeleccionado = true;
                }
            });
            document.getElementById('generarPDF').disabled = !alMenosUnoSeleccionado;
        }
        document.addEventListener('DOMContentLoaded', verificarCheckbox);
        function generarCamposDescripcion() {
            const cantidad = document.getElementById('cantidad_bienes').value;
            const contenedor = document.getElementById('contenedor_descripciones');
            contenedor.innerHTML = ''; 
            descripciones = []; 
            for (let i = 1; i <= cantidad; i++) {
                const label = document.createElement('label');
                label.textContent = `Descripcion de equipo numero ${i}: `;
                const input = document.createElement('input');
                input.type = 'text';
                input.name = `descripcion_bien_${i}`;
                input.oninput = function() { actualizarDescripcion(i - 1, this.value); }; 
                contenedor.appendChild(label);
                contenedor.appendChild(input);
                contenedor.appendChild(document.createElement('br'));
                descripciones.push('');
            }
        }
        function actualizarDescripcion(index, value) {
            descripciones[index] = value;
        }
        function prepararEnvio() {
            const campoOculto = document.getElementById('descripciones_json');
            campoOculto.value = JSON.stringify(descripciones);
        }
    </script>
    <script>
function fetchSuggestions(inputElement, field) {
    var query = inputElement.value;
    var suggestionBox = document.getElementById('suggestions-' + field);
    
    if (query.length > 2) { 
        $.ajax({
            url: 'recomendacion.php',
            method: 'GET',
            data: { field: field, query: query },
            dataType: 'json',
            success: function(data) {
                suggestionBox.innerHTML = ''; 
                if (data.length > 0) {
                    data.forEach(function(item) {
                        suggestionBox.innerHTML += '<div class="suggestion-item" onclick="selectSuggestion(\'' + field + '\', \'' + item + '\')">' + item + '</div>';
                    });
                    suggestionBox.style.display = 'block';
                } else {
                    suggestionBox.style.display = 'none'; 
                }
            }
        });
    } else {
        suggestionBox.style.display = 'none'; 
    }
}
function selectSuggestion(field, value) {
    var inputElement = document.querySelector('[name="' + field + '"]');
    inputElement.value = value; 
    document.getElementById('suggestions-' + field).style.display = 'none'; 
}
</script>
</body>
</html>
