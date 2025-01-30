@extends('layouts.app')

@section('title', "Theme Statistics - {$theme->name}")

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <div class="page-header">
        <h1>{{ $theme->name }} - Statistics</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Articles Overview</h3>
            <div class="stat-content">
                <div class="stat-item">
                    <span class="stat-label">Total Articles</span>
                    <span class="stat-value">{{ $statistics['articles']['total'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Published</span>
                    <span class="stat-value">{{ $statistics['articles']['published'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Pending Review</span>
                    <span class="stat-value">{{ $statistics['articles']['pending'] }}</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <h3>Engagement</h3>
            <div class="stat-content">
                <div class="stat-item">
                    <span class="stat-label">Total Subscribers</span>
                    <span class="stat-value">{{ $statistics['subscribers'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Comments</span>
                    <span class="stat-value">{{ $statistics['comments'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Average Rating</span>
                    <span class="stat-value">{{ number_format($statistics['average_rating'], 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-section">
        <div class="chart-card">
            <h3>Monthly Activity</h3>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-card h3 {
    color: #1f2937;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.stat-content {
    display: grid;
    gap: 1rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.stat-value {
    color: #1f2937;
    font-weight: 600;
    font-size: 1.125rem;
}

.chart-section {
    margin-top: 2rem;
}

.chart-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.chart-card h3 {
    color: #1f2937;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Add your Chart.js initialization here
</script>
@endpush
@endsection
