// تطبيق حالة النشاط على المنيو المحمول
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.mobile-nav-link');
    
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        
        // إزالة الحالة النشطة من جميع الروابط
        link.classList.remove('active');
        
        // تطبيق الحالة النشطة على الرابط الحالي
        if (linkPath === currentPath || 
            (currentPath === '/' && linkPath === '/') ||
            (currentPath.startsWith('/market') && linkPath.includes('market')) ||
            (currentPath.startsWith('/wallet') && linkPath.includes('wallet')) ||
            (currentPath.startsWith('/profile') && linkPath.includes('profile')) ||
            (currentPath.startsWith('/statistics') && linkPath.includes('statistics'))) {
            link.classList.add('active');
        }
    });
    
    // إضافة تأثيرات التفاعل
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // إزالة الحالة النشطة من جميع الروابط
            navLinks.forEach(l => l.classList.remove('active'));
            // إضافة الحالة النشطة للرابط المضغوط
            this.classList.add('active');
        });
    });
});
