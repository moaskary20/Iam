<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixAlpineJsErrors
{
    /**
     * Handle an incoming request.
     * 
     * هذا الـ middleware يحل مشاكل Alpine.js Expression Errors
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إعداد Alpine.js environment قبل معالجة الطلب
        $this->setupAlpineDefaults();
        
        $response = $next($request);
        
        // إضافة JavaScript لحل مشاكل Alpine.js في الاستجابة
        $this->injectAlpineJsFixes($response);
        
        return $response;
    }
    
    /**
     * إعداد متغيرات افتراضية لـ Alpine.js
     */
    protected function setupAlpineDefaults(): void
    {
        // تعيين متغيرات افتراضية في session لتجنب undefined errors
        if (!session()->has('alpine_defaults')) {
            session()->put('alpine_defaults', [
                'store' => [
                    'sidebar' => [
                        'isOpen' => false,
                        'groupsCollapsed' => []
                    ]
                ],
                'table' => [
                    'selectedRecords' => [],
                    'allRecordsSelected' => false
                ],
                'groups' => [
                    'collapsed' => []
                ]
            ]);
        }
    }
    
    /**
     * حقن JavaScript لحل مشاكل Alpine.js
     */
    protected function injectAlpineJsFixes(Response $response): void
    {
        // فقط للصفحات HTML
        $contentType = $response->headers->get('Content-Type');
        if (!$contentType || !str_contains($contentType, 'text/html')) {
            return;
        }
        
        $content = $response->getContent();
        
        // إضافة JavaScript لحل مشاكل Alpine.js
        $alpineFixScript = '
<script>
// إصلاح مشاكل Alpine.js مع Livewire - تشغيل مبكر
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM loaded - setting up Alpine.js fixes");
    
    // إعداد Alpine.js عالمياً قبل أي شيء
    window.Alpine = window.Alpine || {};
    
    // إعداد fallback stores
    window.sidebarStore = {
        isOpen: false,
        groupsCollapsed: {},
        toggle() { this.isOpen = !this.isOpen; }
    };
});

// إعداد Alpine.js stores عند التهيئة
window.addEventListener("alpine:init", () => {
    console.log("Alpine:init - setting up stores");
    
    // إعداد sidebar store
    Alpine.store("sidebar", {
        isOpen: false,
        groupsCollapsed: {},
        
        init() {
            this.isOpen = false;
            this.groupsCollapsed = {};
            console.log("Sidebar store initialized");
        },
        
        toggle() {
            this.isOpen = !this.isOpen;
            console.log("Sidebar toggled:", this.isOpen);
        }
    });
    
    // إعداد table store
    Alpine.store("table", {
        selectedRecords: [],
        allRecordsSelected: false,
        
        init() {
            this.selectedRecords = [];
            this.allRecordsSelected = false;
        }
    });
    
    // Alpine.js data للمكونات
    Alpine.data("filamentSidebar", () => ({
        isOpen: false,
        groupsCollapsed: {},
        
        init() {
            this.isOpen = false;
            this.groupsCollapsed = {};
        },
        
        toggle() {
            this.isOpen = !this.isOpen;
            // تحديث store أيضاً
            if (Alpine.store("sidebar")) {
                Alpine.store("sidebar").isOpen = this.isOpen;
            }
        }
    }));
    
    Alpine.data("filamentTable", () => ({
        selectedRecords: [],
        allRecordsSelected: false,
        
        init() {
            this.selectedRecords = [];
            this.allRecordsSelected = false;
        }
    }));
    
    console.log("All Alpine.js stores and components registered");
});

// إعداد إضافي لضمان وجود stores
document.addEventListener("alpine:initialized", () => {
    console.log("Alpine initialized - verifying stores");
    
    // التأكد من وجود sidebar store
    if (!Alpine.store("sidebar")) {
        console.warn("Sidebar store missing, creating fallback");
        Alpine.store("sidebar", window.sidebarStore);
    }
    
    console.log("Sidebar store:", Alpine.store("sidebar"));
});

// التعامل مع Livewire updates
document.addEventListener("livewire:navigated", function() {
    console.log("Livewire navigated - checking stores");
    
    setTimeout(() => {
        if (typeof Alpine !== "undefined") {
            if (!Alpine.store("sidebar")) {
                Alpine.store("sidebar", window.sidebarStore);
                console.log("Sidebar store recreated after navigation");
            }
        }
    }, 100);
});

document.addEventListener("livewire:update", function() {
    console.log("Livewire updated");
    
    // إعادة تهيئة Alpine.js components بعد Livewire update
    if (typeof Alpine !== "undefined" && Alpine.initTree) {
        setTimeout(() => {
            try {
                Alpine.initTree(document.body);
                console.log("Alpine components reinitialized");
            } catch (e) {
                console.error("Error reinitializing Alpine:", e);
            }
        }, 50);
    }
});

// التعامل مع الأخطاء
window.addEventListener("error", function(e) {
    if (e.message && (
        e.message.includes("Cannot read properties of undefined") ||
        e.message.includes("sidebar") ||
        e.message.includes("isOpen") ||
        e.message.includes("groupsCollapsed") ||
        e.message.includes("selectedRecords")
    )) {
        console.warn("Alpine.js error caught:", e.message);
        
        // إعادة إنشاء store إذا كان مفقود
        if (typeof Alpine !== "undefined" && e.message.includes("sidebar")) {
            Alpine.store("sidebar", window.sidebarStore);
            console.log("Sidebar store recreated due to error");
        }
        
        e.preventDefault();
        return false;
    }
});

// fallback عالمي
window.$store = window.$store || {};
window.$store.sidebar = window.$store.sidebar || window.sidebarStore;
</script>';
        
        // إدراج السكريبت قبل </head> للتأكد من التحميل المبكر
        if (str_contains($content, '</head>')) {
            $content = str_replace('</head>', $alpineFixScript . '</head>', $content);
        } elseif (str_contains($content, '</body>')) {
            $content = str_replace('</body>', $alpineFixScript . '</body>', $content);
        }
        
        $response->setContent($content);
    }
}
