<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار إعدادات Cloudflare</title>
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
        .status-warning { color: #ffc107; }
        .bg-success-light { background-color: #d4edda; }
        .bg-danger-light { background-color: #f8d7da; }
        .bg-warning-light { background-color: #fff3cd; }
        .json-block {
            background: #2d3748;
            color: #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .copy-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        .json-container {
            position: relative;
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
                            <i class="bi bi-cloud-check"></i>
                            اختبار إعدادات Cloudflare و HTTPS
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="runTest()">
                                <i class="bi bi-play-circle"></i>
                                تشغيل اختبار Cloudflare
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
                const response = await fetch('/test-cloudflare-api');
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
                                <h5><i class="bi bi-info-circle"></i> معلومات الطلب</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>URL:</strong> ${data.request_info.url}</li>
                                    <li><strong>Scheme:</strong> <span class="${data.request_info.scheme === 'https' ? 'status-success' : 'status-error'}">${data.request_info.scheme}</span></li>
                                    <li><strong>Is Secure:</strong> <span class="${data.request_info.is_secure ? 'status-success' : 'status-error'}">${data.request_info.is_secure ? 'نعم' : 'لا'}</span></li>
                                    <li><strong>IP:</strong> ${data.request_info.ip}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-cloud"></i> كشف Cloudflare</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>خلف Cloudflare:</strong> <span class="${data.cloudflare_detection.behind_cloudflare ? 'status-success' : 'status-error'}">${data.cloudflare_detection.behind_cloudflare ? 'نعم' : 'لا'}</span></li>
                                    <li><strong>يستخدم HTTPS:</strong> <span class="${data.cloudflare_detection.using_https ? 'status-success' : 'status-error'}">${data.cloudflare_detection.using_https ? 'نعم' : 'لا'}</span></li>
                                    <li><strong>IP الحقيقي:</strong> ${data.cloudflare_detection.real_visitor_ip}</li>
                                    <li><strong>البلد:</strong> ${data.cloudflare_detection.visitor_country || 'غير محدد'}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Cloudflare Headers
            if (data.cloudflare_headers.cf_ray || data.cloudflare_headers.cf_connecting_ip) {
                html += `
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="bi bi-server"></i> Cloudflare Headers</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                `;
                
                Object.keys(data.cloudflare_headers).forEach(key => {
                    if (data.cloudflare_headers[key]) {
                        html += `
                            <div class="col-md-6 mb-2">
                                <strong>${key}:</strong> ${data.cloudflare_headers[key]}
                            </div>
                        `;
                    }
                });
                
                html += `</div></div></div>`;
            }
            
            // Recommendations
            if (data.recommendations && data.recommendations.length > 0) {
                html += `
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> التوصيات</h5>
                        </div>
                        <div class="card-body">
                            <ul>
                `;
                
                data.recommendations.forEach(rec => {
                    html += `<li>${rec}</li>`;
                });
                
                html += `</ul></div></div>`;
            }
            
            // Full JSON Data
            html += `
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-code"></i> البيانات الكاملة (JSON)</h5>
                    </div>
                    <div class="card-body">
                        <div class="json-container">
                            <button class="btn btn-sm btn-outline-secondary copy-btn" onclick="copyToClipboard('jsonData')">
                                <i class="bi bi-clipboard"></i> نسخ
                            </button>
                            <div id="jsonData" class="json-block">${JSON.stringify(data, null, 2)}</div>
                        </div>
                    </div>
                </div>
            `;
            
            resultsDiv.innerHTML = html;
        }
        
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                alert('تم نسخ البيانات!');
            }).catch(err => {
                console.error('فشل في النسخ: ', err);
            });
        }
    </script>
</body>
</html>
