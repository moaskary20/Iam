<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إحصائيات المبيعات - تطبيق IAM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    
    <style>
        :root {
            /* Modern Colors */
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --primary-800: #075985;
            --primary-900: #0c4a6e;
            
            /* Success Colors */
            --success-50: #f0fdf4;
            --success-500: #22c55e;
            --success-600: #16a34a;
            
            /* Warning Colors */
            --warning-50: #fffbeb;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            
            /* Error Colors */
            --error-50: #fef2f2;
            --error-500: #ef4444;
            --error-600: #dc2626;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            --gradient-success: linear-gradient(135deg, var(--success-500) 0%, var(--success-600) 100%);
            --gradient-warning: linear-gradient(135deg, var(--warning-500) 0%, var(--warning-600) 100%);
            --gradient-error: linear-gradient(135deg, var(--error-500) 0%, var(--error-600) 100%);
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            color: #1f2937;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 1rem 0;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .header-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .logo {
            font-size: 1.7rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 2px 16px #764ba2cc, 0 1px 0 #fff8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 1px;
        }
        
        .back-button {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 1rem;
            max-width: 100%;
            margin: 0 auto;
            width: 100%;
        }

        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stat-card.success::before {
            background: var(--gradient-success);
        }

        .stat-card.warning::before {
            background: var(--gradient-warning);
        }

        .stat-card.error::before {
            background: var(--gradient-error);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: var(--gradient-primary);
        }

        .stat-icon.success {
            background: var(--gradient-success);
        }

        .stat-icon.warning {
            background: var(--gradient-warning);
        }

        .stat-icon.error {
            background: var(--gradient-error);
        }

        .stat-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-change.positive {
            color: var(--success-600);
        }

        .stat-change.negative {
            color: var(--error-600);
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        .chart-period {
            display: flex;
            gap: 0.5rem;
        }

        .period-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .period-btn.active {
            background: var(--primary-500);
            color: white;
            border-color: var(--primary-500);
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        /* Tables */
        .table-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .table-header {
            margin-bottom: 1.5rem;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tr:hover {
            background: #f9fafb;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: var(--success-50);
            color: var(--success-600);
        }

        .status-pending {
            background: var(--warning-50);
            color: var(--warning-600);
        }

        .status-cancelled {
            background: var(--error-50);
            color: var(--error-600);
        }

        /* Responsive */
        @media (min-width: 768px) {
            .main-content {
                padding: 3rem 2rem;
            }

            .charts-section {
                grid-template-columns: 2fr 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .main-content {
                padding: 3rem 3rem;
            }
        }

        @media (min-width: 1440px) {
            .main-content {
                padding: 3rem 4rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(14, 165, 233, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-500);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <span>📊</span>
                <span>إحصائيات المبيعات</span>
            </a>
            <a href="/" class="back-button">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للرئيسية
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <h1 class="page-title fade-in">لوحة تحكم المبيعات</h1>

        <!-- Stats Grid -->
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📦</div>
                    <div>
                        <div class="stat-title">إجمالي الطلبات</div>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
                <div class="stat-change positive">
                    <span>↗</span>
                    <span>+12% من الشهر الماضي</span>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon success">💰</div>
                    <div>
                        <div class="stat-title">إجمالي الإيرادات</div>
                    </div>
                </div>
                <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="stat-change positive">
                    <span>↗</span>
                    <span>+8.2% من الشهر الماضي</span>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-icon warning">💳</div>
                    <div>
                        <div class="stat-title">الرصيد الحالي</div>
                    </div>
                </div>
                <div class="stat-value">${{ number_format($stats['current_balance'], 2) }}</div>
                <div class="stat-change positive">
                    <span>↗</span>
                    <span>متاح للسحب</span>
                </div>
            </div>

            <div class="stat-card error">
                <div class="stat-header">
                    <div class="stat-icon error">📈</div>
                    <div>
                        <div class="stat-title">الأرباح الشهرية</div>
                    </div>
                </div>
                <div class="stat-value">${{ number_format($stats['monthly_profit'], 2) }}</div>
                <div class="stat-change positive">
                    <span>↗</span>
                    <span>+15.3% من الشهر الماضي</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section fade-in">
            <!-- Sales Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">مبيعات السنة الحالية</h3>
                    <div class="chart-period">
                        <button class="period-btn active" onclick="updateChart('year')">سنوي</button>
                        <button class="period-btn" onclick="updateChart('month')">شهري</button>
                        <button class="period-btn" onclick="updateChart('week')">أسبوعي</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Order Status Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">حالات الطلبات</h3>
                </div>
                <div class="chart-container">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Market Distribution Chart -->
        <div class="chart-card fade-in">
            <div class="chart-header">
                <h3 class="chart-title">توزيع المبيعات حسب الأسواق</h3>
            </div>
            <div class="chart-container">
                <canvas id="marketChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="table-card fade-in">
            <div class="table-header">
                <h3 class="table-title">أحدث الطلبات</h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>المبلغ</th>
                            <th>السوق</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-001</td>
                            <td>أحمد محمد</td>
                            <td>$125.50</td>
                            <td>{{ $stats['current_market'] }}</td>
                            <td><span class="status-badge status-completed">مكتمل</span></td>
                            <td>2025-08-03</td>
                        </tr>
                        <tr>
                            <td>#ORD-002</td>
                            <td>سارة أحمد</td>
                            <td>$89.30</td>
                            <td>السوق الثاني</td>
                            <td><span class="status-badge status-pending">قيد المعالجة</span></td>
                            <td>2025-08-03</td>
                        </tr>
                        <tr>
                            <td>#ORD-003</td>
                            <td>محمد علي</td>
                            <td>$200.00</td>
                            <td>السوق الثالث</td>
                            <td><span class="status-badge status-completed">مكتمل</span></td>
                            <td>2025-08-02</td>
                        </tr>
                        <tr>
                            <td>#ORD-004</td>
                            <td>فاطمة خالد</td>
                            <td>$75.25</td>
                            <td>السوق الأول</td>
                            <td><span class="status-badge status-cancelled">ملغي</span></td>
                            <td>2025-08-02</td>
                        </tr>
                        <tr>
                            <td>#ORD-005</td>
                            <td>علي حسن</td>
                            <td>$150.75</td>
                            <td>السوق الرابع</td>
                            <td><span class="status-badge status-completed">مكتمل</span></td>
                            <td>2025-08-01</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeSalesChart();
            initializeOrderStatusChart();
            initializeMarketChart();
            
            // Add fade-in animation to elements
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Sales Chart
        function initializeSalesChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const monthlyData = @json($monthlyData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Object.keys(monthlyData),
                    datasets: [{
                        label: 'المبيعات ($)',
                        data: Object.values(monthlyData),
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#0ea5e9',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `المبيعات: $${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: '#0284c7'
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }

        // Order Status Chart
        function initializeOrderStatusChart() {
            const ctx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatus = @json($orderStatus);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['مكتمل', 'قيد المعالجة', 'ملغي'],
                    datasets: [{
                        data: Object.values(orderStatus),
                        backgroundColor: [
                            '#22c55e',
                            '#f59e0b',
                            '#ef4444'
                        ],
                        borderWidth: 0,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    family: 'Cairo'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed}%`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }

        // Market Distribution Chart
        function initializeMarketChart() {
            const ctx = document.getElementById('marketChart').getContext('2d');
            const marketData = @json($marketData);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['السوق الأول', 'السوق الثاني', 'السوق الثالث', 'السوق الرابع'],
                    datasets: [{
                        label: 'نسبة المبيعات (%)',
                        data: Object.values(marketData),
                        backgroundColor: [
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            '#0ea5e9',
                            '#22c55e',
                            '#f59e0b',
                            '#ef4444'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return `نسبة المبيعات: ${context.parsed.y}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 50,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }

        // Update chart function
        function updateChart(period) {
            // Update active button
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Here you would typically fetch new data based on the period
            // For demo purposes, we'll just show a loading state
            const chartContainer = document.querySelector('#salesChart').parentElement;
            chartContainer.style.opacity = '0.5';
            
            setTimeout(() => {
                chartContainer.style.opacity = '1';
                // In a real app, you would update the chart with new data here
            }, 1000);
        }

        // Add hover effects to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Animate numbers on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statValue = entry.target.querySelector('.stat-value');
                    if (statValue && !statValue.classList.contains('animated')) {
                        animateNumber(statValue);
                        statValue.classList.add('animated');
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-card').forEach(card => {
            observer.observe(card);
        });

        function animateNumber(element) {
            const finalNumber = parseFloat(element.textContent.replace(/[^0-9.]/g, ''));
            const increment = finalNumber / 30;
            let currentNumber = 0;

            const timer = setInterval(() => {
                currentNumber += increment;
                if (currentNumber >= finalNumber) {
                    currentNumber = finalNumber;
                    clearInterval(timer);
                }
                
                if (element.textContent.includes('$')) {
                    element.textContent = '$' + currentNumber.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                } else {
                    element.textContent = Math.floor(currentNumber).toLocaleString();
                }
            }, 50);
        }
    </script>
</body>
</html>
