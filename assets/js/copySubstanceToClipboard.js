document.addEventListener('DOMContentLoaded', function () {

    
    const copyButton = document.getElementById('copy-button');
    const textareaToCopy = document.getElementById('lst_HL_SA');
    const copyMessage = document.getElementById('copy-message');
    
    // const toggleBtn = document.getElementById('toggleBtnMedConcom');

        copyButton.addEventListener('click', function() {
                        
            // Sélectionner le contenu du textarea
            textareaToCopy.select();
            textareaToCopy.setSelectionRange(0, 99999); // Pour les appareils mobiles
            
        // Copier le texte dans le presse-papiers
        navigator.clipboard.writeText(textareaToCopy.value).then(() => {
            // Afficher le message de confirmation
            copyMessage.textContent = 'Contenu copié dans le presse-papiers !';
            copyMessage.style.display = 'block';
            
            // Masquer le message après 3 secondes
            setTimeout(() => {
                copyMessage.style.display = 'none';
            }, 3000);
        }).catch(err => {
            console.error('Erreur lors de la copie dans le presse-papiers : ', err);
            copyMessage.textContent = 'Erreur lors de la copie.';
            copyMessage.style.display = 'block';
        });
    });
});