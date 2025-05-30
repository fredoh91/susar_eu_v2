
// console.log("Coucoubis");

document.getElementById('toggleBtnMedConcom').addEventListener('click', function() {
    // console.log('aff/mask concom');
    var medicamentsDiv = document.getElementById('med_concomitant');
    
    var toggleBtn = document.getElementById('toggleBtnMedConcom');
    
    medicamentsDiv.classList.toggle('d-none');

    // Changement du texte du bouton
    if (medicamentsDiv.classList.contains('d-none')) {
        toggleBtn.textContent = 'Afficher les médicaments concomitants';
    } else {
        toggleBtn.textContent = 'Masquer les médicaments concomitants';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.getElementById('BtnCloseTab');

    if (closeButton) {
        closeButton.addEventListener('click', function () {
            // Rafraîchit l'onglet parent
            if (window.opener) {
                window.opener.location.reload(); // Rafraîchit l'onglet parent
            }

            // Ferme l'onglet actuel
            window.close();
        });
    }
});