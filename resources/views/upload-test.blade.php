<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار رفع الملفات - حل المشكلة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .status-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .status-error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status-info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .upload-area {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: #4299e1;
            background-color: #ebf8ff;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">🚀 اختبار رفع الملفات الشامل</h1>
                <p class="text-gray-600">حل جذري لجميع مشاكل رفع الصور</p>
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>المستخدم:</strong> {{ auth()->user()->name ?? 'غير محدد' }} | 
                        <strong>البريد:</strong> {{ auth()->user()->email ?? 'غير محدد' }} |
                        <strong>ID:</strong> {{ auth()->user()->id ?? 'غير محدد' }} |
                        <strong>الوقت:</strong> {{ now()->format('Y-m-d H:i:s') }}
                    </p>
                    @if(!auth()->check())
                        <div class="mt-3 p-3 bg-red-100 rounded-lg">
                            <p class="text-red-800 font-medium">⚠️ تحذير: أنت غير مسجل دخول!</p>
                            <a href="{{ route('quick.login') }}" class="inline-block mt-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                🔐 تسجيل دخول سريع
                            </a>
                        </div>
                    @else
                        <div class="mt-3 p-3 bg-green-100 rounded-lg">
                            <p class="text-green-800 font-medium">✅ أنت مسجل دخول بنجاح!</p>
                            <p class="text-sm text-green-700">يمكنك الآن استخدام جميع ميزات رفع الملفات</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📊 معلومات النظام</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <button onclick="loadSystemInfo()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 w-full">
                            🔍 تحميل معلومات النظام
                        </button>
                        <div id="system-info-result" class="mt-4"></div>
                    </div>
                    <div id="system-info-display" class="bg-gray-50 p-4 rounded-lg text-sm min-h-32"></div>
                </div>
            </div>

            <!-- Test Routes -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🔗 اختبار Routes</h2>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button onclick="testRoute('/upload/test')" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                اختبار Upload Test
                            </button>
                            <button onclick="testRoute('/livewire/upload-file-test', 'GET')" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
                                اختبار Livewire GET
                            </button>
                            <button onclick="testSimpleUpload()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                                اختبار Simple Upload
                            </button>
                        </div>
                <div id="route-test-result" class="mt-4"></div>
            </div>

            <!-- Single File Upload -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📁 رفع ملف واحد</h2>
                <form id="single-upload-form" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اختر صورة:</label>
                            <input type="file" name="file" id="single-file" 
                                   accept="image/*" 
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المجلد:</label>
                                <input type="text" name="directory" value="test-uploads" 
                                       class="w-full p-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البادئة:</label>
                                <input type="text" name="prefix" value="test" 
                                       class="w-full p-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" name="resize" value="1" class="ml-2">
                                    <span class="text-sm text-gray-700">تصغير الصورة</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-medium w-full md:w-auto">
                            🚀 رفع الملف
                        </button>
                    </div>
                </form>
                <div id="single-upload-result" class="mt-4"></div>
            </div>

            <!-- Multiple Files Upload -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📚 رفع ملفات متعددة</h2>
                <form id="multiple-upload-form" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اختر صور متعددة:</label>
                            <input type="file" name="files[]" id="multiple-files" 
                                   accept="image/*" multiple
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 font-medium w-full md:w-auto">
                            📤 رفع الملفات
                        </button>
                    </div>
                </form>
                <div id="multiple-upload-result" class="mt-4"></div>
            </div>

            <!-- Livewire Upload Test -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">⚡ اختبار Livewire Upload</h2>
                <form id="livewire-upload-form" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اختبار رفع Livewire:</label>
                            <input type="file" name="files[]" id="livewire-files" 
                                   accept="image/*" multiple
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div class="flex space-x-4 flex-wrap gap-2">
                            <button type="submit" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 font-medium">
                                ⚡ اختبار Livewire العادي
                            </button>
                            <button type="button" onclick="testLivewireOriginal()" class="bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600 font-medium">
                                🔌 اختبار Livewire الأصلي
                            </button>
                            <button type="button" onclick="testLivewireEnhanced()" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-medium">
                                🚀 اختبار Livewire المحسن
                            </button>
                            <button type="button" onclick="testLivewireNoAuth()" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 font-medium">
                                🔓 اختبار بدون مصادقة
                            </button>
                        </div>
                    </div>
                </form>
                <div id="livewire-upload-result" class="mt-4"></div>
            </div>

            <!-- Drag & Drop -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🎯 رفع بالسحب والإفلات</h2>
                <div class="upload-area p-8 text-center rounded-lg border-2 border-dashed border-gray-300" 
                     id="drag-drop-area"
                     ondrop="dropHandler(event)" 
                     ondragover="dragOverHandler(event)"
                     onclick="document.getElementById('drag-drop-input').click()">
                    <div class="text-gray-500 text-4xl mb-4">📁</div>
                    <p class="text-gray-600 text-lg">اسحب الصور هنا أو اضغط للاختيار</p>
                    <p class="text-sm text-gray-500 mt-2">يدعم JPG, PNG, GIF, WEBP حتى 50MB</p>
                    <input type="file" id="drag-drop-input" multiple accept="image/*" style="display: none;">
                </div>
                <div id="drag-drop-result" class="mt-4"></div>
            </div>

            <!-- Management Tools -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🛠️ أدوات الإدارة</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <button onclick="cleanupTempFiles()" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                        🧹 تنظيف الملفات المؤقتة
                    </button>
                    <button onclick="testAllRoutes()" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600">
                        🔍 اختبار جميع Routes
                    </button>
                    <button onclick="checkPermissions()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        🔒 فحص الصلاحيات
                    </button>
                    <button onclick="getLivewireInfo()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        📊 معلومات Livewire
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <button onclick="location.reload()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        🔄 تحديث الصفحة
                    </button>
                    <button onclick="runLivewireFixCommand()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                        🔧 تشغيل أمر الإصلاح
                    </button>
                    @if(!auth()->check())
                    <button onclick="autoLogin()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        🔐 تسجيل دخول تلقائي
                    </button>
                    @else
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 w-full">
                            🚪 تسجيل خروج
                        </button>
                    </form>
                    @endif
                </div>
                <div id="management-result" class="mt-4"></div>
            </div>

            <!-- Activity Log -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📋 سجل الأنشطة</h2>
                <div id="activity-log" class="bg-black text-green-400 p-4 rounded-lg font-mono text-sm h-64 overflow-y-auto">
                    <div>[INFO] صفحة اختبار رفع الملفات جاهزة</div>
                </div>
                <div class="mt-4 flex space-x-4">
                    <button onclick="clearLog()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        🗑️ مسح السجل
                    </button>
                    <button onclick="downloadLog()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        💾 تحميل السجل
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // إعداد CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // دالة إضافة لوج
        function addLog(message, type = 'INFO') {
            const log = document.getElementById('activity-log');
            const timestamp = new Date().toLocaleTimeString('ar-SA');
            const typeColors = {
                'INFO': 'text-green-400',
                'SUCCESS': 'text-blue-400',
                'ERROR': 'text-red-400',
                'WARNING': 'text-yellow-400'
            };
            
            const logEntry = document.createElement('div');
            logEntry.className = typeColors[type] || 'text-green-400';
            logEntry.textContent = `[${type}] ${timestamp}: ${message}`;
            
            log.appendChild(logEntry);
            log.scrollTop = log.scrollHeight;
        }

        // دالة عرض النتائج
        function showResult(elementId, success, message, data = null) {
            const element = document.getElementById(elementId);
            const statusClass = success ? 'status-success' : 'status-error';
            
            let html = `<div class="${statusClass} p-4 rounded-lg">
                <p class="font-medium">${success ? '✅' : '❌'} ${message}</p>`;
            
            if (data) {
                html += `<details class="mt-3">
                    <summary class="cursor-pointer text-sm opacity-75">عرض التفاصيل</summary>
                    <pre class="mt-2 text-xs bg-gray-100 p-2 rounded overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>
                </details>`;
            }
            
            html += '</div>';
            element.innerHTML = html;
        }

        // تحميل معلومات النظام
        function loadSystemInfo() {
            addLog('تحميل معلومات النظام...', 'INFO');
            
            $.get('/upload/test')
                .done(function(response) {
                    if (response.success) {
                        const data = response.test_data;
                        document.getElementById('system-info-display').innerHTML = `
                            <h4 class="font-bold mb-3 text-green-600">✅ معلومات النظام:</h4>
                            <div class="space-y-2">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div><strong>Upload Max:</strong> ${data.php_settings.upload_max_filesize}</div>
                                    <div><strong>Post Max:</strong> ${data.php_settings.post_max_size}</div>
                                    <div><strong>Memory:</strong> ${data.php_settings.memory_limit}</div>
                                    <div><strong>Time:</strong> ${data.php_settings.max_execution_time}s</div>
                                </div>
                                <div class="mt-3">
                                    <strong>التخزين:</strong>
                                    <span class="${data.storage_info.storage_linked ? 'text-green-600' : 'text-red-600'}">
                                        ${data.storage_info.storage_linked ? '✅' : '❌'} Linked
                                    </span>
                                    <span class="${data.storage_info.permissions.storage_writable ? 'text-green-600' : 'text-red-600'}">
                                        ${data.storage_info.permissions.storage_writable ? '✅' : '❌'} Writable
                                    </span>
                                </div>
                                <div>
                                    <strong>المستخدم:</strong> 
                                    <span class="${data.user_info.authenticated ? 'text-green-600' : 'text-red-600'}">
                                        ${data.user_info.authenticated ? '✅ مسجل الدخول' : '❌ غير مسجل'}
                                    </span>
                                </div>
                            </div>
                        `;
                        showResult('system-info-result', true, 'تم تحميل معلومات النظام بنجاح');
                        addLog('تم تحميل معلومات النظام بنجاح', 'SUCCESS');
                    }
                })
                .fail(function(xhr) {
                    showResult('system-info-result', false, 'فشل في تحميل معلومات النظام');
                    addLog(`فشل في تحميل معلومات النظام: ${xhr.statusText}`, 'ERROR');
                });
        }

        // اختبار route
        function testRoute(url, method = 'GET') {
            addLog(`اختبار ${method} ${url}...`, 'INFO');
            
            $.ajax({
                url: url,
                method: method,
                success: function(response) {
                    showResult('route-test-result', true, `${url} يعمل بنجاح`, response);
                    addLog(`${url} يعمل بنجاح`, 'SUCCESS');
                },
                error: function(xhr) {
                    showResult('route-test-result', false, `${url} لا يعمل: ${xhr.statusText}`);
                    addLog(`${url} فشل: ${xhr.status} ${xhr.statusText}`, 'ERROR');
                }
            });
        }

        // اختبار Simple Upload
        function testSimpleUpload() {
            addLog('اختبار Simple Upload endpoint...', 'INFO');
            
            $.get('/simple-upload-test')
                .done(function(response) {
                    if (response.success) {
                        showResult('route-test-result', true, 'Simple Upload endpoint يعمل بنجاح', response);
                        addLog('Simple Upload endpoint يعمل بنجاح', 'SUCCESS');
                    }
                })
                .fail(function(xhr) {
                    showResult('route-test-result', false, `Simple Upload endpoint لا يعمل: ${xhr.statusText}`);
                    addLog(`Simple Upload endpoint فشل: ${xhr.status} ${xhr.statusText}`, 'ERROR');
                });
        }

        // رفع ملف واحد
        $('#single-upload-form').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const file = $('#single-file')[0].files[0];
            
            if (!file) {
                showResult('single-upload-result', false, 'يجب اختيار ملف أولاً');
                addLog('لم يتم اختيار ملف للرفع', 'WARNING');
                return;
            }
            
            addLog(`بدء رفع الملف: ${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`, 'INFO');
            
            $.ajax({
                url: '/upload/single',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showResult('single-upload-result', true, 'تم رفع الملف بنجاح', response);
                        addLog(`تم رفع الملف بنجاح: ${response.filename}`, 'SUCCESS');
                    } else {
                        showResult('single-upload-result', false, response.error || 'فشل في رفع الملف');
                        addLog(`فشل في رفع الملف: ${response.error}`, 'ERROR');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('single-upload-result', false, `خطأ في الرفع: ${error}`);
                    addLog(`خطأ في رفع الملف: ${error}`, 'ERROR');
                }
            });
        });

        // رفع ملفات متعددة
        $('#multiple-upload-form').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const files = $('#multiple-files')[0].files;
            
            if (files.length === 0) {
                showResult('multiple-upload-result', false, 'يجب اختيار ملفات أولاً');
                addLog('لم يتم اختيار ملفات للرفع', 'WARNING');
                return;
            }
            
            addLog(`بدء رفع ${files.length} ملف...`, 'INFO');
            
            $.ajax({
                url: '/upload/multiple',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showResult('multiple-upload-result', true, `تم رفع ${response.uploaded_files.length} ملف بنجاح`, response);
                        addLog(`تم رفع ${response.uploaded_files.length} ملف بنجاح`, 'SUCCESS');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('multiple-upload-result', false, `خطأ في الرفع: ${error}`);
                    addLog(`خطأ في رفع الملفات: ${error}`, 'ERROR');
                }
            });
        });

        // اختبار Livewire العادي
        $('#livewire-upload-form').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const files = $('#livewire-files')[0].files;
            
            if (files.length === 0) {
                showResult('livewire-upload-result', false, 'يجب اختيار ملفات أولاً');
                return;
            }
            
            addLog(`اختبار رفع Livewire لـ ${files.length} ملف...`, 'INFO');
            
            $.ajax({
                url: '/livewire/upload-file-custom',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showResult('livewire-upload-result', true, 'اختبار Livewire نجح', response);
                    addLog('اختبار Livewire نجح', 'SUCCESS');
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('livewire-upload-result', false, `فشل اختبار Livewire: ${error}`);
                    addLog(`فشل اختبار Livewire: ${error}`, 'ERROR');
                }
            });
        });

        // اختبار Livewire الأصلي
        function testLivewireOriginal() {
            const files = $('#livewire-files')[0].files;
            
            if (files.length === 0) {
                showResult('livewire-upload-result', false, 'يجب اختيار ملفات أولاً');
                return;
            }
            
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }
            
            addLog(`اختبار Livewire الأصلي لـ ${files.length} ملف...`, 'INFO');
            
            $.ajax({
                url: '/livewire/upload-file',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showResult('livewire-upload-result', true, 'اختبار Livewire الأصلي نجح', response);
                    addLog('اختبار Livewire الأصلي نجح', 'SUCCESS');
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('livewire-upload-result', false, `فشل اختبار Livewire الأصلي: ${error}`);
                    addLog(`فشل اختبار Livewire الأصلي: ${error}`, 'ERROR');
                }
            });
        }

        // اختبار Livewire المحسن الجديد
        function testLivewireEnhanced() {
            const files = $('#livewire-files')[0].files;
            
            if (files.length === 0) {
                showResult('livewire-upload-result', false, 'يجب اختيار ملفات أولاً');
                return;
            }
            
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }
            
            addLog(`اختبار Livewire المحسن لـ ${files.length} ملف...`, 'INFO');
            
            $.ajax({
                url: '/livewire/upload-enhanced',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showResult('livewire-upload-result', true, 'اختبار Livewire المحسن نجح', response);
                    addLog('اختبار Livewire المحسن نجح', 'SUCCESS');
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('livewire-upload-result', false, `فشل اختبار Livewire المحسن: ${error}`);
                    addLog(`فشل اختبار Livewire المحسن: ${error}`, 'ERROR');
                }
            });
        }

        // اختبار Livewire بدون مصادقة
        function testLivewireNoAuth() {
            const files = $('#livewire-files')[0].files;
            
            if (files.length === 0) {
                showResult('livewire-upload-result', false, 'يجب اختيار ملفات أولاً');
                return;
            }
            
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }
            
            addLog(`🔓 اختبار Livewire بدون مصادقة لـ ${files.length} ملف...`, 'INFO');
            
            $.ajax({
                url: '/livewire/upload-file-noauth',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showResult('livewire-upload-result', true, '🎉 اختبار Livewire بدون مصادقة نجح!', response);
                    addLog('✅ اختبار Livewire بدون مصادقة نجح!', 'SUCCESS');
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('livewire-upload-result', false, `❌ فشل اختبار Livewire بدون مصادقة: ${error}`);
                    addLog(`❌ فشل اختبار Livewire بدون مصادقة: ${error}`, 'ERROR');
                }
            });
        }

        // Drag & Drop
        function dragOverHandler(ev) {
            ev.preventDefault();
            ev.currentTarget.style.backgroundColor = '#ebf8ff';
        }

        function dropHandler(ev) {
            ev.preventDefault();
            ev.currentTarget.style.backgroundColor = '';
            
            const files = ev.dataTransfer.files;
            uploadDragDropFiles(files);
        }

        function uploadDragDropFiles(files) {
            if (files.length === 0) return;
            
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }
            formData.append('directory', 'drag-drop-uploads');
            
            addLog(`رفع ${files.length} ملف بالسحب والإفلات...`, 'INFO');
            
            $.ajax({
                url: '/upload/multiple',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showResult('drag-drop-result', true, `تم رفع ${response.uploaded_files.length} ملف بنجاح`);
                        addLog(`تم رفع ${response.uploaded_files.length} ملف بالسحب والإفلات`, 'SUCCESS');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || xhr.statusText;
                    showResult('drag-drop-result', false, `خطأ في الرفع: ${error}`);
                    addLog(`خطأ في رفع الملفات: ${error}`, 'ERROR');
                }
            });
        }

        // الحصول على معلومات Livewire
        function getLivewireInfo() {
            addLog('جلب معلومات Livewire...', 'INFO');
            
            $.get('/livewire/upload-info')
                .done(function(response) {
                    if (response.success) {
                        showResult('management-result', true, 'تم جلب معلومات Livewire', response.info);
                        addLog('تم جلب معلومات Livewire بنجاح', 'SUCCESS');
                    }
                })
                .fail(function(xhr) {
                    showResult('management-result', false, 'فشل في جلب معلومات Livewire');
                    addLog(`فشل في جلب معلومات Livewire: ${xhr.statusText}`, 'ERROR');
                });
        }

        // تشغيل أمر إصلاح Livewire (محاكاة)
        function runLivewireFixCommand() {
            addLog('محاولة تشغيل أمر الإصلاح...', 'INFO');
            addLog('ملاحظة: هذا أمر console يجب تشغيله من Terminal:', 'WARNING');
            addLog('php artisan livewire:fix-uploads', 'INFO');
            
            showResult('management-result', true, 'أمر الإصلاح', {
                command: 'php artisan livewire:fix-uploads',
                note: 'يجب تشغيل هذا الأمر من Terminal'
            });
        }

        // تسجيل دخول تلقائي
        function autoLogin() {
            addLog('بدء تسجيل دخول تلقائي...', 'INFO');
            
            $.post('/auto-login', {
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function() {
                addLog('تم تسجيل الدخول بنجاح!', 'SUCCESS');
                location.reload();
            })
            .fail(function() {
                addLog('فشل في تسجيل الدخول التلقائي', 'ERROR');
                window.open('/quick-login', '_blank');
            });
        }

        // تحديث تنظيف الملفات المؤقتة لتستخدم endpoint Livewire الجديد
        function cleanupTempFiles() {
            addLog('بدء تنظيف الملفات المؤقتة...', 'INFO');
            
            $.post('/livewire/cleanup', {hours: 24})
                .done(function(response) {
                    if (response.success) {
                        showResult('management-result', true, `تم حذف ${response.deleted_count} ملف مؤقت`);
                        addLog(`تم حذف ${response.deleted_count} ملف مؤقت`, 'SUCCESS');
                    }
                })
                .fail(function(xhr) {
                    // جرب الـ endpoint القديم
                    $.post('/upload/cleanup', {hours: 24})
                        .done(function(response) {
                            if (response.success) {
                                showResult('management-result', true, `تم حذف ${response.deleted_count} ملف مؤقت`);
                                addLog(`تم حذف ${response.deleted_count} ملف مؤقت (endpoint قديم)`, 'SUCCESS');
                            }
                        })
                        .fail(function(xhr2) {
                            showResult('management-result', false, 'فشل في تنظيف الملفات المؤقتة');
                            addLog('فشل في تنظيف الملفات المؤقتة', 'ERROR');
                        });
                });
        }

        // اختبار جميع Routes
        function testAllRoutes() {
            addLog('بدء اختبار جميع Routes...', 'INFO');
            const routes = [
                {url: '/upload/test', method: 'GET'},
                {url: '/livewire/upload-file-test', method: 'GET'},
                {url: '/simple-upload-test', method: 'GET'},
                {url: '/livewire/upload-enhanced', method: 'GET'},
                {url: '/livewire/upload-info', method: 'GET'}
            ];
            
            let results = [];
            let completed = 0;
            
            routes.forEach(route => {
                $.ajax({
                    url: route.url,
                    method: route.method,
                    success: function() {
                        results.push(`✅ ${route.method} ${route.url}`);
                        addLog(`✅ ${route.method} ${route.url}`, 'SUCCESS');
                    },
                    error: function(xhr) {
                        results.push(`❌ ${route.method} ${route.url} (${xhr.status})`);
                        addLog(`❌ ${route.method} ${route.url} (${xhr.status})`, 'ERROR');
                    },
                    complete: function() {
                        completed++;
                        if (completed === routes.length) {
                            showResult('management-result', true, `اختبار Routes مكتمل`, {results: results});
                        }
                    }
                });
            });
        }

        // فحص الصلاحيات
        function checkPermissions() {
            addLog('فحص صلاحيات الملفات...', 'INFO');
            
            // هذا اختبار تقريبي - في الواقع نحتاج endpoint خاص
            $.get('/upload/test')
                .done(function(response) {
                    if (response.success) {
                        const storage = response.test_data.storage_info;
                        let permissionInfo = `
                            Storage Linked: ${storage.storage_linked ? '✅' : '❌'}<br>
                            Storage Writable: ${storage.permissions.storage_writable ? '✅' : '❌'}<br>
                            Livewire Tmp Writable: ${storage.permissions.livewire_tmp_writable ? '✅' : '❌'}
                        `;
                        
                        showResult('management-result', true, 'فحص الصلاحيات مكتمل', {permission_info: permissionInfo});
                        addLog('فحص الصلاحيات مكتمل', 'SUCCESS');
                    }
                })
                .fail(function() {
                    showResult('management-result', false, 'فشل في فحص الصلاحيات');
                    addLog('فشل في فحص الصلاحيات', 'ERROR');
                });
        }

        // إدارة السجل
        function clearLog() {
            document.getElementById('activity-log').innerHTML = '<div class="text-green-400">[INFO] تم مسح السجل</div>';
        }

        function downloadLog() {
            const log = document.getElementById('activity-log').innerText;
            const blob = new Blob([log], {type: 'text/plain'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'upload-test-log-' + new Date().toISOString().slice(0,19).replace(/:/g, '-') + '.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            addLog('تم تحميل السجل', 'SUCCESS');
        }

        // Drag & Drop for input
        document.getElementById('drag-drop-input').addEventListener('change', function(e) {
            uploadDragDropFiles(e.target.files);
        });

        // تحميل معلومات النظام عند تحميل الصفحة
        $(document).ready(function() {
            addLog('تم تحميل صفحة اختبار رفع الملفات', 'SUCCESS');
            addLog(`المستخدم: {{ auth()->user()->name ?? "غير محدد" }}`, 'INFO');
            
            // تحميل معلومات النظام تلقائياً
            setTimeout(loadSystemInfo, 1000);
        });
    </script>
</body>
</html>
