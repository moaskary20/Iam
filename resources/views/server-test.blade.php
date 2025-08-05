<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار إعدادات السيرفر للرفع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            direction: rtl;
        }
        .card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .status-success { color: #28a745; }
        .status-error { color: #dc3545; }
        .bg-success-light { background-color: #d4edda; }
        .bg-danger-light { background-color: #f8d7da; }
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 10px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-server"></i>
                            اختبار إعدادات السيرفر للرفع
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="runTest()">
                                <i class="bi bi-play-circle"></i>
                                تشغيل الاختبار
                            </button>
                        </div>
                        
                        <div id="loading" class="text-center mt-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">جاري الاختبار...</p>
                        </div>
                        
                        <div id="results" class="mt-4" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function runTest() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').style.display = 'none';
            
            try {
                const response = await fetch('/test-upload-api');
                const data = await response.json();
                
                displayResults(data);
            } catch (error) {
                document.getElementById('results').innerHTML = `
                    <div class="alert alert-danger">
                        <h5>خطأ في الاختبار:</h5>
                        <p>${error.message}</p>
                    </div>
                `;
            }
            
            document.getElementById('loading').style.display = 'none';
            document.getElementById('results').style.display = 'block';
        }
        
        function displayResults(data) {
            const resultsDiv = document.getElementById('results');
            
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-info-circle"></i> معلومات السيرفر</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>PHP:</strong> ${data.server_info.php_version}</li>
                                    <li><strong>Laravel:</strong> ${data.server_info.laravel_version}</li>
                                    <li><strong>Storage Path:</strong> <code>${data.server_info.storage_path}</code></li>
                                    <li><strong>Public Path:</strong> <code>${data.server_info.public_path}</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-gear"></i> إعدادات البيئة</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>APP_URL:</strong> ${data.tests.env_settings.app_url || 'غير محدد'}</li>
                                    <li><strong>Filesystem:</strong> ${data.tests.env_settings.filesystem_disk}</li>
                                    <li><strong>Public URL:</strong> ${data.tests.env_settings.public_disk_url || 'غير محدد'}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-check-circle"></i> نتائج الاختبار</h5>
                    </div>
                    <div class="card-body">
            `;
            
            // Storage Link Test
            html += getTestRow('Storage Link', data.tests.storage_link);
            
            // Storage Permissions Test
            html += getTestRow('صلاحيات Storage', data.tests.storage_permissions);
            
            // Upload Directories Test
            Object.keys(data.tests.upload_directories).forEach(dir => {
                html += getTestRow(`مجلد ${dir}`, data.tests.upload_directories[dir]);
            });
            
            // Actual Upload Test
            html += getTestRow('اختبار الرفع الفعلي', data.tests.actual_upload);
            
            html += `</div></div>`;
            
            // Recommendations
            if (data.recommendations && data.recommendations.length > 0) {
                html += `
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> التوصيات لحل المشاكل</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                `;
                
                data.recommendations.forEach(rec => {
                    html += `<li class="code-block">${rec}</li>`;
                });
                
                html += `</ol></div></div>`;
            }
            
            resultsDiv.innerHTML = html;
        }
        
        function getTestRow(name, result) {
            const statusClass = result.status ? 'status-success' : 'status-error';
            const bgClass = result.status ? 'bg-success-light' : 'bg-danger-light';
            const icon = result.status ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
            
            return `
                <div class="row mb-3 p-2 rounded ${bgClass}">
                    <div class="col-md-3">
                        <strong>${name}:</strong>
                    </div>
                    <div class="col-md-1">
                        <i class="bi ${icon} ${statusClass}"></i>
                    </div>
                    <div class="col-md-8">
                        <span class="${statusClass}">${result.message}</span>
                        ${result.path ? `<br><small class="text-muted">${result.path}</small>` : ''}
                        ${result.error ? `<br><small class="text-danger">${result.error}</small>` : ''}
                    </div>
                </div>
            `;
        }
    </script>
</body>
</html>
