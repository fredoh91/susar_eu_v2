
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