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
btest = document.getElementById('test');

let testjson = async() => { let result = await fetch('public/FINALjsonAPI.json')

    result.json();
}

testjson();