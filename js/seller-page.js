// les boutons 
// bouton login 
const loginBtn = document.getElementById("loginBtn");


loginBtn.addEventListener("click", () => {
  window.location.href = "../pages/connexion.html";
});




// slides taa l adds men fouk

const slider = document.getElementById("slider");
const slides = slider.children;

let index = 0;

function updateSlider() {
  slider.style.transform = `translateX(-${index * 100}%)`;
}

function nextSlide() {
  index++;
  if (index >= slides.length) {
    index = 0; // loop back
  }
  updateSlider();
}

function prevSlide() {
  index--;
  if (index < 0) {
    index = slides.length - 1; // go to last
  }
  updateSlider();
}

setInterval(() => {
  nextSlide();
}, 4000); 


 // El fenetre taa l produit :

 
    const prodBtn = document.getElementById('prod1'); 
    const modal = document.getElementById('colorModal');
    const closeBtn = document.getElementById('closeModal');
    const mainPage = document.getElementById('produitVendeur'); 

    prodBtn.addEventListener('click', () => {
        modal.classList.remove('hidden'); 
        mainPage.classList.add('blur-sm'); 
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden'); 
        mainPage.classList.remove('blur-sm'); 
    });

    // Optional: tclicki l barra tetnahha zeda
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            mainPage.classList.remove('blur-sm');
        }
    });



   

