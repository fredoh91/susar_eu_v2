document.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.getElementById('BtnCloseTab');

    if (closeButton) {
        closeButton.addEventListener('click', function () {
            // Rafraîchit l'onglet parent
            // if (window.opener) {
            //     window.opener.location.reload(); // Rafraîchit l'onglet parent
            // }

            // Ferme l'onglet actuel
            window.close();
        });
    }
});