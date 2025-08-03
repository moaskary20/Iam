// إضافة هذا السكريبت في layout الأساسي لـ Filament لحل مشكلة Livewire
document.addEventListener('DOMContentLoaded', function() {
    // اعتراض طلبات Livewire upload قبل إرسالها
    if (window.Livewire) {
        console.log('Livewire detected, applying upload fix...');
        
        // إعادة تعريف دالة upload في Livewire
        const originalFetch = window.fetch;
        window.fetch = function(url, options) {
            // إذا كان الطلب لـ livewire/upload-file
            if (url.includes('livewire/upload-file')) {
                console.log('Intercepting Livewire upload request:', url, options);
                
                // تأكد من أن الطلب يحتوي على البيانات المطلوبة
                if (options && options.method && options.method.toUpperCase() === 'POST') {
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
                }
                
                // السماح للطلب بالمرور مع التحسينات
                return originalFetch.call(this, url, options)
                    .then(response => {
                        console.log('Livewire upload response:', response.status, response);
                        return response;
                    })
                    .catch(error => {
                        console.error('Livewire upload error:', error);
                        
                        // في حالة الخطأ، جرب طلب بديل
                        if (error.message.includes('405') || error.message.includes('Method Not Allowed')) {
                            console.log('Trying alternative upload method...');
                            
                            // محاولة إرسال الطلب كـ GET أولاً للاختبار
                            return originalFetch.call(this, url, {
                                ...options,
                                method: 'GET',
                                body: null
                            });
                        }
                        
                        throw error;
                    });
            }
            
            // للطلبات الأخرى، استخدم fetch العادي
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
    if (event.reason && event.reason.message && event.reason.message.includes('livewire')) {
        console.error('Livewire network error:', event.reason);
        event.preventDefault(); // منع ظهور الخطأ في console
    }
});
