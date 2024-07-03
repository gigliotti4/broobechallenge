@extends('layouts.app')

@section('content')
<div class="container">
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
            <label for="category" class="form-label">Categorías</label>
            <select id="category" name="category[]" class="form-select select2" multiple="multiple" required>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="strategy" class="form-label">Estrategia</label>
            <select id="strategy" name="strategy" class="form-select ">
                @foreach($strategies as $strategy)
                    <option value="{{ $strategy->name }}">{{ $strategy->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Obtener Métricas</button>
    </form>

    <div id="metricsResults" class="mt-4"></div>

    <button id="saveMetricRunBtn" class="btn btn-success mt-3">Guardar Métrica</button>
</div>
@endsection

@section('js')

<script>
    $(document).ready(function() {
        // Inicializar Select2 en el select con clase select2
        $('.select2').select2();

        $('#metricsForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '{{ route("fetch-metrics") }}',
                method: 'GET',
                data: formData,
                success: function(response) {
                    console.log(response)
                    var metrics = response.lighthouseResult.categories;
                    var resultsHtml = '';

                    $.each(metrics, function(category, data) {
                        resultsHtml += '<h3>' + category + '</h3>';
                        resultsHtml += '<p>Score: ' + data.score + '</p>';
                    });

                    $('#metricsResults').html(resultsHtml);
                },
                error: function(error) {
                    console.log(error);
                    toastr.error('Error al obtener las métricas');
                }
            });
        });

        $('#saveMetricRunBtn').click(function(e) {
            e.preventDefault();
            var formData = $('#metricsForm').serialize();

            $.ajax({
                url: '{{ route("save-metric-run") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Métrica guardada correctamente');
                },
                error: function(error) {
                    console.log(error);
                    toastr.error('Error al guardar la métrica');
                }
            });
        });
    });
</script>
@endsection
