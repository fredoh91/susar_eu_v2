
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

