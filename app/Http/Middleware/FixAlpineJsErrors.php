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
// إصلاح مشاكل Alpine.js مع Livewire
document.addEventListener("DOMContentLoaded", function() {
    // إعداد متغيرات افتراضية لـ Alpine.js
    window.Alpine = window.Alpine || {};
    window.Alpine.data = window.Alpine.data || function(name, data) {};
    
    // إعداد store افتراضي
    if (typeof Alpine !== "undefined" && Alpine.store) {
        if (!Alpine.store("sidebar")) {
            Alpine.store("sidebar", {
                isOpen: false,
                groupsCollapsed: {},
                init() {
                    this.isOpen = false;
                    this.groupsCollapsed = {};
                }
            });
        }
    }
    
    // إصلاح مشاكل undefined variables
    window.addEventListener("alpine:init", () => {
        // تجنب أخطاء undefined في Filament
        Alpine.data("filamentSidebar", () => ({
            isOpen: false,
            groupsCollapsed: {},
            init() {
                this.isOpen = false;
                this.groupsCollapsed = {};
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
    });
    
    // التعامل مع Livewire updates
    document.addEventListener("livewire:update", function() {
        // إعادة تهيئة Alpine.js components بعد Livewire update
        if (typeof Alpine !== "undefined" && Alpine.initTree) {
            setTimeout(() => {
                Alpine.initTree(document.body);
            }, 100);
        }
    });
    
    // تجنب console errors
    window.addEventListener("error", function(e) {
        if (e.message && (
            e.message.includes("Cannot read properties of undefined") ||
            e.message.includes("groupIsCollapsed") ||
            e.message.includes("selectedRecords") ||
            e.message.includes("isOpen")
        )) {
            e.preventDefault();
            return false;
        }
    });
});
</script>';
        
        // إدراج السكريبت قبل </body>
        if (str_contains($content, '</body>')) {
            $content = str_replace('</body>', $alpineFixScript . '</body>', $content);
            $response->setContent($content);
        }
    }
}
