/**
 * Professional Mobile Navigation JavaScript
 * Enhanced mobile navigation with animations, gestures, and accessibility
 */

class MobileNavigation {
    constructor() {
        this.nav = null;
        this.links = [];
        this.currentPath = window.location.pathname;
        this.scrollTimeout = null;
        this.lastScrollY = window.scrollY;
        this.isHidden = false;
        
        this.init();
    }
    
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }
    
    setup() {
        this.nav = document.querySelector('.mobile-nav');
        this.links = document.querySelectorAll('.mobile-nav-link');
        
        if (!this.nav || this.links.length === 0) {
            console.warn('Mobile navigation elements not found');
            return;
        }
        
        this.setupActiveStates();
        this.setupRippleEffects();
        this.setupTouchFeedback();
        this.setupScrollBehavior();
        this.setupKeyboardNavigation();
        this.setupBadgeSystem();
        this.setupPerformanceOptimizations();
        
        // Add entrance animation
        this.animateEntrance();
        
        console.log('Mobile navigation initialized successfully');
    }
    
    setupActiveStates() {
        this.links.forEach(link => {
            const href = link.getAttribute('href');
            
            // Set active state based on current path
            if (this.isCurrentPath(href)) {
                link.classList.add('active');
            }
            
            // Handle navigation clicks
            link.addEventListener('click', (e) => this.handleNavigation(e, link));
        });
    }
    
    isCurrentPath(href) {
        if (!href) return false;
        
        // Handle root path
        if (this.currentPath === '/' && href === '/') return true;
        
        // Handle other paths
        return href === this.currentPath;
    }
    
    setupRippleEffects() {
        this.links.forEach(link => {
            link.addEventListener('click', (e) => this.createRipple(e, link));
        });
    }
    
    createRipple(event, element) {
        const ripple = element.querySelector('.mobile-nav-ripple');
        if (!ripple) return;
        
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height) * 1.5;
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        // Reset ripple
        ripple.style.width = '0px';
        ripple.style.height = '0px';
        ripple.style.opacity = '0';
        
        // Force reflow
        ripple.offsetHeight;
        
        // Animate ripple
        ripple.style.width = size + 'px';
        ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.opacity = '1';
        
        // Clean up
        setTimeout(() => {
            ripple.style.opacity = '0';
            setTimeout(() => {
                ripple.style.width = '0px';
                ripple.style.height = '0px';
            }, 300);
        }, 400);
    }
    
    setupTouchFeedback() {
        this.links.forEach(link => {
            // Add haptic feedback for supported devices
            link.addEventListener('touchstart', () => {
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
                
                // Visual feedback
                link.style.background = 'rgba(102, 126, 234, 0.1)';
            });
            
            link.addEventListener('touchend', () => {
                setTimeout(() => {
                    link.style.background = '';
                }, 200);
            });
            
            // Handle touch cancel
            link.addEventListener('touchcancel', () => {
                link.style.background = '';
            });
        });
    }
    
    setupScrollBehavior() {
        let isScrolling = false;
        
        window.addEventListener('scroll', () => {
            if (isScrolling) return;
            
            isScrolling = true;
            requestAnimationFrame(() => {
                this.handleScroll();
                isScrolling = false;
            });
        }, { passive: true });
    }
    
    handleScroll() {
        const currentScrollY = window.scrollY;
        const scrollThreshold = 100;
        
        // Hide nav when scrolling down, show when scrolling up
        if (currentScrollY > this.lastScrollY && currentScrollY > scrollThreshold) {
            this.hideNavigation();
        } else {
            this.showNavigation();
        }
        
        this.lastScrollY = currentScrollY;
        
        // Auto-show after scroll stops
        clearTimeout(this.scrollTimeout);
        this.scrollTimeout = setTimeout(() => {
            this.showNavigation();
        }, 1000);
    }
    
    hideNavigation() {
        if (this.isHidden) return;
        
        this.nav.style.transform = 'translate3d(0, 100%, 0)';
        this.nav.style.opacity = '0.7';
        this.isHidden = true;
    }
    
    showNavigation() {
        if (!this.isHidden) return;
        
        this.nav.style.transform = 'translate3d(0, 0, 0)';
        this.nav.style.opacity = '1';
        this.isHidden = false;
    }
    
    setupKeyboardNavigation() {
        let currentIndex = -1;
        
        document.addEventListener('keydown', (e) => {
            // Only handle keyboard navigation when no input is focused
            if (document.activeElement.tagName === 'INPUT' || 
                document.activeElement.tagName === 'TEXTAREA') {
                return;
            }
            
            switch (e.key) {
                case 'ArrowLeft':
                case 'ArrowRight':
                    e.preventDefault();
                    this.navigateWithKeyboard(e.key === 'ArrowRight' ? 1 : -1);
                    break;
                case 'Enter':
                case ' ':
                    if (currentIndex >= 0 && currentIndex < this.links.length) {
                        e.preventDefault();
                        this.links[currentIndex].click();
                    }
                    break;
            }
        });
    }
    
    navigateWithKeyboard(direction) {
        let currentIndex = Array.from(this.links).findIndex(link => 
            link === document.activeElement
        );
        
        if (currentIndex === -1) {
            currentIndex = 0;
        } else {
            currentIndex += direction;
        }
        
        // Wrap around
        if (currentIndex >= this.links.length) currentIndex = 0;
        if (currentIndex < 0) currentIndex = this.links.length - 1;
        
        this.links[currentIndex].focus();
    }
    
    setupBadgeSystem() {
        // This would integrate with your backend to show notification badges
        this.updateBadges();
        
        // Update badges periodically
        setInterval(() => this.updateBadges(), 30000);
    }
    
    updateBadges() {
        // Example: You could fetch notification counts from your API
        const badges = {
            wallet: 0,
            statistics: 0,
            market: 0,
            profile: 0
        };
        
        // Apply badges
        Object.entries(badges).forEach(([nav, count]) => {
            this.showBadge(nav, count);
        });
    }
    
    showBadge(navItem, count) {
        const link = document.querySelector(`[data-nav="${navItem}"]`);
        if (!link || count <= 0) return;
        
        const indicator = link.querySelector('.mobile-nav-indicator');
        if (indicator) {
            indicator.style.opacity = '1';
            indicator.style.transform = 'scale(1)';
            
            // Add badge with count if > 1
            if (count > 1) {
                let badge = link.querySelector('.mobile-nav-badge');
                if (!badge) {
                    badge = document.createElement('div');
                    badge.className = 'mobile-nav-badge';
                    link.querySelector('.mobile-nav-icon-container').appendChild(badge);
                }
                badge.textContent = count > 99 ? '99+' : count.toString();
            }
        }
    }
    
    setupPerformanceOptimizations() {
        // Use transform3d for better performance
        this.nav.style.transform = 'translate3d(0, 0, 0)';
        this.nav.style.willChange = 'transform';
        
        // Optimize animations
        this.links.forEach(link => {
            link.style.willChange = 'transform';
        });
    }
    
    animateEntrance() {
        // Add staggered entrance animation
        this.links.forEach((link, index) => {
            link.style.opacity = '0';
            link.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                link.style.transition = 'all 0.3s ease-out';
                link.style.opacity = '1';
                link.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }
    
    handleNavigation(event, link) {
        // Add loading state
        link.classList.add('loading');
        
        // Remove loading state after navigation
        setTimeout(() => {
            link.classList.remove('loading');
        }, 1000);
        
        // Update active states
        this.updateActiveStates(link);
    }
    
    updateActiveStates(activeLink) {
        this.links.forEach(link => {
            link.classList.remove('active');
        });
        
        activeLink.classList.add('active');
    }
    
    // Public methods
    setActiveTab(navItem) {
        const link = document.querySelector(`[data-nav="${navItem}"]`);
        if (link) {
            this.updateActiveStates(link);
        }
    }
    
    showNotification(navItem, count = 1) {
        this.showBadge(navItem, count);
    }
    
    hideNotification(navItem) {
        const link = document.querySelector(`[data-nav="${navItem}"]`);
        if (link) {
            const indicator = link.querySelector('.mobile-nav-indicator');
            const badge = link.querySelector('.mobile-nav-badge');
            
            if (indicator) {
                indicator.style.opacity = '0';
                indicator.style.transform = 'scale(0)';
            }
            
            if (badge) {
                badge.remove();
            }
        }
    }
}

// Auto-initialize when script loads
let mobileNav;

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        mobileNav = new MobileNavigation();
    });
} else {
    mobileNav = new MobileNavigation();
}

// Export for use in other scripts
window.MobileNavigation = MobileNavigation;
window.mobileNav = mobileNav;
