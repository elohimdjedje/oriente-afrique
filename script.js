document.addEventListener('DOMContentLoaded', function() {
    // Animation pour les éléments de fonctionnalités
    const features = document.querySelectorAll('.feature');
    
    features.forEach((feature, index) => {
        setTimeout(() => {
            feature.style.opacity = '1';
            feature.style.transform = 'translateY(0)';
        }, 300 * index);
    });
    
    // Simulation du chatbot
    const chatbotFeature = document.querySelector('.feature:last-child');
    
    chatbotFeature.addEventListener('click', function() {
        alert('Le chatbot est en cours de développement. Il sera bientôt disponible !');
    });
    
    // Animation du bouton principal
    const mainButton = document.querySelector('.hero-content .btn');
    
    mainButton.addEventListener('mouseover', function() {
        this.style.transform = 'scale(1.05)';
    });
    
    mainButton.addEventListener('mouseout', function() {
        this.style.transform = 'scale(1)';
    });

    // Chatbot logo draggable and clickable
    const chatbotLogo = document.getElementById('chatbot-logo');
    let isDragging = false, offsetX = 0, offsetY = 0;
    // Position initiale
    const initialRight = 30;
    const initialBottom = 30;

    if (chatbotLogo) {
        // Positionne le logo en bas à droite au départ
        chatbotLogo.style.position = 'fixed';
        chatbotLogo.style.right = initialRight + 'px';
        chatbotLogo.style.bottom = initialBottom + 'px';
        chatbotLogo.style.left = 'auto';
        chatbotLogo.style.top = 'auto';

        chatbotLogo.addEventListener('mousedown', function(e) {
            isDragging = true;
            offsetX = e.clientX - chatbotLogo.getBoundingClientRect().left;
            offsetY = e.clientY - chatbotLogo.getBoundingClientRect().top;
            chatbotLogo.style.transition = 'none';
            // Fixe la position courante pour le déplacement
            chatbotLogo.style.left = chatbotLogo.getBoundingClientRect().left + 'px';
            chatbotLogo.style.top = chatbotLogo.getBoundingClientRect().top + 'px';
            chatbotLogo.style.right = 'auto';
            chatbotLogo.style.bottom = 'auto';
        });

        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                let minLeft = 30; // marge minimale à gauche
                let newLeft = e.clientX - offsetX;
                let newTop = e.clientY - offsetY;
                // Empêcher de sortir de l'écran et de rester collé à gauche
                newLeft = Math.max(minLeft, Math.min(window.innerWidth - chatbotLogo.offsetWidth, newLeft));
                newTop = Math.max(0, Math.min(window.innerHeight - chatbotLogo.offsetHeight, newTop));
                chatbotLogo.style.left = newLeft + 'px';
                chatbotLogo.style.top = newTop + 'px';
            }
        });

        function isOverForbiddenZone(rect) {
            // Sélecteurs des zones à éviter (texte, images, logos, boutons, navigation, etc.)
            const forbiddenSelectors = [
                '.btn', 'nav', '.hero-content', '.feature', '.footer-section',
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'li', 'ul', 'ol',
                'img', '.logo'
            ];
            for (const selector of forbiddenSelectors) {
                const elements = document.querySelectorAll(selector);
                for (const el of elements) {
                    const elRect = el.getBoundingClientRect();
                    // Vérifie le chevauchement
                    if (
                        rect.left < elRect.right &&
                        rect.right > elRect.left &&
                        rect.top < elRect.bottom &&
                        rect.bottom > elRect.top
                    ) {
                        return true;
                    }
                }
            }
            return false;
        }

        document.addEventListener('mouseup', function(e) {
            if (isDragging) {
                isDragging = false;
                chatbotLogo.style.transition = '';
                // Détecter si le logo est au centre (par exemple, dans le quart central de l'écran)
                const rect = chatbotLogo.getBoundingClientRect();
                const centerZone = {
                    left: window.innerWidth * 0.25,
                    right: window.innerWidth * 0.75 - chatbotLogo.offsetWidth,
                    top: window.innerHeight * 0.25,
                    bottom: window.innerHeight * 0.75 - chatbotLogo.offsetHeight
                };
                // Si au centre ou sur une zone interdite, retour à la position initiale
                if (
                    (rect.left > centerZone.left &&
                    rect.left < centerZone.right &&
                    rect.top > centerZone.top &&
                    rect.top < centerZone.bottom)
                    || isOverForbiddenZone(rect)
                ) {
                    chatbotLogo.style.left = 'auto';
                    chatbotLogo.style.top = 'auto';
                    chatbotLogo.style.right = initialRight + 'px';
                    chatbotLogo.style.bottom = initialBottom + 'px';
                }
            }
        });

        chatbotLogo.addEventListener('click', function(e) {
            if (!isDragging) {
                window.location.href = 'chatbot.html'; // Mets ici le lien de ta page chatbot
            }
        });
    }
});