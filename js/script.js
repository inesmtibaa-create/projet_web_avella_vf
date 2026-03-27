

const items = document.querySelectorAll('.carousel .list .item');
let current = 0;


// Fonction pour aller à un slide
function goTo(index) {
    // Cacher l'item actuel
    items[current].classList.remove('opacity-100');
    items[current].classList.add('opacity-0');


    // Mettre à jour l'index
    current = (index + items.length) % items.length;


    // Afficher le nouvel item
    items[current].classList.remove('opacity-0');
    items[current].classList.add('opacity-100');
}


// Boutons
document.getElementById('next').onclick = () => goTo(current + 1);
document.getElementById('prev').onclick = () => goTo(current - 1);