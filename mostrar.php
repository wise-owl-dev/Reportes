<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de bienes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-action {
            margin-bottom: 5px;
            width: 100%;
        }
        .thead-dark th {
            background-color: #343a40;
            color: white;
            white-space: nowrap;
        }

        .table-responsive {
            max-width: 100%;
            overflow-x: auto;
            height: 700px; 
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        #searchField {
            margin-top: 10px;
        }

        .editable {
            cursor: pointer;
            padding: 5px;
        }

        .editable:hover {
            background-color: #f0f0f0;
        }
        .custom-select {
            width: auto;
            margin-left: 10px;
            display: inline-block;
            padding: 10px; 
            font-size: 16px; 
            border-radius: 0.25rem;
            border: 2px solid #007bff;
            background-color: #f8f9fa; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .custom-select:hover {
            background-color: #e9ecef; 
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); 
        }

        .custom-select:focus {
            outline: none; 
            border-color: #0056b3; 
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5); 
        }

        @media (max-width: 576px) {
            .custom-select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid mt-4">
    <h2 class="mb-4">Reportes</h2>
    <div class="mb-3">
    <button class="btn btn-primary" onclick="window.location.href='menu.php'">Volver al menú</button>
        <button class="btn btn-success" id="searchButton">Buscar</button>
        <div id="searchField" style="display: none;">
            <input type="text" class="form-control" id="searchInput" placeholder="Ingrese el término de búsqueda...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Acciones</th>
                    <th>ID</th>
                    <th>Nombre del trabajador</th>
                    <th>Institución</th>
                    <th>Adscripción</th>
                    <th>Matrícula</th>
                    <th>Identificación</th>
                    <th>Teléfono</th>
                    <th>Área de salida</th>
                    <th>Cantidad de bienes</th>
                    <th>Naturaleza de bienes</th>
                    <th>Descripciones</th>
                    <th>Propósito del bien</th>
                    <th>Estado de Bienes</th>
                    <th>Devolución de bienes</th>
                    <th>Fecha de devolución</th>
                    <th>Responsable de entrega</th>
                    <th>Recibe el solicitante</th>
                    <th>Lugar y fecha</th>
                    <th>Folio de reporte</th>
                    <th>Nombre de resguardo</th>
                    <th>Cargo de resguardo</th>
                    <th>Dirección de resguardo</th>
                    <th>Teléfono de resguardo</th>
                    <th>Recibe resguardo</th>
                    <th>Entrega resguardo</th>
                    <th>Recibe préstamos bienes</th>
                    <th>Matrícula de coordinación</th>
                    <th>Responsable control administrativo</th>
                    <th>Matrícula administrativa</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function loadTable(documentTypes = [], searchTerm = '') {
        $.post('buscar.php', { document_types: documentTypes, search_term: searchTerm }, function(data) {
            $('#tableBody').html(data);
        });
    }
    loadTable();
    $('#searchButton').on('click', function() {
        $('#searchField').toggle();
    });
    $('#searchInput').on('keyup', function() {
        const searchTerm = $(this).val();
        const documentTypes = $('#documentTypeSelect').val();  
        loadTable(documentTypes, searchTerm);
    });
    $('#documentTypeSelect').on('change', function() {
        const documentTypes = $(this).val(); 
        const searchTerm = $('#searchInput').val();  
        loadTable(documentTypes, searchTerm); 
    });
    $(document).on('dblclick', '.editable', function() {
        const field = $(this).data('field');
        const oldValue = $(this).text();
        $(this).html(`<input type='text' value='${oldValue}' class='form-control' data-field='${field}' data-old-value='${oldValue}'>`);
    });
    $(document).on('blur', 'input', function() {
        const newValue = $(this).val();
        const field = $(this).data('field');
        const oldValue = $(this).data('old-value');
        const id = $(this).closest('tr').find('td').eq(1).text(); 
        if (newValue !== oldValue) {
            $.post('editar.php', { id: id, [field]: newValue }, function(response) {
                alert(response);
                loadTable($('#searchInput').val(), $('#documentTypeSelect').val()); 
            });
        } else {
            $(this).parent().text(oldValue); 
        }
    });
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');  
        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            $.post('eliminar.php', { id: id }, function(data) {
                alert(data);  
                loadTable($('#documentTypeSelect').val(), $('#searchInput').val()); 
            });
        }
    });
</script>
</body>
</html>
