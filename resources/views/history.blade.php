@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Metric History</h2>
    <table class="table table-bordered mt-4">
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
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metricHistory as $metric)
            <tr>
                <td>{{ $metric->id }}</td>
                <td>{{ $metric->url }}</td>
                <td>{{ $metric->accessibility_metric ?? '' }}</td>
                <td>{{ $metric->pwa_metric ?? '' }}</td>
                <td>{{ $metric->performance_metric ?? '' }}</td>
                <td>{{ $metric->seo_metric ?? '' }}</td>
                <td>{{ $metric->best_practices_metric ?? '' }}</td>
                <td>{{ $metric->strategy ?? '' }}</td>
                <td>{{ $metric->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $metric->updated_at->format('Y-m-d H:i:s') }}</td>
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