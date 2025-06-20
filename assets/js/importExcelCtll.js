document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  const spinner = document.getElementById('excelImportSpinner');
  if (form && spinner) {
    form.addEventListener('submit', function () {
      spinner.classList.remove('d-none');
    });
  }
}); 