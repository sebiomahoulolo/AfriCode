/**
 * Script de génération PDF pour certificats AfriCode
 * Version alternative plus robuste
 */

class CertificatePDFGenerator {
    constructor() {
        this.isGenerating = false;
    }

    async generatePDF() {
        if (this.isGenerating) {
            this.showNotification('Génération déjà en cours...', 'warning');
            return;
        }

        this.isGenerating = true;
        const btn = document.getElementById('download-certificate');
        
        try {
            // UI Feedback
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Génération en cours...';
            }

            // Méthode 1: Préparation optimisée + html2pdf
            await this.tryOptimizedPDF();
            
        } catch (error) {
            console.warn('Méthode 1 échouée, essai méthode 2...', error);
            
            try {
                // Méthode 2: Capture canvas + jsPDF
                await this.tryCanvasMethod();
                
            } catch (error2) {
                console.warn('Méthode 2 échouée, essai méthode 3...', error2);
                
                try {
                    // Méthode 3: Window.print avec styles optimisés
                    this.tryPrintMethod();
                    
                } catch (error3) {
                    console.error('Toutes les méthodes ont échoué:', error3);
                    this.showNotification('Échec de génération. Contactez le support.', 'error');
                }
            }
        } finally {
            this.isGenerating = false;
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-download me-2"></i>Télécharger en PDF';
            }
        }
    }

    async tryOptimizedPDF() {
        if (typeof html2pdf === 'undefined') {
            throw new Error('html2pdf non disponible');
        }

        // Préparer l'élément pour la capture
        const element = await this.prepareElementForPDF();
        
        // Configuration optimisée pour une seule page A4 paysage
        const options = {
            margin: [5, 5, 5, 5], // Marges en mm - très petite marge en haut
            filename: `certificat-africode-${Date.now()}.pdf`,
            image: { 
                type: 'jpeg', 
                quality: 0.98 
            },
            html2canvas: { 
                scale: 1.0, // Échelle optimisée
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false,
                letterRendering: true,
                allowTaint: false,
                width: 1200, // Largeur contrôlée
                height: 1200,  // Hauteur réduite pour mieux s'adapter
                x: 0,
                y: 10,
                scrollX: 0,
                scrollY: 0,
                foreignObjectRendering: true
            },
            jsPDF: { 
                unit: 'mm',
                format: 'a4',
                orientation: 'landscape',
                compress: true,
                precision: 16
            },
            pagebreak: { 
                mode: 'avoid-all'
            }
        };

        await html2pdf().set(options).from(element).save();
        this.restoreElement();
        this.showNotification('PDF téléchargé avec succès !', 'success');
    }

    async prepareElementForPDF() {
        const element = document.querySelector('.certificate-container');
        if (!element) {
            throw new Error('Élément certificat non trouvé');
        }

        // Sauvegarder l'état original
        this.originalStyles = {
            parentClass: element.parentElement.className,
            containerClass: element.className,
            containerStyle: element.style.cssText
        };

        // Masquer les éléments no-print
        this.hideNoPrintElements();

        // Appliquer la classe de capture PDF
        element.parentElement.classList.add('pdf-capture');
        
        // Attendre que les styles se mettent à jour
        await new Promise(resolve => setTimeout(resolve, 100));
        
        return element;
    }

    restoreElement() {
        if (this.originalStyles) {
            const element = document.querySelector('.certificate-container');
            if (element) {
                element.parentElement.className = this.originalStyles.parentClass;
                element.className = this.originalStyles.containerClass;
                element.style.cssText = this.originalStyles.containerStyle;
            }
        }
        this.showNoPrintElements();
    }

    async tryHtml2Pdf() {
        if (typeof html2pdf === 'undefined') {
            throw new Error('html2pdf non disponible');
        }

        const element = document.querySelector('.certificate-container');
        if (!element) {
            throw new Error('Élément certificat non trouvé');
        }

        // Masquer les éléments no-print
        this.hideNoPrintElements();

        // Configuration optimisée pour une seule page A4 paysage
        const options = {
            margin: [5, 5, 5, 5], // Marges en mm
            filename: `certificat-africode-${Date.now()}.pdf`,
            image: { 
                type: 'jpeg', 
                quality: 0.95 
            },
            html2canvas: { 
                scale: 1.2, // Réduction de l'échelle pour éviter le débordement
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false,
                letterRendering: true,
                allowTaint: false,
                width: 1200, // Largeur fixe pour contrôler le format
                height: 1200,  // Hauteur fixe pour A4 paysage
                x: 0,
                y: 10,
                scrollX: 0,
                scrollY: 0
            },
            jsPDF: { 
                unit: 'mm',
                format: 'a4',
                orientation: 'landscape',
                precision: 16,
                compress: true
            },
            pagebreak: { 
                mode: 'avoid-all'
            }
        };

        await html2pdf().set(options).from(element).save();
        this.showNoPrintElements();
        this.showNotification('PDF téléchargé avec succès !', 'success');
    }

    async tryCanvasMethod() {
        if (typeof html2canvas === 'undefined' || typeof jsPDF === 'undefined') {
            throw new Error('Bibliothèques canvas non disponibles');
        }

        const element = document.querySelector('.certificate-container');
        this.hideNoPrintElements();

        // Configuration optimisée pour capturer correctement
        const canvas = await html2canvas(element, {
            scale: 1.5,
            backgroundColor: '#ffffff',
            useCORS: true,
            letterRendering: true,
            allowTaint: false,
            width: element.offsetWidth,
            height: element.offsetHeight,
            windowWidth: element.offsetWidth,
            windowHeight: element.offsetHeight
        });

        const imgData = canvas.toDataURL('image/jpeg', 0.95);
        
        // Créer le PDF en format paysage A4
        const pdf = new jsPDF('landscape', 'mm', 'a4');
        
        // Dimensions A4 paysage en mm
        const pdfWidth = 297;
        const pdfHeight = 210;
        const margin = 10;
        
        // Calculer les dimensions disponibles
        const availableWidth = pdfWidth - (margin * 2);
        const availableHeight = pdfHeight - (margin * 2);
        
        // Calculer le ratio pour maintenir les proportions
        const imgAspectRatio = canvas.width / canvas.height;
        let imgWidth = availableWidth;
        let imgHeight = availableWidth / imgAspectRatio;
        
        // Si l'image est trop haute, ajuster par la hauteur
        if (imgHeight > availableHeight) {
            imgHeight = availableHeight;
            imgWidth = availableHeight * imgAspectRatio;
        }
        
        // Centrer l'image
        const x = (pdfWidth - imgWidth) / 2;
        const y = (pdfHeight - imgHeight) / 2;
        
        pdf.addImage(imgData, 'JPEG', x, y, imgWidth, imgHeight);
        pdf.save(`certificat-africode-${Date.now()}.pdf`);

        this.showNoPrintElements();
        this.showNotification('PDF généré avec Canvas !', 'success');
    }

    tryPrintMethod() {
        // Préparer la page pour l'impression
        const originalTitle = document.title;
        document.title = 'Certificat AfriCode';

        // Créer un style d'impression personnalisé
        const printStyle = document.createElement('style');
        printStyle.textContent = `
            @media print {
                body * { visibility: hidden; }
                .certificate-container, .certificate-container * { 
                    visibility: visible; 
                }
                .certificate-container {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: 0;
                    box-shadow: none !important;
                }
                .no-print { display: none !important; }
            }
        `;
        
        document.head.appendChild(printStyle);
        
        // Déclencher l'impression
        window.print();
        
        // Nettoyer
        setTimeout(() => {
            document.head.removeChild(printStyle);
            document.title = originalTitle;
        }, 100);

        this.showNotification('Boîte d\'impression ouverte', 'info');
    }

    hideNoPrintElements() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = 'none';
        });
    }

    showNoPrintElements() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = '';
        });
    }

    showNotification(message, type = 'info') {
        // Supprimer les notifications existantes
        document.querySelectorAll('.pdf-notification').forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = 'pdf-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-family: 'Inter', sans-serif;
            max-width: 350px;
        `;

        // Couleurs
        const colors = {
            success: '#27B371',
            error: '#E32D31',
            warning: '#FF8E2A',
            info: '#1EA38B'
        };
        
        notification.style.background = colors[type] || colors.info;
        notification.textContent = message;
        
        document.body.appendChild(notification);

        // Animation
        requestAnimationFrame(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        });

        // Auto-suppression
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', function() {
    const generator = new CertificatePDFGenerator();
    
    const btn = document.getElementById('download-certificate');
    if (btn) {
        btn.addEventListener('click', () => generator.generatePDF());
    }
});
