@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <form id="metricsForm">
        @csrf
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="Ingrese la URL" required
                   pattern="https?://.+" title="Por favor, ingrese una URL válida con http o https">
            <div class="invalid-feedback">
                Por favor, ingrese una URL válida.
            </div>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Categories</label>
            <select id="category" name="category[]" class="form-select select2" multiple="multiple" required>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="strategy" class="form-label">Strategy</label>
            <select id="strategy" name="strategy" class="form-select">
                @foreach($strategies as $strategy)
                    <option value="{{ $strategy->name }}">{{ $strategy->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Obtener Métricas</button>
    </form>

    <div id="metricsResults" class="mt-4">
        <div id="loadingSpinner" class="spinner-border text-primary d-none" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <div class="mt-4">
        <canvas id="metricsCanvas" ></canvas>
    </div>
    <button id="saveMetricRunBtn" class="btn btn-success mt-3">Guardar Métrica</button>
</div>
@endsection

@section('js')
<script>

$(document).ready(function() {
        var metricsData = {};
        
        // Inicializar Select2 en el select con clase select2
        $('.select2').select2();
    
        $('#metricsForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
    
            $.ajax({
                url: '{{ route("fetch-metrics") }}',
                method: 'GET',
                data: formData,
                beforeSend: function() {
                    // Mostrar el spinner antes de enviar la solicitud
                    $('#loadingSpinner').removeClass('d-none');
                },
                success: function(response) {
                    console.log('Form Data:', formData);
                    var metrics = response.lighthouseResult.categories;
                    metricsData = {
                        strategy: $('#strategy').val() // Obtener la estrategia seleccionada
                    }; 
                    
                    $.each(metrics, function(category, data) {
                        metricsData[category] = data.score; // Guardar solo el score
                    });
    
                    $('#saveMetricRunBtn').prop('disabled', false); // Habilitar el botón de guardar
                    console.log('Metrics Data:', metricsData);

                    // Llamar a la función para dibujar en el gráfico doughnut
                    drawMetricsDoughnut(metricsData);
                },
                error: function(error) {
                    console.log(error);
                    toastr.error('Error al obtener las métricas');
                },
                complete: function() {
                    // Ocultar el spinner una vez completada la solicitud
                    $('#loadingSpinner').addClass('d-none');
                }
            });
        });
    
        $('#saveMetricRunBtn').click(function(e) {
            e.preventDefault();
            
            if ($.isEmptyObject(metricsData)) {
                toastr.error('No hay datos de métricas para guardar. Por favor, obtenga las métricas primero.');
                return;
            }
    
            var formData = new FormData();
    
            // Agregar datos de métricas a los datos del formulario
            formData.append('url', $('#url').val());
            formData.append('strategy', metricsData.strategy);
            formData.append('accessibility_metric', metricsData.accessibility || '');
            formData.append('pwa_metric', metricsData.pwa || '');
            formData.append('performance_metric', metricsData.performance || '');
            formData.append('seo_metric', metricsData.seo || '');
            formData.append('best_practices_metric', metricsData['best-practices'] || '');
    
            console.log('Form Data with Metrics:', formData);
    
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("save-metric-run") }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    toastr.success('Métrica guardada correctamente');
                },
                error: function(error) {
                    console.log(error);
                    toastr.error('Error al guardar la métrica: ' + (error.responseJSON ? error.responseJSON.message : 'Error desconocido'));
                }
            });
        });
    
        function drawMetricsDoughnut(metricsData) {
            var ctx = document.getElementById('metricsCanvas').getContext('2d');

            // Datos para el gráfico doughnut
            var data = {
                labels: Object.keys(metricsData).filter(category => category !== 'strategy'),
                datasets: [{
                    label: 'Scores',
                    data: Object.values(metricsData).filter(value => typeof value === 'number'),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', // Color para la primera categoría
                        'rgba(54, 162, 235, 0.5)', // Color para la segunda categoría, etc.
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Configuración del gráfico
            var options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2); // Mostrar solo dos decimales
                            }
                        }
                    }
                }
            };

            // Crear el gráfico doughnut
            var doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: options
            });
        }
    });
</script>
@endsection
