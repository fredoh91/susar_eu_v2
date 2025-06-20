document.addEventListener('DOMContentLoaded', function () {
  const btnRecherche = document.querySelector('.rech-chp_rech button, .rech-chp_rech input[type="submit"]');
  // const btnExport = document.querySelector('.rech-chp_excel button, .rech-chp_excel input[type="submit"]');
  const btnReset = document.querySelector('.rech-chp_reset button, .rech-chp_reset input[type="submit"]');
  const spinner = document.getElementById('actionSpinner');
  const spinnerMsg = document.getElementById('actionSpinnerMsg');
  let hideTimeout = null;

  function showSpinner(message) {
    if (spinner && spinnerMsg) {
      spinnerMsg.textContent = message;
      spinner.classList.remove('d-none');
      if (hideTimeout) clearTimeout(hideTimeout);
      hideTimeout = setTimeout(() => {
        spinner.classList.add('d-none');
      }, 60000); // 60 secondes
    }
  }

  if (btnRecherche) {
    btnRecherche.addEventListener('click', function () {
      showSpinner('Recherche en cours...');
    });
  }
  // Suppression du spinner pour l'export Excel
  if (btnReset) {
    btnReset.addEventListener('click', function () {
      showSpinner('Reset en cours...');
    });
  }
}); 