/**
 * Advanced Mobile Navigation Features
 * Professional animations, gestures, and interactions
 */

// Enhanced Mobile Navigation Class Extension
class AdvancedMobileNav extends MobileNavigation {
    constructor() {
        super();
        this.gestures = new Map();
        this.notifications = new Map();
        this.themes = ['light', 'dark', 'auto'];
        this.currentTheme = 'auto';
        
        this.setupAdvancedFeatures();
    }
    
    setupAdvancedFeatures() {
        this.setupGestureNavigation();
        this.setupNotificationSystem();
        this.setupThemeSupport();
        this.setupVoiceCommands();
        this.setupAdvancedAnimations();
        this.setupContextMenu();
    }
    
    setupGestureNavigation() {
        let startX, startY, startTime;
        let isLongPress = false;
        let longPressTimer;
        
        this.nav.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            startTime = Date.now();
            isLongPress = false;
            
            // Long press detection
            longPressTimer = setTimeout(() => {
                isLongPress = true;
                this.handleLongPress(e);
            }, 500);
        });
        
        this.nav.addEventListener('touchmove', (e) => {
            clearTimeout(longPressTimer);
            
            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const deltaX = currentX - startX;
            const deltaY = currentY - startY;
            
            // Detect swipe gestures
            if (Math.abs(deltaX) > 50 && Math.abs(deltaY) < 30) {
                if (deltaX > 0) {
                    this.handleSwipeRight();
                } else {
                    this.handleSwipeLeft();
                }
            }
        });
        
        this.nav.addEventListener('touchend', (e) => {
            clearTimeout(longPressTimer);
            
            if (isLongPress) {
                e.preventDefault();
                return;
            }
            
            const endTime = Date.now();
            const duration = endTime - startTime;
            
            // Double tap detection
            if (duration < 300) {
                if (this.lastTap && (endTime - this.lastTap) < 300) {
                    this.handleDoubleTap(e);
                }
                this.lastTap = endTime;
            }
        });
    }
    
    handleSwipeLeft() {
        // Navigate to next tab
        const currentIndex = this.getCurrentTabIndex();
        const nextIndex = (currentIndex + 1) % this.links.length;
        this.navigateToTab(nextIndex);
        
        this.showFeedback('تم الانتقال للتبويب التالي', 'info');
    }
    
    handleSwipeRight() {
        // Navigate to previous tab
        const currentIndex = this.getCurrentTabIndex();
        const prevIndex = (currentIndex - 1 + this.links.length) % this.links.length;
        this.navigateToTab(prevIndex);
        
        this.showFeedback('تم الانتقال للتبويب السابق', 'info');
    }
    
    handleLongPress(e) {
        const target = e.target.closest('.mobile-nav-link');
        if (target) {
            // Show context menu or additional options
            this.showContextMenu(target, e.touches[0].clientX, e.touches[0].clientY);
            
            // Haptic feedback
            if (navigator.vibrate) {
                navigator.vibrate([50, 50, 50]);
            }
        }
    }
    
    handleDoubleTap(e) {
        const target = e.target.closest('.mobile-nav-link');
        if (target) {
            // Quick action for double tap
            this.executeQuickAction(target);
        }
    }
    
    getCurrentTabIndex() {
        return Array.from(this.links).findIndex(link => 
            link.classList.contains('active')
        );
    }
    
    navigateToTab(index) {
        if (index >= 0 && index < this.links.length) {
            const link = this.links[index];
            this.animateTabTransition(link);
            
            // Simulate click after animation
            setTimeout(() => {
                link.click();
            }, 200);
        }
    }
    
    animateTabTransition(targetLink) {
        // Add transition effect
        targetLink.style.transform = 'scale(1.2)';
        targetLink.style.background = 'rgba(102, 126, 234, 0.2)';
        
        setTimeout(() => {
            targetLink.style.transform = '';
            targetLink.style.background = '';
        }, 200);
    }
    
    setupNotificationSystem() {
        this.notificationQueue = [];
        this.maxNotifications = 5;
        
        // Create notification container
        this.createNotificationContainer();
    }
    
    createNotificationContainer() {
        const container = document.createElement('div');
        container.className = 'mobile-nav-notifications';
        container.innerHTML = `
            <style>
                .mobile-nav-notifications {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 1001;
                    pointer-events: none;
                }
                
                .mobile-nav-notification {
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(20px);
                    border-radius: 12px;
                    padding: 12px 16px;
                    margin-bottom: 8px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                    transform: translateX(100%);
                    transition: transform 0.3s ease;
                    pointer-events: auto;
                    max-width: 300px;
                    border-left: 4px solid #667eea;
                }
                
                .mobile-nav-notification.show {
                    transform: translateX(0);
                }
                
                .mobile-nav-notification.success {
                    border-left-color: #22c55e;
                }
                
                .mobile-nav-notification.error {
                    border-left-color: #ef4444;
                }
                
                .mobile-nav-notification.warning {
                    border-left-color: #f59e0b;
                }
            </style>
        `;
        
        document.body.appendChild(container);
        this.notificationContainer = container;
    }
    
    showFeedback(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `mobile-nav-notification ${type}`;
        notification.textContent = message;
        
        this.notificationContainer.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Remove after duration
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
    }
    
    setupThemeSupport() {
        this.detectTheme();
        this.applyTheme();
        
        // Listen for theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.currentTheme === 'auto') {
                this.applyTheme();
            }
        });
    }
    
    detectTheme() {
        const savedTheme = localStorage.getItem('mobile-nav-theme');
        if (savedTheme && this.themes.includes(savedTheme)) {
            this.currentTheme = savedTheme;
        }
    }
    
    applyTheme() {
        const isDark = this.currentTheme === 'dark' || 
                      (this.currentTheme === 'auto' && 
                       window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    }
    
    toggleTheme() {
        const currentIndex = this.themes.indexOf(this.currentTheme);
        const nextIndex = (currentIndex + 1) % this.themes.length;
        this.currentTheme = this.themes[nextIndex];
        
        localStorage.setItem('mobile-nav-theme', this.currentTheme);
        this.applyTheme();
        
        this.showFeedback(`تم تغيير المظهر إلى: ${this.getThemeName()}`, 'success');
    }
    
    getThemeName() {
        const names = { light: 'فاتح', dark: 'داكن', auto: 'تلقائي' };
        return names[this.currentTheme] || 'غير معروف';
    }
    
    setupVoiceCommands() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            return;
        }
        
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.recognition = new SpeechRecognition();
        
        this.recognition.lang = 'ar-SA';
        this.recognition.continuous = false;
        this.recognition.interimResults = false;
        
        this.recognition.onresult = (event) => {
            const command = event.results[0][0].transcript.toLowerCase();
            this.processVoiceCommand(command);
        };
        
        this.recognition.onerror = (event) => {
            console.warn('Voice recognition error:', event.error);
        };
    }
    
    processVoiceCommand(command) {
        const commands = {
            'الرئيسية': () => this.navigateToPage('/'),
            'المحفظة': () => this.navigateToPage('/wallet'),
            'السوق': () => this.navigateToPage('/market'),
            'الإحصائيات': () => this.navigateToPage('/statistics'),
            'الملف الشخصي': () => this.navigateToPage('/profile'),
            'تغيير المظهر': () => this.toggleTheme(),
            'تحديث': () => this.refreshPage()
        };
        
        for (const [phrase, action] of Object.entries(commands)) {
            if (command.includes(phrase)) {
                action();
                this.showFeedback(`تم تنفيذ الأمر: ${phrase}`, 'success');
                return;
            }
        }
        
        this.showFeedback('لم يتم التعرف على الأمر', 'warning');
    }
    
    startVoiceCommand() {
        if (this.recognition) {
            this.recognition.start();
            this.showFeedback('جاري الاستماع للأمر الصوتي...', 'info');
        }
    }
    
    navigateToPage(url) {
        window.location.href = url;
    }
    
    refreshPage() {
        window.location.reload();
    }
    
    setupAdvancedAnimations() {
        // Particle effect on click
        this.links.forEach(link => {
            link.addEventListener('click', (e) => {
                this.createParticleEffect(e, link);
            });
        });
    }
    
    createParticleEffect(event, element) {
        const rect = element.getBoundingClientRect();
        const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c'];
        
        for (let i = 0; i < 6; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 4px;
                height: 4px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                border-radius: 50%;
                pointer-events: none;
                z-index: 1002;
                left: ${rect.left + rect.width / 2}px;
                top: ${rect.top + rect.height / 2}px;
            `;
            
            document.body.appendChild(particle);
            
            // Animate particle
            const angle = (i / 6) * Math.PI * 2;
            const velocity = 50 + Math.random() * 50;
            const x = Math.cos(angle) * velocity;
            const y = Math.sin(angle) * velocity;
            
            particle.animate([
                { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                { transform: `translate(${x}px, ${y}px) scale(0)`, opacity: 0 }
            ], {
                duration: 600,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            }).onfinish = () => {
                particle.remove();
            };
        }
    }
    
    setupContextMenu() {
        this.contextMenu = this.createContextMenu();
    }
    
    createContextMenu() {
        const menu = document.createElement('div');
        menu.className = 'mobile-nav-context-menu';
        menu.innerHTML = `
            <style>
                .mobile-nav-context-menu {
                    position: fixed;
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(20px);
                    border-radius: 12px;
                    padding: 8px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                    z-index: 1003;
                    opacity: 0;
                    transform: scale(0.8);
                    transition: all 0.2s ease;
                    pointer-events: none;
                }
                
                .mobile-nav-context-menu.show {
                    opacity: 1;
                    transform: scale(1);
                    pointer-events: auto;
                }
                
                .mobile-nav-context-item {
                    padding: 8px 12px;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: background 0.2s ease;
                    font-size: 14px;
                    white-space: nowrap;
                }
                
                .mobile-nav-context-item:hover {
                    background: rgba(102, 126, 234, 0.1);
                }
            </style>
            <div class="mobile-nav-context-item" data-action="bookmark">إضافة للمفضلة</div>
            <div class="mobile-nav-context-item" data-action="share">مشاركة</div>
            <div class="mobile-nav-context-item" data-action="theme">تغيير المظهر</div>
            <div class="mobile-nav-context-item" data-action="voice">أوامر صوتية</div>
        `;
        
        document.body.appendChild(menu);
        
        // Handle context menu clicks
        menu.addEventListener('click', (e) => {
            const action = e.target.dataset.action;
            if (action) {
                this.handleContextAction(action);
                this.hideContextMenu();
            }
        });
        
        // Hide menu when clicking outside
        document.addEventListener('click', () => {
            this.hideContextMenu();
        });
        
        return menu;
    }
    
    showContextMenu(target, x, y) {
        this.contextMenu.style.left = `${x}px`;
        this.contextMenu.style.top = `${y}px`;
        this.contextMenu.classList.add('show');
    }
    
    hideContextMenu() {
        this.contextMenu.classList.remove('show');
    }
    
    handleContextAction(action) {
        switch (action) {
            case 'bookmark':
                this.showFeedback('تمت إضافة الصفحة للمفضلة', 'success');
                break;
            case 'share':
                if (navigator.share) {
                    navigator.share({
                        title: document.title,
                        url: window.location.href
                    });
                } else {
                    this.showFeedback('مشاركة الرابط غير مدعومة', 'warning');
                }
                break;
            case 'theme':
                this.toggleTheme();
                break;
            case 'voice':
                this.startVoiceCommand();
                break;
        }
    }
    
    executeQuickAction(target) {
        const navType = target.dataset.nav;
        
        switch (navType) {
            case 'wallet':
                this.showFeedback('فتح المحفظة السريعة', 'info');
                break;
            case 'market':
                this.showFeedback('البحث في السوق', 'info');
                break;
            case 'statistics':
                this.showFeedback('تحديث الإحصائيات', 'info');
                break;
            case 'profile':
                this.showFeedback('تحرير الملف الشخصي', 'info');
                break;
            default:
                this.showFeedback('إجراء سريع', 'info');
        }
    }
}

// Initialize advanced mobile navigation
let advancedMobileNav;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        advancedMobileNav = new AdvancedMobileNav();
    });
} else {
    advancedMobileNav = new AdvancedMobileNav();
}

// Export for global access
window.AdvancedMobileNav = AdvancedMobileNav;
window.advancedMobileNav = advancedMobileNav;
