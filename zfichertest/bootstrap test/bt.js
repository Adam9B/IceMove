import './bootstrap.js';
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

function initApp() {
    // menu burger
    const burger = document.getElementById('burger');
    const menu = document.getElementById('menu');
    const overlay = document.getElementById('overlay');

    if (burger && menu && overlay) {
        burger.addEventListener('click', () => {
            burger.classList.toggle('active');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            overlay.classList.add('fade-in-element');
            console.log('click sur le bouton burger');
        });

        overlay.addEventListener('click', () => {
            burger.classList.remove('active');
            menu.classList.remove('active');
            overlay.classList.remove('active');
            console.log("click sur l'overlay");
        });
    }

    // flecheRetour
    const flecheRetour = document.getElementById('flecheRetour');
    if (flecheRetour) {
        flecheRetour.addEventListener('click', () => {
            window.history.back();
            console.log('fleche retour a fonctionné');
        });
    }

    // Modal management
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Setup modal event listeners if elements exist
    const openFirstModal = document.getElementById('openFirstModal');
    if (openFirstModal) {
        openFirstModal.addEventListener('click', () => {
            showModal('firstModal');
        });
    }
    const closeFirstModal = document.getElementById('closeFirstModal');
    if (closeFirstModal) {
        closeFirstModal.addEventListener('click', () => {
            hideModal('firstModal');
        });
    }
    const firstModal = document.getElementById('firstModal');
    if (firstModal) {
        firstModal.addEventListener('click', (event) => {
            if (event.target === firstModal) {
                hideModal('firstModal');
            }
        });
    }
    // ... répète ce pattern pour les autres modals (seanceModal, programmeModal) ...

    // Exemple pour ouvrir Seance Modal
    const openSeanceModal = document.getElementById('openSeanceModal');
    if (openSeanceModal) {
        openSeanceModal.addEventListener('click', () => {
            hideModal('firstModal');
            setTimeout(() => {
                showModal('seanceModal');
            }, 300);
        });
    }
    const closeSeanceModal = document.getElementById('closeSeanceModal');
    if (closeSeanceModal) {
        closeSeanceModal.addEventListener('click', () => {
            hideModal('seanceModal');
        });
    }
    const seanceModal = document.getElementById('seanceModal');
    if (seanceModal) {
        seanceModal.addEventListener('click', (event) => {
            if (event.target === seanceModal) {
                hideModal('seanceModal');
            }
        });
    }
    // idem pour programmeModal...

    const openProgrammeModal = document.getElementById('openProgrammeModal');
if (openProgrammeModal) {
    openProgrammeModal.addEventListener('click', () => {
        hideModal('firstModal');
        setTimeout(() => {
            showModal('programmeModal');
        }, 300);
    });
}

const closeProgrammeModal = document.getElementById('closeProgrammeModal');
if (closeProgrammeModal) {
    closeProgrammeModal.addEventListener('click', () => {
        hideModal('programmeModal');
    });
}

const programmeModal = document.getElementById('programmeModal');
if (programmeModal) {
    programmeModal.addEventListener('click', (event) => {
        if (event.target === programmeModal) {
            hideModal('programmeModal');
        }
    });
}
}

// Appel initial + à chaque navigation Turbo
document.addEventListener('turbo:load', initApp);
