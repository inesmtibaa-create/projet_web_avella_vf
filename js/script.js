document.addEventListener("DOMContentLoaded", () => {
	const openButton = document.getElementById("open-sidebar");
	const closeButton = document.getElementById("close-sidebar");
	const sidebarWrapper = document.getElementById("sidebar-wrapper");
	const sidebar = document.getElementById("sidebar");
	const overlay = document.getElementById("sidebar-overlay");

	if (!openButton || !closeButton || !sidebarWrapper || !sidebar || !overlay) {
		return;
	}

	let isSidebarOpen = false;

	const openSidebar = () => {
		if (isSidebarOpen) {
			return;
		}

		isSidebarOpen = true;
		sidebarWrapper.classList.remove("pointer-events-none", "opacity-0");
		sidebarWrapper.classList.add("pointer-events-auto", "opacity-100");
		sidebar.classList.remove("-translate-x-full");
		sidebar.classList.add("translate-x-0");
		sidebar.setAttribute("aria-hidden", "false");
		document.body.classList.add("overflow-hidden");
	};

	const closeSidebar = () => {
		if (!isSidebarOpen) {
			return;
		}

		isSidebarOpen = false;
		sidebar.classList.remove("translate-x-0");
		sidebar.classList.add("-translate-x-full");
		sidebar.setAttribute("aria-hidden", "true");
		sidebarWrapper.classList.remove("pointer-events-auto", "opacity-100");
		sidebarWrapper.classList.add("pointer-events-none", "opacity-0");
		document.body.classList.remove("overflow-hidden");
	};

	openButton.addEventListener("click", openSidebar);
	closeButton.addEventListener("click", closeSidebar);
	overlay.addEventListener("click", closeSidebar);

	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape") {
			closeSidebar();
		}
	});
});

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

