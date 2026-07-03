import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
	const menuToggle = document.getElementById('menu-toggle');
	const menuPanel = document.getElementById('menu-panel');

	if (menuToggle && menuPanel) {
		menuToggle.addEventListener('click', () => {
			menuPanel.classList.toggle('hidden');
			menuPanel.classList.toggle('absolute');
			menuPanel.classList.toggle('left-4');
			menuPanel.classList.toggle('right-4');
			menuPanel.classList.toggle('top-20');
			menuPanel.classList.toggle('z-40');
			menuPanel.classList.toggle('rounded-xl');
			menuPanel.classList.toggle('bg-white');
			menuPanel.classList.toggle('p-4');
			menuPanel.classList.toggle('shadow-xl');
			menuPanel.classList.toggle('space-y-3');
			menuPanel.classList.toggle('block');
		});
	}

	const tabs = Array.from(document.querySelectorAll('.intent-tab'));
	const form = document.getElementById('property-search');
	const city = document.getElementById('search-city');
	const type = document.getElementById('search-type');
	const bed = document.getElementById('search-bed');
	const price = document.getElementById('search-price');
	const cards = Array.from(document.querySelectorAll('.property-card'));
	const counter = document.getElementById('results-count');
	const noResults = document.getElementById('no-results');

	let activeIntent = 'buy';

	const renderIntent = () => {
		tabs.forEach((tab) => {
			const isActive = tab.dataset.intent === activeIntent;
			tab.classList.toggle('bg-white', isActive);
			tab.classList.toggle('text-slate-900', isActive);
			tab.classList.toggle('shadow-sm', isActive);
			tab.classList.toggle('bg-transparent', !isActive);
			tab.classList.toggle('text-white/90', !isActive);
			tab.classList.toggle('opacity-95', !isActive);
		});
	};

	const applyFilters = () => {
		const cityVal = city ? city.value : 'all';
		const typeVal = type ? type.value : 'all';
		const bedVal = bed ? bed.value : 'all';
		const priceVal = price ? price.value : 'all';

		let visible = 0;

		cards.forEach((card) => {
			const matchesIntent = card.dataset.intent === activeIntent || activeIntent === 'all';
			const matchesCity = cityVal === 'all' || card.dataset.city === cityVal;
			const matchesType = typeVal === 'all' || card.dataset.type === typeVal;
			const matchesBed = bedVal === 'all' || Number(card.dataset.bed) >= Number(bedVal);
			const matchesPrice = priceVal === 'all' || Number(card.dataset.price) <= Number(priceVal);

			const isVisible = matchesIntent && matchesCity && matchesType && matchesBed && matchesPrice;
			card.style.display = isVisible ? '' : 'none';
			if (isVisible) {
				visible += 1;
			}
		});

		if (counter) {
			counter.textContent = `Showing ${visible} matching listings`;
		}

		if (noResults) {
			noResults.classList.toggle('hidden', visible !== 0);
		}
	};

	tabs.forEach((tab) => {
		tab.addEventListener('click', () => {
			activeIntent = tab.dataset.intent || 'buy';
			renderIntent();
			applyFilters();
		});
	});

	if (form) {
		form.addEventListener('submit', (event) => {
			event.preventDefault();
			applyFilters();
		});
	}

	renderIntent();
	applyFilters();

	const featuredSliders = Array.from(document.querySelectorAll('.featured-slider'));

	featuredSliders.forEach((slider) => {
		const image = slider.querySelector('.featured-slide-image');
		const prevBtn = slider.querySelector('.featured-slide-prev');
		const nextBtn = slider.querySelector('.featured-slide-next');
		const images = (slider.getAttribute('data-images') || '')
			.split('|')
			.map((src) => src.trim())
			.filter(Boolean);

		if (!image || images.length < 2) {
			return;
		}

		let index = 0;

		const setSlide = (newIndex) => {
			index = (newIndex + images.length) % images.length;
			image.classList.add('opacity-60');
			window.setTimeout(() => {
				image.src = images[index];
				image.classList.remove('opacity-60');
			}, 120);
		};

		prevBtn?.addEventListener('click', () => setSlide(index - 1));
		nextBtn?.addEventListener('click', () => setSlide(index + 1));

		window.setInterval(() => {
			setSlide(index + 1);
		}, 4500);
	});
});
