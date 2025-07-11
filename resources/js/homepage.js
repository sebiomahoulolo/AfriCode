// AfriCode Homepage Interactive Features
document.addEventListener("DOMContentLoaded", function() {
    // Indicateur de progression de scroll
    const createScrollIndicator = () => {
        const scrollIndicator = document.createElement('div');
        scrollIndicator.className = 'scroll-indicator';
        scrollIndicator.innerHTML = '<div class="scroll-progress"></div>';
        document.body.prepend(scrollIndicator);
        
        const updateScrollProgress = () => {
            const scrollTop = window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            
            document.querySelector('.scroll-progress').style.width = scrollPercent + '%';
        };
        
        window.addEventListener('scroll', updateScrollProgress);
    };
    
    // Initialiser l'indicateur de scroll
    createScrollIndicator();
    
    // Animation des compteurs avec intersection observer
    const animateCounters = () => {
        const statNumbers = document.querySelectorAll(".stat-number");
        
        const animateCounter = (element, target) => {
            let current = 0;
            const targetNum = parseInt(target);
            const isPercentage = target.includes('%');
            const increment = targetNum / 100;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= targetNum) {
                    element.textContent = targetNum + (isPercentage ? '%' : '+');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (isPercentage ? '%' : '+');
                }
            }, 20);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const value = target.textContent;
                    animateCounter(target, value);
                    observer.unobserve(target);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(stat => {
            observer.observe(stat);
        });
    };
    
    // Animation d'apparition progressive pour les cartes
    const animateCards = () => {
        const cards = document.querySelectorAll('.program-card, .feature-item, .testimonial-card');
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });
        
        cards.forEach(card => observer.observe(card));
    };
    
    // Effet parallax pour le hero
    const parallaxEffect = () => {
        const hero = document.querySelector('.hero-main');
        if (!hero) return;
        
        const handleScroll = () => {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.3;
            
            // Appliquer l'effet uniquement si l'élément est visible
            if (scrolled < hero.offsetHeight) {
                hero.style.transform = `translateY(${parallax}px)`;
            }
        };
        
        // Throttle pour optimiser les performances
        let ticking = false;
        const throttledScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        };
        
        window.addEventListener('scroll', throttledScroll);
    };
    
    // Animation des badges flottants
    const animateFloatingBadges = () => {
        const badges = document.querySelectorAll('.badge-item');
        
        badges.forEach((badge, index) => {
            const delay = index * 500;
            badge.style.animationDelay = `${delay}ms`;
            
            // Ajouter un effet de hover personnalisé
            badge.addEventListener('mouseenter', () => {
                badge.style.transform = 'scale(1.1) translateY(-5px)';
                badge.style.boxShadow = '0 10px 20px rgba(0,0,0,0.2)';
            });
            
            badge.addEventListener('mouseleave', () => {
                badge.style.transform = 'scale(1) translateY(0)';
                badge.style.boxShadow = '0 4px 8px rgba(0,0,0,0.15)';
            });
        });
    };
    
    // Smooth scroll pour les liens d'ancrage
    const initSmoothScroll = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80; // Offset pour la nav fixe
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    };
    
    // Effet de typing pour le titre principal
    const typingEffect = () => {
        const titleElement = document.querySelector('.hero-title');
        if (!titleElement) return;
        
        const originalText = titleElement.textContent;
        titleElement.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < originalText.length) {
                titleElement.textContent += originalText.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        // Démarrer l'effet après un petit délai
        setTimeout(typeWriter, 500);
    };
    
    // Gestion des formulaires avec validation
    const handleForms = () => {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const inputs = form.querySelectorAll('input[required]');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                        
                        // Supprimer la classe d'erreur lors de la saisie
                        input.addEventListener('input', () => {
                            input.classList.remove('is-invalid');
                        });
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Ajouter un feedback visuel
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    };
    
    // Loader de page
    const pageLoader = () => {
        const loader = document.createElement('div');
        loader.className = 'page-loader';
        loader.innerHTML = `
            <div class="loader-content">
                <div class="africode-logo">
                    <div class="logo-animation"></div>
                </div>
                <div class="loader-text">Chargement...</div>
            </div>
        `;
        
        document.body.prepend(loader);
        
        // Masquer le loader quand tout est chargé
        window.addEventListener('load', () => {
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.remove();
                }, 300);
            }, 500);
        });
    };
    
    // Intersection Observer pour les animations
    const initScrollAnimations = () => {
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -100px 0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        
        // Observer tous les éléments avec la classe 'animate-on-scroll'
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    };
    
    // Initialisation de tous les effets
    const init = () => {
        // Vérifier si on est sur la page d'accueil
        if (document.querySelector('.hero-main')) {
            animateCounters();
            animateCards();
            parallaxEffect();
            animateFloatingBadges();
            typingEffect();
        }
        
        initSmoothScroll();
        handleForms();
        initScrollAnimations();
        
        // Ajouter des classes pour les animations CSS
        document.body.classList.add('loaded');
    };
    
    // Initialiser après que le DOM soit complètement chargé
    init();
    
    // Gestionnaire de redimensionnement de fenêtre
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // Recalculer les positions si nécessaire
            console.log('Window resized - recalculating positions');
        }, 250);
    });
    
    // Performance monitoring (dev only)
    if (window.location.hostname === 'localhost') {
        const perfObserver = new PerformanceObserver((list) => {
            list.getEntries().forEach((entry) => {
                console.log(`${entry.name}: ${entry.duration}ms`);
            });
        });
        
        perfObserver.observe({ entryTypes: ['navigation', 'paint'] });
    }
});

// CSS supplémentaire pour les animations
const additionalStyles = `
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1EA38B, #27B371);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        transition: opacity 0.3s ease;
    }
    
    .loader-content {
        text-align: center;
        color: white;
    }
    
    .logo-animation {
        width: 60px;
        height: 60px;
        border: 3px solid rgba(255,255,255,0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px auto;
    }
    
    .loader-text {
        font-size: 18px;
        font-weight: 500;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    .animate-on-scroll.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .is-invalid {
        border-color: #E32D31 !important;
        box-shadow: 0 0 0 0.2rem rgba(227, 45, 49, 0.25) !important;
    }
`;

// Injecter les styles supplémentaires
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
