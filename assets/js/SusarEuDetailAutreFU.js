
// console.log("Coucou, autre FU");


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


// Pour ouvrir l'onglet détail/évaluation
document.addEventListener('DOMContentLoaded', function () {
    // Sélectionne tous les boutons avec la classe "btn-open-detail"
    const detailButtons = document.querySelectorAll('.btn-open-detail');

    // Ajoute un gestionnaire d'événements à chaque bouton
    detailButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Empêche le comportement par défaut du bouton

            // Récupère l'URL à partir de l'attribut "data-url"
            const url = button.getAttribute('data-url');

            // Ouvre l'URL dans un nouvel onglet
            if (url) {
                window.open(url, '_blank', 'noopener=false');
            } else {
                console.error('URL non définie pour ce bouton.');
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Sélectionne tous les boutons avec la classe "btn-toggle-MedHist"
    const toggleButtons = document.querySelectorAll('.btn-toggle-MedHist');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            // Récupère l'id unique depuis l'attribut data-id
            const susarId = button.getAttribute('data-id');

            // Récupère la div correspondante avec l'id unique
            const medHistDiv = document.getElementById(`tab_med_hist_${susarId}`);

            if (medHistDiv) {
                // Bascule la classe d-none pour afficher ou masquer la div
                medHistDiv.classList.toggle('d-none');

                // Change le texte du bouton en fonction de l'état de la div
                if (medHistDiv.classList.contains('d-none')) {
                    button.textContent = 'Afficher les antécédents médicaux';
                } else {
                    button.textContent = 'Masquer les antécédents médicaux';
                }
            } else {
                console.error(`Div avec l'id tab_med_hist_${susarId} introuvable.`);
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('scrollToTopBtn');
    const showAfter = 300; // px à partir duquel le bouton apparaît (modifiable)

    window.addEventListener('scroll', function () {
        if (window.scrollY > showAfter) {
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
        }
    });

    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

