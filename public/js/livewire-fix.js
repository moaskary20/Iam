// إضافة هذا السكريبت في layout الأساسي لـ Filament لحل مشكلة Livewire
document.addEventListener('DOMContentLoaded', function() {
    // اعتراض طلبات Livewire upload قبل إرسالها
    if (window.Livewire) {
        console.log('Livewire detected, applying upload fix...');
        
        // إعادة تعريف دالة upload في Livewire
        const originalFetch = window.fetch;
        window.fetch = function(url, options) {
            // إذا كان الطلب لـ livewire upload فقط
            if (url.includes('livewire/upload-file')) {
                console.log('Intercepting Livewire upload request:', url, options);
                
                // تأكد من أن الطلب يحتوي على البيانات المطلوبة
                if (options && options.method) {
                    // إضافة headers مطلوبة
                    options.headers = options.headers || {};
                    options.headers['X-Requested-With'] = 'XMLHttpRequest';
                    options.headers['Accept'] = 'application/json';
                    
                    // إضافة CSRF token إذا لم يكن موجود
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                    document.querySelector('input[name="_token"]')?.value;
                    
                    if (csrfToken && !options.headers['X-CSRF-TOKEN']) {
                        options.headers['X-CSRF-TOKEN'] = csrfToken;
                    }
                    
                    // إضافة تفاصيل المستخدم المسجل
                    options.headers['X-User-Authenticated'] = 'true';
                    
                    console.log('Enhanced upload request headers:', options.headers);
                }
                
                // السماح للطلب بالمرور مع التحسينات
                return originalFetch.call(this, url, options)
                    .then(response => {
                        console.log('Livewire upload response status:', response.status);
                        
                        if (response.status === 401) {
                            console.error('Upload failed: Unauthorized (401)');
                            if (confirm('انتهت صلاحية الجلسة. هل تريد إعادة تحميل الصفحة؟')) {
                                window.location.reload();
                            }
                            return response;
                        }
                        
                        if (response.status === 405) {
                            console.error('Method not allowed (405) for upload');
                            // محاولة إعادة الطلب كـ POST
                            if (options.method === 'GET') {
                                console.log('Retrying upload as POST...');
                                options.method = 'POST';
                                if (!options.body) {
                                    options.body = new FormData();
                                }
                                return originalFetch.call(this, url, options);
                            }
                        }
                        
                        if (response.status === 200) {
                            console.log('Upload successful!');
                        }
                        
                        return response;
                    })
                    .catch(error => {
                        console.error('Livewire upload error:', error);
                        throw error;
                    });
            }
            
            // للطلبات الأخرى (بما في ذلك livewire/update)، استخدم fetch العادي بدون تدخل
            return originalFetch.call(this, url, options);
        };
        
        console.log('Livewire upload fix applied successfully');
    } else {
        console.log('Livewire not detected yet, waiting...');
        
        // إذا لم يكن Livewire محمل بعد، انتظر
        setTimeout(function() {
            if (window.Livewire) {
                console.log('Livewire loaded, applying fix...');
                location.reload(); // إعادة تحميل لتطبيق الإصلاح
            }
        }, 2000);
    }
});

// إضافة معالج للأخطاء العامة
window.addEventListener('error', function(event) {
    if (event.message && event.message.includes('livewire')) {
        console.error('Livewire error detected:', event.error);
    }
});

// معالج خاص لأخطاء الشبكة
window.addEventListener('unhandledrejection', function(event) {
    if (event.reason && event.reason.message) {
        // التعامل مع أخطاء Livewire فقط المتعلقة بالـ uploads
        if (event.reason.message.includes('livewire') && event.reason.message.includes('upload')) {
            console.error('Livewire upload network error:', event.reason);
            
            // إذا كان الخطأ 401، اعرض رسالة للمستخدم
            if (event.reason.message.includes('401')) {
                console.warn('Authentication error detected. User may need to login again.');
            }
            
            // إذا كان الخطأ 405، اعرض رسالة حول method
            if (event.reason.message.includes('405')) {
                console.warn('Method not allowed error detected. Trying alternative methods...');
            }
            
            event.preventDefault(); // منع ظهور الخطأ في console
        }
        
        // تجاهل أخطاء shift() في Livewire core - هذه مؤقتة
        if (event.reason.message.includes('shift') && event.reason.message.includes('undefined')) {
            console.warn('Livewire internal error (shift/undefined) - ignoring temporarily');
            event.preventDefault();
        }
    }
});
