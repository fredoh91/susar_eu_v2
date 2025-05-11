// console.log("Test Fredoh");


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


// Pour ouvrir l'onglet autre FU
document.addEventListener('DOMContentLoaded', function () {
    // Sélectionne tous les boutons avec la classe "btn-open-autre-fu"
    const autresFUButtons = document.querySelectorAll('.btn-open-autre-fu');

    // Ajoute un gestionnaire d'événements à chaque bouton
    autresFUButtons.forEach(button => {
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



// // console.log("Test JS module : 1");

// const excelExport = require('./modules/excelExport');

// // console.log("Test JS module : 2");

// document.addEventListener('DOMContentLoaded', function() {
//     excelExport.initializeExcelExport();
// });

// // console.log("Test JS module : 3");





