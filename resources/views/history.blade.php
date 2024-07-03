
@extends('layouts.app')

@section('content')

<div class="container">

    <table id="metricHistoryTable" class="display my-5">
        <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>Accessibility Metric</th>
                <th>PWA Metric</th>
                <th>Performance Metric</th>
                <th>SEO Metric</th>
                <th>Best Practices Metric</th>
                <th>Strategy</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metricHistory as $metric)
                <tr>
                    <td>{{ $metric->id }}</td>
                    <td>{{ $metric->url }}</td>
                    <td>{{ $metric->accessibility_metric }}</td>
                    <td>{{ $metric->pwa_metric }}</td>
                    <td>{{ $metric->performance_metric }}</td>
                    <td>{{ $metric->seo_metric }}</td>
                    <td>{{ $metric->best_practices_metric }}</td>
                    <td>{{ $metric->strategy->name }}</td>
                    <td>{{ $metric->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('#metricHistoryTable').DataTable();
    });
</script>


@endsection