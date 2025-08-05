// Safe JavaScript loader - مقاوم للـ AdBlocker
(function() {
    'use strict';
    
    console.log('Safe JS Loader started');
    
    // إعداد Alpine.js stores بطريقة آمنة
    window.safeStores = {
        sidebar: {
            isOpen: false,
            groupsCollapsed: {},
            
            init() {
                this.isOpen = false;
                this.groupsCollapsed = {};
                console.log('Safe sidebar store initialized');
            },
            
            toggle() {
                this.isOpen = !this.isOpen;
                console.log('Sidebar toggled safely:', this.isOpen);
            }
        },
        
        table: {
            selectedRecords: [],
            allRecordsSelected: false,
            
            init() {
                this.selectedRecords = [];
                this.allRecordsSelected = false;
            }
        }
    };
    
    // إعداد fallback stores
    window.$store = window.$store || {};
    window.$store.sidebar = window.safeStores.sidebar;
    window.$store.table = window.safeStores.table;
    
    // إعداد Alpine.js عند التهيئة
    document.addEventListener('alpine:init', function() {
        console.log('Alpine init - setting up safe stores');
        
        if (typeof Alpine !== 'undefined') {
            Alpine.store('sidebar', window.safeStores.sidebar);
            Alpine.store('table', window.safeStores.table);
            
            console.log('Safe stores registered with Alpine');
        }
    });
    
    // التأكد من stores بعد التهيئة
    document.addEventListener('alpine:initialized', function() {
        console.log('Alpine initialized - verifying safe stores');
        
        if (typeof Alpine !== 'undefined') {
            if (!Alpine.store('sidebar')) {
                Alpine.store('sidebar', window.safeStores.sidebar);
                console.log('Sidebar store restored');
            }
        }
    });
    
    // إعداد Livewire fallback
    window.livewireFallback = function() {
        if (typeof Livewire === 'undefined') {
            console.warn('Livewire not loaded, using fallback');
            
            window.Livewire = {
                emit: function(event, data) {
                    console.log('Livewire emit fallback:', event, data);
                },
                on: function(event, callback) {
                    console.log('Livewire on fallback:', event);
                }
            };
        }
    };
    
    // تشغيل fallback بعد تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(window.livewireFallback, 1000);
    });
    
    // Error handler آمن
    window.addEventListener('error', function(e) {
        if (e.message && (
            e.message.includes('Cannot read properties of undefined') ||
            e.message.includes('sidebar') ||
            e.message.includes('isOpen')
        )) {
            console.warn('Safe error handler caught:', e.message);
            
            // إعادة إنشاء stores
            if (typeof Alpine !== 'undefined') {
                Alpine.store('sidebar', window.safeStores.sidebar);
            }
            
            e.preventDefault();
            return false;
        }
    });
    
    console.log('Safe JS Loader completed');
})();
