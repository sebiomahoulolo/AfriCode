/**
 * Système d'aspects marketing pour AfriCode
 * - Test de niveau proposé après 60 secondes
 * - Promotions affichées au scroll 50-60%
 */

class MarketingAutomation {
    constructor() {
        this.isInitialized = false;
        this.testPopupShown = false;
        this.promotionPopupShown = false;
        this.userSession = {
            startTime: Date.now(),
            scrollPercentage: 0,
            pageViews: 1
        };

        if (this.isAllowedPage()) {
            this.init();
        }
    }

    isAllowedPage() {
        const currentPath = window.location.pathname;
        const allowedPages = ['/', '/accueil'];
        return allowedPages.some(page => {
            if (page === '/') return currentPath === '/' || currentPath === '';
            return currentPath.startsWith(page);
        });
    }

    init() {
        if (this.isInitialized) return;
        this.isInitialized = true;
        this.setupEventListeners();
        this.startSessionTracking();
    }

    setupEventListeners() {
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.updateScrollPercentage();
            }, 100);
        });

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.userSession.startTime = Date.now();
            }
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('.mkt-card')) {
                this.trackPopupInteraction(e.target.closest('.mkt-card'));
            }
        });
    }

    startSessionTracking() {
        setTimeout(() => {
            this.checkAndShowLevelTest();
        }, 7000);

        this.updateScrollPercentage();
    }

    updateScrollPercentage() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercentage = Math.round((scrollTop / scrollHeight) * 100);

        this.userSession.scrollPercentage = scrollPercentage;

        if (scrollPercentage >= 50 && scrollPercentage <= 60 && !this.promotionPopupShown) {
            this.checkAndShowPromotions();
        }
    }

    async checkAndShowLevelTest() {
        if (this.testPopupShown) return;
        try {
            const response = await fetch('/marketing/test-eligibility');
            const data = await response.json();
            if (data.eligible) {
                this.showLevelTestCard();
                this.testPopupShown = true;
            }
        } catch (error) {
            console.error('Erreur lors de la vérification du test:', error);
        }
    }

    async checkAndShowPromotions() {
        if (this.promotionPopupShown) return;
        try {
            const response = await fetch('/marketing/promotional-courses');
            const data = await response.json();
            if (data.courses && data.courses.length > 0) {
                this.showPromotionCard(data.courses);
                this.promotionPopupShown = true;
            }
        } catch (error) {
            console.error('Erreur lors du chargement des promotions:', error);
        }
    }

    showLevelTestCard() {
        const existing = document.getElementById('mkt-level-test');
        if (existing) return;

        const card = document.createElement('div');
        card.id = 'mkt-level-test';
        card.className = 'mkt-card mkt-card--level';
        card.innerHTML = `
            <div class="mkt-card__accent"></div>
            <button class="mkt-card__close" onclick="marketingAutomation.dismissCard('mkt-level-test', true)" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
            <div class="mkt-card__body">
                <div class="mkt-card__icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="mkt-card__text">
                    <strong>Découvrez votre niveau</strong>
                    <p>Évaluation gratuite · 15 min</p>
                </div>
            </div>
            <div class="mkt-card__chips">
                <span><i class="fas fa-chart-line"></i> Personnalisé</span>
                <span><i class="fas fa-gift"></i> 100% gratuit</span>
            </div>
            <div class="mkt-card__actions">
                <button class="mkt-btn mkt-btn--primary" onclick="window.location.href='/test-de-niveau'">
                    Commencer le test
                </button>
                <button class="mkt-btn mkt-btn--ghost" onclick="marketingAutomation.dismissCard('mkt-level-test', true)">
                    Plus tard
                </button>
            </div>
        `;

        document.body.appendChild(card);

        requestAnimationFrame(() => {
            card.classList.add('mkt-card--visible');
        });

        setTimeout(() => {
            this.dismissCard('mkt-level-test', false);
        }, 18000);
    }

    showPromotionCard(courses) {
        const existing = document.getElementById('mkt-promotion');
        if (existing) return;

        const course = courses[0];
        const discount = course.discount_percentage || 20;

        const card = document.createElement('div');
        card.id = 'mkt-promotion';
        card.className = 'mkt-card mkt-card--promo';
        card.innerHTML = `
            <div class="mkt-card__accent mkt-card__accent--promo"></div>
            <button class="mkt-card__close" onclick="marketingAutomation.dismissCard('mkt-promotion', true)" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
            <div class="mkt-card__promo-badge">
                <i class="fas fa-tag"></i> -${discount}% aujourd'hui
            </div>
            <div class="mkt-card__body">
                <div class="mkt-card__course-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="mkt-card__text">
                    <strong>${this._truncate(course.title, 42)}</strong>
                    <p>par ${course.instructor || 'AfriCode'}</p>
                </div>
            </div>
            <div class="mkt-card__pricing">
                <span class="mkt-price--old">${formatPrice(course.original_price)} FCFA</span>
                <span class="mkt-price--new">${formatPrice(course.price)} FCFA</span>
            </div>
            <div class="mkt-card__urgency">
                <i class="fas fa-clock"></i> Offre valable jusqu'à fin du mois
            </div>
            <div class="mkt-card__actions">
                <button class="mkt-btn mkt-btn--promo" onclick="window.location.href='/cours?promotion=true'">
                    Voir les offres
                </button>
                <button class="mkt-btn mkt-btn--ghost" onclick="marketingAutomation.dismissCard('mkt-promotion', true)">
                    Fermer
                </button>
            </div>
        `;

        document.body.appendChild(card);

        const levelCard = document.getElementById('mkt-level-test');
        const offset = levelCard ? 220 : 20;
        card.style.bottom = offset + 'px';

        requestAnimationFrame(() => {
            card.classList.add('mkt-card--visible');
        });

        setTimeout(() => {
            this.dismissCard('mkt-promotion', false);
        }, 20000);
    }

    _truncate(str, len) {
        return str && str.length > len ? str.substring(0, len) + '…' : (str || '');
    }

    dismissCard(id, markSeen) {
        const card = document.getElementById(id);
        if (!card) return;
        card.classList.remove('mkt-card--visible');
        card.classList.add('mkt-card--hiding');
        setTimeout(() => card.remove(), 400);

        if (markSeen) {
            if (id === 'mkt-level-test') this.markTestSeen();
            if (id === 'mkt-promotion') {
                const courses = [];
                this.markPromotionSeen(courses[0]?.id);
            }
        }
    }

    trackPopupInteraction(card) {
        const type = card.classList.contains('mkt-card--level') ? 'level_test' : 'promotion';
        if (typeof gtag !== 'undefined') {
            gtag('event', 'popup_interaction', {
                'event_category': 'marketing',
                'event_label': type,
                'value': 1
            });
        }
    }

    async markTestSeen() {
        try {
            await fetch('/marketing/mark-test-seen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        } catch (error) {}
    }

    async markPromotionSeen(courseId) {
        if (!courseId) return;
        try {
            await fetch('/marketing/mark-promotion-seen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ course_id: courseId })
            });
        } catch (error) {}
    }
}

let marketingAutomation;
document.addEventListener('DOMContentLoaded', () => {
    marketingAutomation = new MarketingAutomation();
});

function getInitials(name) {
    const parts = (name || '').trim().split(' ');
    return parts.map(p => p[0]?.toUpperCase() || '').slice(0, 2).join('');
}

function formatPrice(value) {
    const n = Number(value);
    if (isNaN(n)) return '0';
    return Math.round(n).toLocaleString('fr-FR');
}

const style = document.createElement('style');
style.textContent = `
    /* ── Marketing notification cards ── */
    .mkt-card {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 310px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.08);
        z-index: 9800;
        overflow: hidden;
        transform: translateX(120%) translateY(10px);
        opacity: 0;
        transition: transform .4s cubic-bezier(.34,1.56,.64,1), opacity .35s ease;
        border: 1px solid rgba(30,163,139,.18);
    }

    .mkt-card--promo {
        bottom: 20px;
    }

    .mkt-card--visible {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }

    .mkt-card--hiding {
        transform: translateX(120%) translateY(10px) !important;
        opacity: 0 !important;
    }

    .mkt-card__accent {
        height: 4px;
        background: linear-gradient(90deg, #11c06d, #239e79);
    }

    .mkt-card__accent--promo {
        background: linear-gradient(90deg, #fea837, #fa1c39);
    }

    .mkt-card__close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,.06);
        border: none;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #888;
        font-size: .75rem;
        transition: background .2s, color .2s;
        line-height: 1;
        padding: 0;
    }

    .mkt-card__close:hover {
        background: rgba(0,0,0,.14);
        color: #333;
    }

    .mkt-card__promo-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 12px 14px 0;
        padding: 3px 10px;
        background: linear-gradient(135deg, #fea837, #fa1c39);
        color: #fff;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .mkt-card__body {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 14px 8px;
    }

    .mkt-card__icon {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #11c06d, #239e79);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.2rem;
    }

    .mkt-card__course-icon {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fea837, #fa1c39);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.25rem;
    }

    .mkt-card__text strong {
        display: block;
        font-size: .9rem;
        color: #1a1a2e;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 2px;
    }

    .mkt-card__text p {
        margin: 0;
        font-size: .78rem;
        color: #888;
    }

    .mkt-card__chips {
        display: flex;
        gap: 6px;
        padding: 0 14px 10px;
        flex-wrap: wrap;
    }

    .mkt-card__chips span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        background: #f0faf8;
        border: 1px solid rgba(30,163,139,.2);
        color: #11c06d;
        border-radius: 20px;
        font-size: .73rem;
        font-weight: 600;
    }

    .mkt-card__pricing {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 14px 6px;
    }

    .mkt-price--old {
        font-size: .8rem;
        color: #aaa;
        text-decoration: line-through;
    }

    .mkt-price--new {
        font-size: 1.05rem;
        font-weight: 700;
        color: #11c06d;
    }

    .mkt-card__urgency {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0 14px 10px;
        padding: 6px 10px;
        background: rgba(255,142,42,.08);
        border-left: 3px solid #fea837;
        border-radius: 0 8px 8px 0;
        font-size: .75rem;
        color: #c0620d;
        font-weight: 600;
    }

    .mkt-card__actions {
        display: flex;
        gap: 8px;
        padding: 10px 14px 14px;
        border-top: 1px solid #f0f0f0;
    }

    .mkt-btn {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 9px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .mkt-btn--primary {
        background: linear-gradient(135deg, #11c06d, #239e79);
        color: #fff;
    }

    .mkt-btn--primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30,163,139,.35);
    }

    .mkt-btn--promo {
        background: linear-gradient(135deg, #fea837, #fa1c39);
        color: #fff;
    }

    .mkt-btn--promo:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255,142,42,.35);
    }

    .mkt-btn--ghost {
        background: transparent;
        color: #aaa;
        border: 1px solid #e5e5e5;
    }

    .mkt-btn--ghost:hover {
        background: #f5f5f5;
        color: #666;
    }

    @media (max-width: 480px) {
        .mkt-card {
            width: calc(100vw - 24px);
            right: 12px;
            bottom: 12px;
        }

        .mkt-card--promo {
            bottom: 12px;
        }
    }
`;

document.head.appendChild(style);
