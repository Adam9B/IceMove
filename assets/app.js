import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// menu burger
const burger = document.getElementById('burger');
const menu = document.getElementById('menu');
const overlay = document.getElementById('overlay');


function Burger(){
burger.addEventListener('click', () => {
    burger.classList.toggle('active');
    menu.classList.toggle('active');
    overlay.classList.toggle('active');
    overlay.classList.add('fade-in-element');
    console.log('click sur le bouton burger')
});

overlay.addEventListener('click', () => {
    burger.classList.remove('active');
    menu.classList.remove('active');
    overlay.classList.remove('active');
   
    console.log("click sur l'overlay")
});

console.log('on est a la fin')

}

Burger();

// function transitionOverlay(){
    
// }

// transitionOverlay();


// fin menu Burger

// flecheRetour

const flecheRetour = document.getElementById('flecheRetour');
flecheRetour.addEventListener('click', () => {
    window.history.back();
    console.log('fleche retour a fonctionné')
});

// fin flecheRetour


// modal accueil    

// Fonction pour afficher un modal spécifique avec animation
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'flex';
    setTimeout(() => {
      modal.classList.add('show');
    }, 10); // Ajout de la classe après un court délai pour déclencher l'animation
  }

  // Fonction pour masquer un modal spécifique avec animation
  function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('show');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300); // Attente de la fin de l'animation avant de masquer complètement
  }

  // Gestion du premier modal
  document.getElementById('openFirstModal').addEventListener('click', () => {
    showModal('firstModal');
  });

  document.getElementById('closeFirstModal').addEventListener('click', () => {
    hideModal('firstModal');
  });

  // Fermer le modal en cliquant sur l'overlay (partie sombre)
  document.getElementById('firstModal').addEventListener('click', (event) => {
    if (event.target === document.getElementById('firstModal')) {
      hideModal('firstModal');
    }
  });

  // Gestion du modal Séance
  document.getElementById('openSeanceModal').addEventListener('click', () => {
    hideModal('firstModal'); // Masquer le premier modal avant d'ouvrir le suivant
    setTimeout(() => {
      showModal('seanceModal');
    }, 300); // Attendre la fin de l'animation de fermeture
  });

  document.getElementById('closeSeanceModal').addEventListener('click', () => {
    hideModal('seanceModal');
  });

  document.getElementById('seanceModal').addEventListener('click', (event) => {
    if (event.target === document.getElementById('seanceModal')) {
      hideModal('seanceModal');
    }
  });

  // Gestion du modal Programme
  document.getElementById('openProgrammeModal').addEventListener('click', () => {
    hideModal('firstModal'); // Masquer le premier modal avant d'ouvrir le suivant
    setTimeout(() => {
      showModal('programmeModal');
    }, 300); // Attendre la fin de l'animation de fermeture
  });

  document.getElementById('closeProgrammeModal').addEventListener('click', () => {
    hideModal('programmeModal');
  });

  document.getElementById('programmeModal').addEventListener('click', (event) => {
    if (event.target === document.getElementById('programmeModal')) {
      hideModal('programmeModal');
    }
  });

  // Actions des boutons dans le modal Séance
//   document.getElementById('consulterSeance').addEventListener('click', () => {
//     alert('Vous avez cliqué sur "Consulter" pour Séance.');
//   });

//   document.getElementById('creerSeance').addEventListener('click', () => {
//     alert('Vous avez cliqué sur "Créer" pour Séance.');
//   });

  // Actions des boutons dans le modal Programme
//   document.getElementById('consulterProgramme').addEventListener('click', () => {
//     alert('Vous avez cliqué sur "Consulter" pour Programme.');
//   });

//   document.getElementById('creerProgramme').addEventListener('click', () => {
//     alert('Vous avez cliqué sur "Créer" pour Programme.');
//   });

// fin modal accueil