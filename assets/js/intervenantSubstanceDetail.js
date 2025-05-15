// assets/js/intervenantSubstanceDetail.js
document.addEventListener('DOMContentLoaded', function() {
    var substanceList = document.querySelector('.substances');
    var addButton = document.querySelector('.btn-new');
    var index = substanceList.querySelectorAll('li').length;

    addButton.addEventListener('click', function() {
        var prototype = substanceList.dataset.prototype;
        var newForm = prototype.replace(/__name__/g, index);
        var li = document.createElement('li');
        // li.innerHTML = newForm + '<button type="button" class="btn btn-danger remove-substance">Supprimer</button>';
        li.innerHTML = newForm;
        substanceList.appendChild(li);
        index++;
    });

    substanceList.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-substance')) {
            e.target.closest('li').remove();
        }
    });
});


// Gestion du menu déroulant évaluateur/DMM/pôle
// document.addEventListener('DOMContentLoaded', function() {
//     const evaluateurSelect = document.querySelector('#intervenant_substance_dmm_substances_evaluateur');
//     const dmmField = document.querySelector('#intervenant_substance_dmm_substances_dmm');
//     const poleCourtField = document.querySelector('#intervenant_substance_dmm_substances_pole_court');
//     const idField = document.querySelector('#intervenant_substance_dmm_substances_id');

//     evaluateurSelect.addEventListener('change', function() {
//         const selectedOption = evaluateurSelect.options[evaluateurSelect.selectedIndex].value;
//         const columns = selectedOption.split('|');

//         if (columns.length === 5) {
//             dmmField.value = columns[1];
//             poleCourtField.value = columns[2];
//             idField.value = columns[4];
//         } else {
//             dmmField.value = '';
//             poleCourtField.value = '';
//             idField.value = '';
//         }
//     });
// });


// // Couleur du fond du formulaire selon que la case à cocher "Inactif" est cochée ou pas
// const checkbox = document.querySelector('#intervenant_substance_dmm_substances_inactif');
// const div = document.querySelector('#form-bg');
// const body = document.querySelector('body');

// checkbox.addEventListener('change', function() {
//     if (checkbox.checked) {
//         div.classList.remove('rouge-clair');
//         div.classList.add('vert-clair');
        
//         body.classList.remove('rouge-clair');
//         body.classList.add('vert-clair');
//     } else {
//         div.classList.remove('vert-clair');
//         div.classList.add('rouge-clair'); 
        
//         body.classList.remove('vert-clair');
//         body.classList.add('rouge-clair');
//     }
//   });


function toggleBackgroundColor(checkboxSelector, divSelector, bodySelector) {
    const checkbox = document.querySelector(checkboxSelector);
    const div = document.querySelector(divSelector);
    const body = document.querySelector(bodySelector);
  
    // Ajoutez un gestionnaire d'événements pour la case à cocher
    checkbox.addEventListener('change', function() {
      if (checkbox.checked) {
        div.classList.remove('vert-clair');
        div.classList.add('rouge-clair');
  
        body.classList.remove('vert-clair');
        body.classList.add('rouge-clair');
      } else {
        div.classList.remove('rouge-clair');
        div.classList.add('vert-clair');
  
        body.classList.remove('rouge-clair');
        body.classList.add('vert-clair');
      }
    });
  
    // Vérifiez l'état initial de la case à cocher et appliquez la couleur de fond en conséquence
    if (checkbox.checked) {
      div.classList.remove('vert-clair');
      div.classList.add('rouge-clair');
  
      body.classList.remove('vert-clair');
      body.classList.add('rouge-clair');
    } else {
      div.classList.remove('rouge-clair');
      div.classList.add('vert-clair');
  
      body.classList.remove('rouge-clair');
      body.classList.add('vert-clair');
    }
  }
  

var ckBxAssSub = document.getElementById('intervenant_substance_dmm_substances_AssociationDeSubstances');

var divNoAssoSub = document.getElementById('no_asso_sub');

var divAssoSub = document.getElementById('asso_sub');

ckBxAssSub.addEventListener("click", affDivSub);

function affDivSub () {
    if (ckBxAssSub.checked) {
        // divNoAssoSub.style.display = "none";
        divAssoSub.style.display = "block";
    } else {
        // divNoAssoSub.style.display = "block";
        divAssoSub.style.display = "none";
    }
}

// Recopie du contenu du champ "intervenant_substance_dmm_substances_ActiveSubstanceHighLevel" "parent" vers les champs "enfants"

// document.getElementById('btn-enregistrer').onclick = function() {
//   const SubParent =  document.getElementById('intervenant_substance_dmm_substances_ActiveSubstanceHighLevel')
//   const SubEnfants = document.querySelectorAll('[id^="intervenant_substance_dmm_substances_intervenantSubstanceDMMSubstances_"][id$="_active_substance_high_level"]');

//   // console.log (SubParent);
//   for (const SubEnfant of SubEnfants) {
//     // console.log(SubEnfant.id);
//     SubEnfant.value = SubParent.value
//     // console.log(SubEnfant.value);
//   }
// };

const form = document.querySelector('form[name="intervenant_substance_dmm_substances"]');

form.addEventListener('submit', (event) => {

  const SubParent =  document.getElementById('intervenant_substance_dmm_substances_ActiveSubstanceHighLevel')
  const SubEnfants = document.querySelectorAll('[id^="intervenant_substance_dmm_substances_intervenantSubstanceDMMSubstances_"][id$="_active_substance_high_level"]');

  // console.log (SubParent);
  for (const SubEnfant of SubEnfants) {
    // console.log(SubEnfant.id);
    SubEnfant.value = SubParent.value
    // console.log(SubEnfant.value);
  }
  // On empêche l'envoi du formulaire par défaut, pour attendre que le code JS soit terminé :
  event.preventDefault();

  // Envoyer le formulaire manuellement après la recopie (si nécessaire)
  form.submit();
});





affDivSub()

toggleBackgroundColor('#intervenant_substance_dmm_substances_inactif', '#form-bg', 'body');
