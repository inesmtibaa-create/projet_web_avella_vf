const modal = document.getElementById('productModal');
const closeBtn = document.getElementById('closeModal');
const modalImg = document.getElementById('modal-img');
const modalName = document.getElementById('modal-name');
const modalPrice = document.getElementById('modal-price');
const modalDesc = document.getElementById('modal-desc');
const modalColors = document.getElementById('modal-colors');
function openProductModal(productId) {
  const product = products.find(p => p.id === productId);
  if (!product) return; // securite : id inexistant
  modalImg.src = product.image;
  modalName.textContent = product.name;
  modalPrice.textContent = product.price;
  modalDesc.textContent = product.description;
  modalColors.innerHTML = ''; // reset : vider les couleurs precedentes
  product.colors.forEach(color => {
    const btn = document.createElement('button');
    btn.title = color.name;
    btn.style.backgroundColor = color.hex;
    btn.className = 'w-8 h-8 rounded-full border-2 border-gray-300 hover:scale-110 transition cursor-pointer';
    btn.addEventListener('click', () => {
      modalImg.src = color.image;
    });
    modalColors.appendChild(btn);
  });
  modal.classList.remove('hidden');
}
// 3. Fermer le modal : 2 facons
closeBtn.addEventListener('click', () => {
  modal.classList.add('hidden');
});
document.querySelectorAll('.product-card').forEach(card => {
  card.addEventListener('click', () => {
    openProductModal(Number(card.dataset.id));
  });
});