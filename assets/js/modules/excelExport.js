// assets/js/modules/excelExport.js

/**
 * Télécharge un blob en tant que fichier
 * @param {Blob} blob - Le blob à télécharger
 * @param {string} filename - Le nom du fichier
 */
function downloadBlob(blob, filename) {
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = filename;
    
    document.body.appendChild(a);
    a.click();
    
    window.URL.revokeObjectURL(url);
    a.remove();
}

/**
 * Exporte les données du formulaire vers Excel
 * @param {string} exportUrl - L'URL d'export
 * @param {FormData} formData - Les données du formulaire
 * @returns {Promise<Blob>}
 */
function exportToExcel(exportUrl, formData) {

    console.log("exportUrl : ",exportUrl);
    console.log("formData : ",formData);


    return fetch(exportUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de l\'export Excel');
        }
        return response.blob();
    });
}

/**
 * Initialise la gestion de l'export Excel sur un formulaire
 * @param {string} formSelector - Le sélecteur du formulaire
 * @param {string} exportButtonName - Le nom du bouton d'export Excel
 */
function initializeExcelExport(formSelector, exportButtonName) {
    formSelector = formSelector || 'form';
    exportButtonName = exportButtonName || 'search_susar_eu[exportExcel]';
    // console.log("Test JS module : 4");
    const exportForm = document.querySelector(formSelector);
    if (!exportForm) return;

    // console.log("Test JS module : 5");

    exportForm.addEventListener('submit', function(e) {
        // Vérifie si c'est bien le bouton d'export Excel qui a été cliqué
        const isExportButton = e.submitter && e.submitter.name === exportButtonName;
        
        // console.log("Test JS module : 6");


        
        
        
        // Si ce n'est pas le bouton d'export, on laisse le formulaire se soumettre normalement
        if (!isExportButton) {
            return;
        }
        console.log("e.submitter.name : ",e.submitter.name);
        
        // Si c'est le bouton d'export, on empêche la soumission normale et on gère l'export
        e.preventDefault();
        
        // console.log("e : ",e);
        const exportUrl = exportForm.dataset.exportUrl;

        console.log("exportUrl : ",exportUrl);


        if (!exportUrl) {
            console.error('URL d\'export non définie');
            return;
        }

        const filename = exportForm.dataset.exportFilename || 'export.xlsx';

        console.log("filename : ",filename);


        exportToExcel(exportUrl, new FormData(exportForm))
            .then(function(blob) {
                downloadBlob(blob, filename);
            })
            .catch(function(error) {
                console.error('Erreur lors de l\'export:', error);
                alert('Une erreur est survenue lors de l\'export Excel');
            });
    });
}

module.exports = {
    initializeExcelExport: initializeExcelExport,
    exportToExcel: exportToExcel,
    downloadBlob: downloadBlob
};




















// version module

// // assets/js/modules/excelExport.js

// /**
//  * Télécharge un blob en tant que fichier
//  * @param {Blob} blob - Le blob à télécharger
//  * @param {string} filename - Le nom du fichier
//  */
// export function downloadBlob(blob, filename) {
//     const url = window.URL.createObjectURL(blob);
//     const a = document.createElement('a');
//     a.style.display = 'none';
//     a.href = url;
//     a.download = filename;
    
//     document.body.appendChild(a);
//     a.click();
    
//     window.URL.revokeObjectURL(url);
//     a.remove();
// }

// /**
//  * Exporte les données du formulaire vers Excel
//  * @param {string} exportUrl - L'URL d'export
//  * @param {FormData} formData - Les données du formulaire
//  * @returns {Promise<Blob>}
//  */
// export async function exportToExcel(exportUrl, formData) {
//     const response = await fetch(exportUrl, {
//         method: 'POST',
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest',
//         },
//         body: formData
//     });

//     if (!response.ok) {
//         throw new Error('Erreur lors de l\'export Excel');
//     }

//     return response.blob();
// }

// /**
//  * Initialise la gestion de l'export Excel sur un formulaire
//  * @param {string} formSelector - Le sélecteur du formulaire
//  * @param {string} exportButtonName - Le nom du bouton d'export Excel
//  */
// export function initializeExcelExport(formSelector = 'form', exportButtonName = 'search_susar_eu[exportExcel]') {
//     const exportForm = document.querySelector(formSelector);
//     if (!exportForm) return;

//     exportForm.addEventListener('submit', async function(e) {
//         // Vérifie si c'est bien le bouton d'export Excel qui a été cliqué
//         const isExportButton = e.submitter && e.submitter.name === exportButtonName;
        
//         // Si ce n'est pas le bouton d'export, on laisse le formulaire se soumettre normalement
//         if (!isExportButton) {
//             return;
//         }

//         // Si c'est le bouton d'export, on empêche la soumission normale et on gère l'export
//         e.preventDefault();
        
//         const exportUrl = exportForm.dataset.exportUrl;
//         if (!exportUrl) {
//             console.error('URL d\'export non définie');
//             return;
//         }

//         const filename = exportForm.dataset.exportFilename || 'export.xlsx';

//         try {
//             const blob = await exportToExcel(exportUrl, new FormData(exportForm));
//             downloadBlob(blob, filename);
//         } catch (error) {
//             console.error('Erreur lors de l\'export:', error);
//             alert('Une erreur est survenue lors de l\'export Excel');
//         }
//     });
// }
