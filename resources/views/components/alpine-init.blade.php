{{-- Alpine.js Store Initialization --}}
<script>
// إعداد Alpine.js stores قبل تحميل Alpine
(function() {
    'use strict';
    
    console.log('Initializing Alpine.js stores...');
    
    // إعداد stores عالمياً
    window.alpineStores = {
        sidebar: {
            isOpen: false,
            groupsCollapsed: {},
            
            init() {
                console.log('Sidebar store initialized');
                this.isOpen = false;
                this.groupsCollapsed = {};
            },
            
            toggle() {
                this.isOpen = !this.isOpen;
                console.log('Sidebar toggled:', this.isOpen);
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
    
    // إعداد fallback global store
    window.$store = window.$store || {};
    window.$store.sidebar = window.alpineStores.sidebar;
    window.$store.table = window.alpineStores.table;
    
    console.log('Global stores setup complete');
})();

// إعداد Alpine.js عند التهيئة
document.addEventListener('alpine:init', () => {
    console.log('Alpine:init - registering stores');
    
    // تسجيل stores
    Alpine.store('sidebar', window.alpineStores.sidebar);
    Alpine.store('table', window.alpineStores.table);
    
    console.log('Alpine stores registered:', {
        sidebar: Alpine.store('sidebar'),
        table: Alpine.store('table')
    });
});

// التأكد من stores بعد التهيئة
document.addEventListener('alpine:initialized', () => {
    console.log('Alpine initialized - verifying stores');
    
    if (!Alpine.store('sidebar')) {
        console.error('Sidebar store missing after initialization!');
        Alpine.store('sidebar', window.alpineStores.sidebar);
    }
    
    console.log('Final store verification:', Alpine.store('sidebar'));
});
</script>
