const wrapper = document.getElementById("categories-wrapper");
const cardWidth = 276; // 260px card + 2*8px margin
let offset = 0;
const maxOffset = -(wrapper.children.length - 4) * (cardWidth - 45); // show 4 at a time

function updateSlider() {
    wrapper.style.transform = `translateX(${offset}px)`;
}

function nextSlide() {
    offset -= cardWidth;
    if (offset < maxOffset) offset = 0; // loop back
    updateSlider();
}

function prevSlide() {
    offset += cardWidth;
    if (offset > 0) offset = maxOffset; // loop back
    updateSlider();
}


const slider = document.getElementById("slider");
const slides = slider.children;

let index = 0;

function updateSliderpub() {
    slider.style.transform = `translateX(-${index * 100}%)`;
}

function nextSlidepub() {
    index++;
    if (index >= slides.length) {
        index = 0; // loop back
    }
    updateSliderpub();
}

function prevSlidepub() {
    index--;
    if (index < 0) {
        index = slides.length - 1; // go to last
    }
    updateSliderpub();
}

setInterval(() => {
    nextSlidepub();
}, 4000);