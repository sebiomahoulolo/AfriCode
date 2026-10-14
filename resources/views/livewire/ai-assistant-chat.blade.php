<div class="ai-assistant-container" x-data="aiAssistant()" style="position: fixed !important; bottom: 20px !important; right: 20px !important; z-index: 99999 !important;">
    <!-- Bouton flottant -->
    <button @click="toggleChat()" class="ai-assistant-toggle" x-show="!isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" title="AfriCode AI Assistant">
        <span class="ai-icon"><i class="fas fa-spinner fa-spin" x-show="isLoading"></i><i class="fas fa-robot" x-show="!isLoading"></i></span>
        <span class="pulse-dot" x-show="hasNewMessages"></span>
    </button>

    <!-- Fenêtre de chat -->
    <div class="ai-assistant-window" x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        <!-- En-tête -->
        <div class="ai-header">
            <div class="ai-status-indicator" :class="{'online': isOnline, 'offline': !isOnline}"></div>
            <div class="ai-avatar">
                <span><i class="fas fa-spinner fa-spin" x-show="isLoading"></i><i class="fas fa-robot" x-show="!isLoading"></i></span>
            </div>
            <div class="ai-info">
                <h3>AfriCode AI Assistant</h3>
                <p x-text="isTyping ? 'Écrit...' : 'Comment puis-je vous aider ?'"></p>
            </div>
            <div class="ai-actions">
                <button @click="clearChat()" class="ai-action-btn" title="Effacer la conversation">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button @click="toggleChat()" class="ai-close" title="Fermer">
                    ×
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div class="ai-messages" id="messages-container" x-ref="messagesContainer">
            <!-- Message de bienvenue -->
            <div class="welcome-message" x-show="messages.length === 0">
                <div class="welcome-icon"><i class="fas fa-robot"></i></div>
                <h4>Bienvenue sur AfriCode !</h4>
                <p>Je suis votre assistant IA. Posez-moi vos questions sur :</p>
                <ul class="welcome-features">
                    <li><i class="fas fa-book me-2"></i>Les cours et formations</li>
                    <li><i class="fas fa-briefcase me-2"></i>Votre compte formateur</li>
                    <li><i class="fas fa-coins me-2"></i>Les paiements et revenus</li>
                    <li><i class="fas fa-bullseye me-2"></i>Conseils pédagogiques</li>
                </ul>
            </div>

            <!-- Messages de la conversation -->
            <template x-for="message in messages" :key="message.id">
                <div class="message-wrapper" :class="message.role === 'user' ? 'user-message-wrapper' : 'ai-message-wrapper'">
                    <div class="message-container">
                        <div class="message-avatar" x-show="message.role === 'assistant'">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message-bubble-container">
                            <div class="message-bubble" :class="message.role === 'user' ? 'user-bubble' : 'ai-bubble'">
                                <div class="message-text" x-html="message.content"></div>
                                <div class="message-actions" x-show="message.role === 'assistant'">
                                    <button @click="copyMessage(message.content)" class="message-action-btn" title="Copier">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="message-time" x-text="formatTime(message.timestamp)"></div>
                        </div>
                        <div class="message-avatar user-avatar" x-show="message.role === 'user'">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Indicateur de frappe -->
            <div class="typing-indicator" x-show="isTyping">
                <div class="typing-avatar"><i class="fas fa-robot"></i></div>
                <div class="typing-bubble">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggestions rapides -->
        <div class="ai-suggestions" x-show="messages.length === 0">
            <h5>Questions fréquentes :</h5>
            <div class="suggestions-grid">
                <template x-for="suggestion in quickSuggestions" :key="suggestion.id">
                    <button @click="sendQuickMessage(suggestion.text)" class="suggestion-card">
                        <span class="suggestion-icon" x-text="suggestion.icon"></span>
                        <span class="suggestion-text" x-text="suggestion.text"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Formulaire de saisie -->
        <form @submit.prevent="sendMessage()" class="ai-input-form">
            <div class="input-container">
                <input type="text" x-model="input" placeholder="Tapez votre message..." class="ai-input" :disabled="isLoading">
                <div class="input-actions">
                    <button type="button" @click="toggleVoice()" class="voice-btn" :class="{'recording': isRecording}" title="Reconnaissance vocale">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button type="submit" class="ai-send-button" :disabled="!input.trim() || isLoading">
                        <span x-show="!isLoading"><i class="fas fa-paper-plane"></i></span>
                        <span x-show="isLoading" class="send-loading"></span>
                    </button>
                </div>
            </div>
            <div class="input-footer">
                <span class="char-count" x-text="`${input.length}/500`"></span>
                <span class="ai-disclaimer">AfriCode AI peut faire des erreurs. Vérifiez les informations importantes.</span>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    --success-color: #10B981;
    --warning-color: #F59E0B;
    --error-color: #EF4444;
    --bg-primary: #FFFFFF;
    --bg-secondary: #F8FAFC;
    --bg-tertiary: #F1F5F9;
    --text-primary: #1E293B;
    --text-secondary: #64748B;
    --text-muted: #94A3B8;
    --border-light: #E2E8F0;
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.3);
    --border-radius: 24px;
    --border-radius-sm: 16px;
}

/* Container principal */
.ai-assistant-container {
    position: fixed !important;
    bottom: 20px !important;
    right: 20px !important;
    z-index: 99999 !important;
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    margin: 0 !important;
    padding: 0 !important;
    isolation: isolate;
    transform: translateZ(0);
    will-change: transform;
}

/* Bouton toggle amélioré */
.ai-assistant-toggle {
    position: relative !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
    width: 68px !important;
    height: 68px !important;
    padding: 0 !important;
    background: var(--primary-gradient);
    color: #fff;
    border: none;
    border-radius: 50% !important;
    cursor: pointer;
    box-shadow: var(--shadow-lg);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    margin: 0 !important;
    animation: floatButton 3s ease-in-out infinite;
    overflow: hidden;
}

.ai-assistant-toggle::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s;
}

.ai-assistant-toggle:hover::before {
    transform: translateX(100%);
}

.ai-assistant-toggle:hover {
    transform: translateY(-6px) scale(1.1);
    box-shadow: 0 24px 48px rgba(30, 163, 139, 0.4);
}

.ai-icon {
    font-size: 2rem;
    position: relative;
    z-index: 1;
    line-height: 1;
    transition: transform 0.3s;
}

.pulse-dot {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 12px;
    height: 12px;
    background: var(--error-color);
    border-radius: 50%;
    border: 2px solid #fff;
    animation: pulse 2s infinite;
}

@keyframes floatButton {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}

/* Fenêtre de chat améliorée */
.ai-assistant-window {
    width: 440px;
    height: 680px;
    max-height: calc(100vh - 40px);
    background: var(--bg-primary);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    display: flex !important;
    flex-direction: column;
    overflow: hidden !important;
    position: fixed !important;
    bottom: 20px !important;
    right: 20px !important;
    z-index: 99999 !important;
    margin: 0 !important;
    padding: 0 !important;
    isolation: isolate;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* En-tête amélioré */
.ai-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px;
    background: var(--primary-gradient);
    color: #fff;
    position: relative;
    overflow: hidden;
}

.ai-header::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%) rotate(45deg); }
    100% { transform: translateX(100%) rotate(45deg); }
}

.ai-status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.ai-status-indicator.online {
    background: var(--success-color);
    animation: statusPulse 2s infinite;
}

.ai-status-indicator.offline {
    background: var(--text-muted);
}

@keyframes statusPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.ai-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    position: relative;
    z-index: 1;
}

.ai-info {
    flex: 1;
    position: relative;
    z-index: 1;
}

.ai-info h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.02em;
}

.ai-info p {
    margin: 4px 0 0 0;
    opacity: 0.9;
    font-size: 0.875rem;
    font-weight: 500;
}

.ai-actions {
    display: flex;
    gap: 8px;
    position: relative;
    z-index: 1;
}

.ai-action-btn, .ai-close {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    transition: all 0.2s;
}

.ai-action-btn:hover, .ai-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

/* Messages améliorés - Design professionnel */
.ai-messages {
    flex: 1;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding: 24px 20px;
    background: #f8f9fa;
    scroll-behavior: smooth;
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-height: calc(650px - 180px);
    box-sizing: border-box;
}

.welcome-message {
    text-align: center;
    padding: 40px 20px;
}

.welcome-icon {
    font-size: 3rem;
    margin-bottom: 16px;
}

.welcome-message h4 {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 12px;
    font-weight: 700;
}

.welcome-message p {
    color: var(--text-secondary);
    margin-bottom: 20px;
}

.welcome-features {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
    max-width: 280px;
    margin: 0 auto;
}

.welcome-features li {
    padding: 8px 0;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Wrapper pour chaque message */
.message-wrapper {
    display: flex;
    width: 100%;
    margin-bottom: 16px;
    animation: messageSlide 0.3s ease-out;
    box-sizing: border-box;
}

.user-message-wrapper {
    justify-content: flex-end;
}

.ai-message-wrapper {
    justify-content: flex-start;
}

@keyframes messageSlide {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Container du message */
.message-container {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    max-width: 80%;
    box-sizing: border-box;
    flex-wrap: nowrap;
}

.user-message-wrapper .message-container {
    flex-direction: row-reverse;
    margin-left: auto;
}

.ai-message-wrapper .message-container {
    margin-right: auto;
}

/* Avatar */
.message-avatar {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-avatar {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

/* Container de la bulle */
.message-bubble-container {
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-width: 100%;
}

/* Bulle de message */
.message-bubble {
    position: relative;
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
    max-width: 100%;
    box-sizing: border-box;
    line-height: 1.6;
    display: inline-block;
}

.user-bubble {
    background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
    color: #ffffff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 8px rgba(30, 163, 139, 0.25);
}

.ai-bubble {
    background: #ffffff;
    color: #1f2937;
    border: 1px solid #e5e7eb;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.message-text {
    font-size: 0.9375rem;
    line-height: 1.6;
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message-actions {
    display: flex;
    gap: 8px;
    margin-top: 8px;
    opacity: 0;
    transition: opacity 0.2s;
}

.message-bubble:hover .message-actions {
    opacity: 1;
}

.message-action-btn {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 6px;
    background: #f3f4f6;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    transition: all 0.2s;
    padding: 0;
}

.message-action-btn:hover {
    background: #1EA38B;
    color: #fff;
    transform: scale(1.05);
}


/* Indicateur de frappe amélioré */
.typing-indicator {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
    animation: typingSlide 0.3s ease-out;
}

.typing-avatar {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.typing-bubble {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    border-bottom-left-radius: 4px;
    padding: 12px 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.typing-dots {
    display: flex;
    gap: 4px;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #1EA38B;
    animation: typingBounce 1.4s infinite;
}

.typing-dots span:nth-child(2) { animation-delay: 0.2s; }
.typing-dots span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typingBounce {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.6;
    }
    30% {
        transform: translateY(-6px);
        opacity: 1;
    }
}

/* Suggestions améliorées */
.ai-suggestions {
    padding: 16px 20px;
    background: #ffffff;
    border-top: 1px solid #e5e7eb;
    max-height: 200px;
    overflow-y: auto;
}

.ai-suggestions h5 {
    margin: 0 0 12px 0;
    color: #1f2937;
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.suggestions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.suggestion-card {
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: left;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.suggestion-card:hover {
    background: linear-gradient(135deg, #1EA38B 0%, #FF8E2A 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 163, 139, 0.3);
    border-color: transparent;
}

.suggestion-icon {
    font-size: 1.25rem;
}

.suggestion-text {
    font-size: 0.8rem;
    line-height: 1.3;
    font-weight: 500;
}

/* Formulaire amélioré */
.ai-input-form {
    padding: 20px;
    background: var(--bg-primary);
    border-top: 1px solid var(--border-light);
}

.input-container {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.ai-input {
    flex: 1;
    padding: 16px 20px;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    outline: none;
    font-size: 0.875rem;
    background: var(--bg-secondary);
    transition: all 0.2s;
    resize: none;
    min-height: 52px;
}

.ai-input:focus {
    border-color: #1EA38B;
    background: var(--bg-primary);
    box-shadow: 0 0 0 4px rgba(30, 163, 139, 0.1);
}

.input-actions {
    display: flex;
    gap: 8px;
}

.voice-btn, .ai-send-button {
    width: 52px;
    height: 52px;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    transition: all 0.2s;
    flex-shrink: 0;
}

.voice-btn {
    background: var(--bg-secondary);
    color: var(--text-secondary);
}

.voice-btn:hover, .voice-btn.recording {
    background: var(--error-color);
    color: #fff;
    transform: scale(1.05);
}

.ai-send-button {
    background: var(--primary-gradient);
    color: #fff;
    box-shadow: var(--shadow-sm);
}

.ai-send-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.ai-send-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.send-loading {
    width: 20px;
    height: 20px;
    border: 2px solid transparent;
    border-top: 2px solid #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.input-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.char-count {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

.ai-disclaimer {
    font-size: 0.7rem;
    color: var(--text-muted);
    text-align: right;
    max-width: 200px;
}

/* Responsive */
@media (max-width: 480px) {
    .ai-assistant-window {
        width: calc(100vw - 32px);
        height: calc(100vh - 100px);
        max-height: 600px;
        bottom: 16px !important;
        right: 16px !important;
    }
    
    .suggestions-grid {
        grid-template-columns: 1fr;
    }
    
    .message-bubble {
        max-width: 220px;
    }
}

/* Scrollbar personnalisée */
.ai-messages::-webkit-scrollbar {
    width: 6px;
}

.ai-messages::-webkit-scrollbar-track {
    background: transparent;
}

.ai-messages::-webkit-scrollbar-thumb {
    background: var(--text-muted);
    border-radius: 3px;
}

.ai-messages::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}
</style>
@endpush

@push('scripts')
<script>
// Fonction Alpine.js pour l'AI Assistant - Doit être globale
window.aiAssistant = function() {
    return {
        isOpen: false,
        isLoading: false,
        isTyping: false,
        isRecording: false,
        isOnline: true,
        hasNewMessages: false,
        input: '',
        messages: [],
        quickSuggestions: [
            { id: 1, icon: '', text: 'Comment créer un cours ?' },
            { id: 2, icon: '', text: 'Comment sont calculés mes revenus ?' },
            { id: 3, icon: '', text: 'Conseils pour améliorer mes cours' },
            { id: 4, icon: '', text: 'Paramètres du compte formateur' }
        ],
        
        init() {
            this.loadMessages();
            this.checkConnection();
        },
        
        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.hasNewMessages = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },
        
        async sendMessage() {
            if (!this.input.trim() || this.isLoading) return;
            
            const userMessage = {
                id: Date.now(),
                role: 'user',
                content: this.input.trim(),
                timestamp: new Date()
            };
            
            this.messages.push(userMessage);
            this.input = '';
            this.isLoading = true;
            this.isTyping = true;
            
            await this.$nextTick();
            this.scrollToBottom();
            
            // Simuler une réponse de l'IA
            setTimeout(() => {
                this.isTyping = false;
                const aiMessage = {
                    id: Date.now() + 1,
                    role: 'assistant',
                    content: this.generateResponse(userMessage.content),
                    timestamp: new Date()
                };
                this.messages.push(aiMessage);
                this.isLoading = false;
                this.saveMessages();
                this.$nextTick(() => this.scrollToBottom());
            }, 2000);
        },
        
        sendQuickMessage(text) {
            this.input = text;
            this.sendMessage();
        },
        
        generateResponse(userMessage) {
            const responses = {
                'cours': "Pour créer un cours sur AfriCode, rendez-vous dans votre tableau de bord formateur, cliquez sur 'Créer un cours' et suivez les étapes : titre, description, contenu, prix et publication.",
                'revenus': "Vos revenus sont calculés sur 70% du prix de chaque vente. Les paiements sont effectués mensuellement autour du 15 du mois suivant.",
                'conseils': "Pour améliorer vos cours : soyez spécifique dans le titre, utilisez des vidéos de qualité, ajoutez des exercices pratiques et interagissez avec vos étudiants.",
                'paramètres': "Dans les paramètres formateur, vous pouvez gérer votre profil, vos méthodes de paiement, vos notifications et vos préférences de compte."
            };
            
            const lowerMessage = userMessage.toLowerCase();
            for (const [key, response] of Object.entries(responses)) {
                if (lowerMessage.includes(key)) {
                    return response;
                }
            }
            
            return "Je comprends votre question sur \"" + userMessage + "\". Pour une réponse précise, je vous recommande de consulter le centre d'aide d'AfriCode ou de contacter notre support.";
        },
        
        toggleVoice() {
            this.isRecording = !this.isRecording;
            // Implémentation de la reconnaissance vocale
            if (this.isRecording) {
                this.input = "Reconnaissance vocale activée...";
            }
        },
        
        copyMessage(content) {
            navigator.clipboard.writeText(content);
            // Afficher une notification de copie
        },
        
        regenerateMessage(messageId) {
            const messageIndex = this.messages.findIndex(m => m.id === messageId);
            if (messageIndex > -1) {
                this.messages.splice(messageIndex, 1);
                this.input = this.messages[messageIndex - 1]?.content || '';
                this.sendMessage();
            }
        },
        
        clearChat() {
            this.messages = [];
            localStorage.removeItem('africode-ai-chat');
        },
        
        scrollToBottom() {
            this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
        },
        
        formatTime(timestamp) {
            return new Date(timestamp).toLocaleTimeString('fr-FR', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        },
        
        saveMessages() {
            localStorage.setItem('africode-ai-chat', JSON.stringify(this.messages));
        },
        
        loadMessages() {
            const saved = localStorage.getItem('africode-ai-chat');
            if (saved) {
                this.messages = JSON.parse(saved);
            }
        },
        
        checkConnection() {
            this.isOnline = navigator.onLine;
        }
    };
};

// Écouteur d'événements Livewire
document.addEventListener('livewire:init', () => {
    Livewire.on('scroll-to-bottom', () => {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
});
</script>
@endpush