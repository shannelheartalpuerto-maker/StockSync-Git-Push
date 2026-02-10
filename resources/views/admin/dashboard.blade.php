@extends('layouts.app')

@section('content')
<link href="{{ asset('css/admin-dashboard-design.css') }}?v={{ time() }}" rel="stylesheet">
<!-- Cache Buster: {{ time() }} -->

<div class="container-fluid px-4 admin-dashboard-container animate-fade-up">
    <!-- Header -->
    <div class="dashboard-header">
        <h2 class="dashboard-title"><i class="fa-solid fa-gauge-high me-3 text-primary"></i>Admin Dashboard</h2>
        <p class="dashboard-subtitle">Overview of your business performance and inventory status.</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="d-flex justify-content-center w-100">
        <ul class="nav nav-pills dashboard-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product" type="button" role="tab" aria-controls="product" aria-selected="true">
                    <i class="fa-solid fa-box"></i>Product Data
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab" aria-controls="sales" aria-selected="false">
                    <i class="fa-solid fa-chart-line"></i>Sales Analytics
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="forecast-tab" data-bs-toggle="tab" data-bs-target="#forecast" type="button" role="tab" aria-controls="forecast" aria-selected="false">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>Forecasts
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="dashboardTabsContent">
        <!-- Product Data Tab -->
        <div class="tab-pane fade show active" id="product" role="tabpanel" aria-labelledby="product-tab">
            <!-- Product Stats -->
            <div class="row g-4 mb-4">
                 <!-- Total Products -->
                 <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Products</p>
                                <h3 class="stat-value">{{ number_format($totalProducts) }}</h3>
                            </div>
                            <div class="stat-icon primary">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                        </div>
                    </div>
                 </div>
                 <!-- Total Stock -->
                 <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Stock</p>
                                <h3 class="stat-value">{{ number_format($totalStockCount) }}</h3>
                            </div>
                            <div class="stat-icon info">
                                <i class="fa-solid fa-cubes"></i>
                            </div>
                        </div>
                    </div>
                 </div>
                 <!-- Stock Value -->
                 <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Inventory Value</p>
                                <h3 class="stat-value text-success">₱{{ number_format($totalStockValue, 2) }}</h3>
                            </div>
                            <div class="stat-icon success">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                        </div>
                    </div>
                 </div>
                 <!-- Top Moving Product -->
                 <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="min-width: 0;"> <!-- Fix for text-truncate flex child -->
                                <p class="stat-label">Top Mover</p>
                                <h5 class="fw-bold mb-0 text-truncate">{{ $topMovingProduct->name ?? 'N/A' }}</h5>
                                <small class="text-muted">{{ $topMovingProduct->total_sold ?? 0 }} sold</small>
                            </div>
                            <div class="stat-icon warning">
                                <i class="fa-solid fa-fire"></i>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
            
            <div class="row g-4">
                <!-- Recent Stock Activity -->
                <div class="col-lg-8">
                     <div class="content-card">
                        <div class="card-header-custom">
                            <h5 class="card-title-custom">Recent Stock Activity</h5>
                            <a href="{{ route('admin.stock_logs.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                        </div>
                        <div class="card-body-custom p-0">
                            <div class="list-group list-group-custom list-group-flush">
                                @forelse($recentLogs as $log)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 rounded-circle p-2 {{ $log->type == 'stock_in' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                                    <i class="fa-solid {{ $log->type == 'stock_in' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold text-dark">{{ $log->product->name ?? 'Unknown' }}</h6>
                                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }} by {{ $log->user->name ?? 'System' }}</small>
                                                </div>
                                            </div>
                                            <span class="badge {{ $log->type == 'stock_in' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $log->type == 'stock_in' ? 'success' : 'danger' }} rounded-pill px-3">
                                                {{ $log->type == 'stock_in' ? '+' : '-' }}{{ $log->quantity }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-5 text-center text-muted">
                                        <i class="fa-regular fa-clipboard mb-3 fa-2x opacity-50"></i>
                                        <p class="mb-0">No recent activity found</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                     </div>
                </div>
                
                <!-- Category Distribution -->
                <div class="col-lg-4">
                    <div class="content-card">
                        <div class="card-header-custom">
                            <h5 class="card-title-custom">Category Distribution</h5>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body-custom overflow-auto" style="max-height: 450px;">
                            @forelse($categoryStats as $category)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold text-dark">{{ $category->name }}</span>
                                        <span class="text-muted small fw-bold">{{ $category->products_count }}</span>
                                    </div>
                                    <div class="progress progress-custom">
                                        @php
                                            $percentage = $totalProducts > 0 ? ($category->products_count / $totalProducts) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <i class="fa-solid fa-folder-open fa-2x mb-3 opacity-50"></i>
                                    <p class="mb-0">No categories found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Tab -->
        <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
            
            <div class="row g-4 mb-4">
                <!-- Total Revenue -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Revenue ({{ ucfirst(str_replace('_', ' ', $period)) }})</p>
                                <h3 class="stat-value text-success">₱{{ number_format($totalRevenue, 2) }}</h3>
                                @if($period == 'all_time')
                                    <div class="stat-trend up">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                        <span>+{{ number_format($thisMonthRevenue, 2) }} this month</span>
                                    </div>
                                @endif
                            </div>
                            <div class="stat-icon success">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Sales -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Transactions</p>
                                <h3 class="stat-value">{{ number_format($totalTransactions) }}</h3>
                            </div>
                            <div class="stat-icon primary">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Avg Sale -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Average Order</p>
                                <h3 class="stat-value text-info">₱{{ number_format($averageOrderValue, 2) }}</h3>
                            </div>
                            <div class="stat-icon info">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Most Sold -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="min-width: 0;">
                                <p class="stat-label">Best Seller</p>
                                <h5 class="fw-bold mb-0 text-truncate">{{ $mostSoldProduct->name ?? 'N/A' }}</h5>
                                <small class="text-muted">{{ $mostSoldProduct->total_sold ?? 0 }} sold</small>
                            </div>
                            <div class="stat-icon warning">
                                <i class="fa-solid fa-medal"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="d-flex justify-content-center mb-4">
                <form action="{{ route('admin.dashboard') }}" method="GET" id="salesFilterForm">
                    <input type="hidden" name="tab" value="sales">
                    <div class="filter-group">
                        <button type="submit" name="period" value="today" class="filter-btn {{ $period == 'today' ? 'active' : '' }}">Today</button>
                        <button type="submit" name="period" value="week" class="filter-btn {{ $period == 'week' ? 'active' : '' }}">This Week</button>
                        <button type="submit" name="period" value="month" class="filter-btn {{ $period == 'month' ? 'active' : '' }}">This Month</button>
                        <button type="submit" name="period" value="all_time" class="filter-btn {{ $period == 'all_time' || !$period ? 'active' : '' }}">All Time</button>
                    </div>
                </form>
            </div>
            
            <div class="row g-4">
                <!-- Chart -->
                <div class="col-lg-8">
                    <div class="content-card">
                        <div class="card-header-custom">
                            <h5 class="card-title-custom">Revenue Overview</h5>
                        </div>
                        <div class="card-body-custom">
                            <div style="height: 320px;"><canvas id="revenueChart"></canvas></div>
                        </div>
                    </div>
                </div>
                <!-- Recent Transactions -->
                <div class="col-lg-4">
                     <div class="content-card">
                        <div class="card-header-custom">
                            <h5 class="card-title-custom">Recent Transactions</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-inline-block">
                                    <input type="hidden" name="tab" value="sales">
                                    @if(request('period'))
                                        <input type="hidden" name="period" value="{{ request('period') }}">
                                    @endif
                                    <select name="transaction_status" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                                        <option value="all" {{ request('transaction_status') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="completed" {{ request('transaction_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="returned" {{ request('transaction_status') == 'returned' ? 'selected' : '' }}>Returned</option>
                                    </select>
                                </form>
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body-custom p-0">
                            <div class="list-group list-group-custom list-group-flush">
                                @forelse($recentTransactions as $transaction)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 rounded-circle p-2 bg-primary bg-opacity-10 text-primary">
                                                <i class="fa-solid fa-file-invoice"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">#{{ $transaction->id }}</div>
                                                <small class="text-muted">{{ $transaction->user->name }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success">₱{{ number_format($transaction->total_amount, 2) }}</div>
                                            <small class="text-muted">{{ $transaction->created_at->format('M d') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="p-5 text-center text-muted">
                                    <i class="fa-solid fa-receipt mb-3 fa-2x opacity-50"></i>
                                    <p class="mb-0">No transactions yet</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                     </div>
                </div>
            </div>
        </div>

        <!-- Forecast Tab -->
        <div class="tab-pane fade" id="forecast" role="tabpanel" aria-labelledby="forecast-tab">
            
            <div class="row g-4 mb-4">
                <!-- Fast Moving -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Fast Moving</p>
                                <h3 class="stat-value">{{ number_format($fastMovingCount) }}</h3>
                            </div>
                            <div class="stat-icon success">
                                <i class="fa-solid fa-angles-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stable -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Stable</p>
                                <h3 class="stat-value">{{ number_format($stableMovingCount) }}</h3>
                            </div>
                            <div class="stat-icon primary">
                                <i class="fa-solid fa-minus"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slow Moving -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Slow Moving</p>
                                <h3 class="stat-value">{{ number_format($slowMovingCount) }}</h3>
                            </div>
                            <div class="stat-icon secondary">
                                <i class="fa-solid fa-angles-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Restock Needed -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Restock Needed</p>
                                <h3 class="stat-value">{{ number_format($restockNeededCount) }}</h3>
                            </div>
                            <div class="stat-icon warning">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="d-flex justify-content-center mb-4">
                <form action="{{ route('admin.dashboard') }}" method="GET">
                    <input type="hidden" name="tab" value="forecast">
                    <div class="filter-group">
                        <button type="submit" name="period" value="today" class="filter-btn {{ $period == 'today' ? 'active' : '' }}">Today</button>
                        <button type="submit" name="period" value="week" class="filter-btn {{ $period == 'week' ? 'active' : '' }}">This Week</button>
                        <button type="submit" name="period" value="month" class="filter-btn {{ $period == 'month' ? 'active' : '' }}">This Month</button>
                        <button type="submit" name="period" value="all_time" class="filter-btn {{ $period == 'all_time' || !$period ? 'active' : '' }}">All Time</button>
                    </div>
                </form>
            </div>

            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Forecast Data & Recommendations</h5>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#legendCollapse" aria-expanded="false" aria-controls="legendCollapse">
                        <i class="fa-solid fa-circle-info me-1"></i> Legend
                    </button>
                </div>
                
                <div class="collapse px-4 pb-3" id="legendCollapse">
                    <div class="card card-body bg-light border-0 small text-muted">
                        <div class="d-flex flex-wrap gap-4">
                            <div><i class="fa-solid fa-angles-up text-success me-1"></i> <strong>Fast Moving:</strong> High turnover (>50% stock sold/month)</div>
                            <div><i class="fa-solid fa-minus text-primary me-1"></i> <strong>Stable:</strong> Moderate turnover</div>
                            <div><i class="fa-solid fa-angles-down text-secondary me-1"></i> <strong>Slow Moving:</strong> Low turnover (<10% stock sold/month)</div>
                            <div><i class="fa-solid fa-arrow-trend-up text-danger me-1"></i> <strong>Stock In:</strong> Current stock is below projected 30-day demand</div>
                        </div>
                    </div>
                </div>

                <div class="card-body-custom p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Velocity</th>
                                    <th class="text-center">Stock Action</th>
                                    <th class="text-center">Suggested Thresholds (L / G / O)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productForecasts as $forecast)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $forecast['name'] }}</div>
                                            <small class="text-muted">{{ $forecast['category_name'] }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $forecast['stock'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="{{ $forecast['velocity_class'] }} fw-bold">
                                                <i class="fa-solid {{ $forecast['velocity_icon'] }} me-1"></i>
                                                {{ $forecast['velocity_status'] }}
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                Avg: {{ number_format($forecast['avg_daily_sales'], 2) }}/day
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $forecast['action_class'] == 'text-danger' ? 'bg-danger' : 'bg-success' }} bg-opacity-10 {{ $forecast['action_class'] }}">
                                                <i class="fa-solid {{ $forecast['action_icon'] }} me-1"></i>
                                                {{ $forecast['stock_action'] }}
                                                @if($forecast['action_qty'] > 0)
                                                    (+{{ $forecast['action_qty'] }})
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-2">
                                                <span class="badge bg-warning bg-opacity-10 text-warning" title="Suggested Low Stock">{{ $forecast['suggested_low_threshold'] }}</span>
                                                <span class="badge bg-success bg-opacity-10 text-success" title="Suggested Good Stock">{{ $forecast['suggested_good_stock'] }}</span>
                                                <span class="badge bg-danger bg-opacity-10 text-danger" title="Suggested Over Stock">{{ $forecast['suggested_overstock_threshold'] }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-chart-pie fa-2x mb-3 opacity-50"></i>
                                            <p class="mb-0">No forecast data available for this period.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer-custom bg-light p-3 text-end">
                    <small class="text-muted me-2">L: Low Threshold</small>
                    <small class="text-muted me-2">G: Good Target</small>
                    <small class="text-muted">O: Overstock Limit</small>
                </div>
            </div>
        </div>
    </div> <!-- End Tab Content -->
</div> <!-- End Container -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Persistence
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam) {
            const tabTrigger = document.querySelector(`#${tabParam}-tab`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Chart.js
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dates) !!},
                    datasets: [{
                        label: 'Revenue ($)',
                        data: {!! json_encode($revenues) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 },
                            displayColors: false,
                            callbacks: {
http://stocksync.fun/fix-storage                                label: function(context) {
                                    return '₱ ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
