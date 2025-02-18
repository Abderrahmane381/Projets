document.addEventListener('DOMContentLoaded', function() {
    const flashContainer = document.getElementById('flash-messages');

    if (window.flashMessages) {
        Object.entries(window.flashMessages).forEach(([type, message]) => {
            if (message) {
                createFlashMessage(type, message);
            }
        });
    }

    // Fonction pour créer et afficher le message
    function createFlashMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flash-message flash-${type}`;
        messageDiv.innerHTML = `
            <span>${message}</span>
            <button class="flash-close">&times;</button>
        `;

        // Fermeture manuelle du message
        messageDiv.querySelector('.flash-close').addEventListener('click', () => {
            messageDiv.remove();
        });

        // Ajouter le message au conteneur
        flashContainer.appendChild(messageDiv);

        // Animer l'apparition
        setTimeout(() => messageDiv.classList.add('show'), 10);

        // Supprimer après 5 secondes
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
});
