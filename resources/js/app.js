import './bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

$(function () {
	const $responsiveNavs = $('[data-responsive-nav]');

	if ($responsiveNavs.length) {
		const closeMenu = function ($nav) {
			const $panel = $nav.find('[data-menu-panel]').first();
			const $toggle = $nav.find('[data-menu-toggle]').first();
			$panel.addClass('hidden');
			$toggle.attr('aria-expanded', 'false');
		};

		$responsiveNavs.each(function () {
			const $nav = $(this);
			const $toggle = $nav.find('[data-menu-toggle]').first();
			const $panel = $nav.find('[data-menu-panel]').first();

			if (!$toggle.length || !$panel.length) {
				return;
			}

			$toggle.on('click', function (event) {
				event.preventDefault();
				const isOpen = !$panel.hasClass('hidden');

				$responsiveNavs.each(function () {
					closeMenu($(this));
				});

				if (!isOpen) {
					$panel.removeClass('hidden');
					$toggle.attr('aria-expanded', 'true');
				}
			});

			$panel.find('a').on('click', function () {
				closeMenu($nav);
			});
		});

		$(document).on('click', function (event) {
			const $target = $(event.target);
			if ($target.closest('[data-responsive-nav]').length) {
				return;
			}

			$responsiveNavs.each(function () {
				closeMenu($(this));
			});
		});

		$(window).on('resize', function () {
			$responsiveNavs.each(function () {
				closeMenu($(this));
			});
		});
	}

	const $tabs = $('.intent-tab');
	const $homeIntentForms = $('[data-home-intent-form]');
	const $form = $('#property-search');
	const $city = $('#search-city');
	const $type = $('#search-type');
	const $bed = $('#search-bed');
	const $price = $('#search-price');
	const $cards = $('.property-card');
	const $counter = $('#results-count');
	const $noResults = $('#no-results');

	let activeIntent = 'buy';

	const renderIntent = function () {
		$tabs.each(function () {
			const $tab = $(this);
			const isActive = $tab.data('intent') === activeIntent;
			$tab.toggleClass('is-active bg-white text-[#1f2a44] ring-2 ring-[#1e73be] ring-offset-1 ring-offset-transparent', isActive);
			$tab.toggleClass('bg-[#d8e8fb] text-[#0f5faf]', !isActive);
		});
	};

	const setHomeIntentForm = function (intent) {
		if (!$homeIntentForms.length) {
			return;
		}

		const safeIntent = intent || 'buy';
		$homeIntentForms.addClass('hidden');
		$homeIntentForms.filter(`[data-home-intent-form="${safeIntent}"]`).removeClass('hidden');
	};

	const applyFilters = function () {
		const cityVal = $city.length ? $city.val() : 'all';
		const typeVal = $type.length ? $type.val() : 'all';
		const bedVal = $bed.length ? $bed.val() : 'all';
		const priceVal = $price.length ? $price.val() : 'all';

		let visible = 0;

		$cards.each(function () {
			const $card = $(this);
			const cardIntent = String($card.data('intent'));
			const cardCity = String($card.data('city'));
			const cardType = String($card.data('type'));
			const cardBed = Number($card.data('bed'));
			const cardPrice = Number($card.data('price'));

			const matchesIntent = cardIntent === activeIntent || activeIntent === 'all';
			const matchesCity = cityVal === 'all' || cardCity === cityVal;
			const matchesType = typeVal === 'all' || cardType === typeVal;
			const matchesBed = bedVal === 'all' || cardBed >= Number(bedVal);
			const matchesPrice = priceVal === 'all' || cardPrice <= Number(priceVal);

			const isVisible = matchesIntent && matchesCity && matchesType && matchesBed && matchesPrice;
			$card.toggle(isVisible);

			if (isVisible) {
				visible += 1;
			}
		});

		if ($counter.length) {
			$counter.text(`Showing ${visible} matching listings`);
		}

		if ($noResults.length) {
			$noResults.toggleClass('hidden', visible !== 0);
		}
	};

	$tabs.on('click', function () {
		activeIntent = $(this).data('intent') || 'buy';
		renderIntent();
		setHomeIntentForm(activeIntent);
		applyFilters();
	});

	if ($form.length) {
		$form.on('submit', function (event) {
			event.preventDefault();
			applyFilters();
		});
	}

	renderIntent();
	setHomeIntentForm(activeIntent);
	applyFilters();

	const $propertiesCards = $('[data-properties-cards]');
	const $propertiesViewButtons = $('[data-properties-view]');

	const setPropertiesView = function (view) {
		if (!$propertiesCards.length || !$propertiesViewButtons.length) {
			return;
		}

		const safeView = view === 'list' ? 'list' : 'grid';
		$propertiesCards.toggleClass('is-list-view', safeView === 'list');

		$propertiesViewButtons.each(function () {
			const $button = $(this);
			const isActive = $button.data('propertiesView') === safeView;
			$button.toggleClass('is-active', isActive);
			$button.attr('aria-pressed', isActive ? 'true' : 'false');
		});
	};

	if ($propertiesCards.length && $propertiesViewButtons.length) {
		$propertiesViewButtons.on('click', function () {
			setPropertiesView($(this).data('propertiesView'));
		});

		setPropertiesView('grid');
	}

	const $propertiesToolbar = $('[data-properties-toolbar]');
	const $propertiesDropdowns = $propertiesToolbar.find('.properties-filter-dropdown');

	if ($propertiesDropdowns.length) {
		$propertiesDropdowns.on('toggle', function () {
			if (!this.open) {
				return;
			}

			$propertiesDropdowns.not(this).prop('open', false);
		});

		$propertiesDropdowns.children('summary').on('click', function () {
			const $dropdown = $(this).parent();
			if (!$dropdown.prop('open')) {
				$propertiesDropdowns.not($dropdown).prop('open', false);
			}
		});

		$(document).on('click', function (event) {
			if (!$(event.target).closest('[data-properties-toolbar]').length) {
				$propertiesDropdowns.prop('open', false);
			}
		});
	}

	const $propertiesAccordionToggles = $('.properties-accordion-toggle');

	if ($propertiesAccordionToggles.length) {
		$propertiesAccordionToggles.on('click', function () {
			const $toggle = $(this);
			const $panel = $toggle.next('.properties-accordion-panel');
			const $icon = $toggle.find('.properties-accordion-icon');

			if (!$panel.length) {
				return;
			}

			const isOpen = !$panel.hasClass('hidden');

			$panel.toggleClass('hidden', isOpen);
			$toggle.attr('aria-expanded', isOpen ? 'false' : 'true');
			$icon.toggleClass('rotate-180', !isOpen);
		});
	}

	const $propertyInfoAccordionToggles = $('[data-property-accordion-toggle]');

	if ($propertyInfoAccordionToggles.length) {
		$propertyInfoAccordionToggles.on('click', function () {
			const $toggle = $(this);
			const panelId = $toggle.attr('aria-controls');
			const $panel = panelId ? $(`#${panelId}`) : $();
			const $icon = $toggle.find('.property-info-accordion-icon');
			const isOpen = !$panel.hasClass('hidden');

			$propertyInfoAccordionToggles.each(function () {
				const $otherToggle = $(this);
				const otherPanelId = $otherToggle.attr('aria-controls');
				const $otherPanel = otherPanelId ? $(`#${otherPanelId}`) : $();
				const $otherIcon = $otherToggle.find('.property-info-accordion-icon');

				$otherPanel.addClass('hidden');
				$otherToggle.attr('aria-expanded', 'false');
				$otherIcon.removeClass('rotate-180');
			});

			if (!isOpen) {
				$panel.removeClass('hidden');
				$toggle.attr('aria-expanded', 'true');
				$icon.addClass('rotate-180');
			}
		});
	}

	const $stickyHeader = $('[data-sticky-header]');
	const $stickyTrigger = $('[data-sticky-trigger]').first();

	if ($stickyHeader.length && $stickyTrigger.length) {
		const syncStickyHeader = function () {
			const triggerBottom = $stickyTrigger.offset().top + $stickyTrigger.outerHeight();
			const isVisible = $(window).scrollTop() > triggerBottom;
			$stickyHeader.toggleClass('is-visible', isVisible);
		};

		$(window).on('scroll resize', syncStickyHeader);
		syncStickyHeader();
	}

	const $mapIntentTabs = $('[data-map-intent-tab]');
	const $mapForms = $('[data-map-form]');
	const $aboutFaq = $('[data-about-faq]');

	if ($aboutFaq.length) {
		const $aboutFaqItems = $aboutFaq.find('[data-about-faq-item]');
		const $aboutFaqSummaries = $aboutFaqItems.children('summary');

		$aboutFaqSummaries.on('click', function (event) {
			event.preventDefault();
			const $item = $(this).parent('[data-about-faq-item]');
			const shouldOpen = !$item.prop('open');

			$aboutFaqItems.prop('open', false);
			$item.prop('open', shouldOpen);
		});
	}

	const $sidebarTabs = $('[data-sidebar-tab]');
	const $sidebarPanels = $('[data-sidebar-panel]');

	const setSidebarPanel = function (panel) {
		if (!$sidebarTabs.length || !$sidebarPanels.length) {
			return;
		}

		const safePanel = panel === 'tour' ? 'tour' : 'request';
		$sidebarPanels.addClass('hidden');
		$sidebarPanels.filter(`[data-sidebar-panel="${safePanel}"]`).removeClass('hidden');

		$sidebarTabs.each(function () {
			const $tab = $(this);
			const isActive = $tab.data('sidebarTab') === safePanel;
			$tab.toggleClass('border-b-2 border-[#1e73be] text-[#1f2a44]', isActive);
			$tab.toggleClass('text-slate-500', !isActive);
			$tab.attr('aria-pressed', isActive ? 'true' : 'false');
		});
	};

	if ($sidebarTabs.length && $sidebarPanels.length) {
		$sidebarTabs.on('click', function () {
			setSidebarPanel($(this).data('sidebarTab'));
		});

		setSidebarPanel($sidebarTabs.filter('[aria-pressed="true"]').data('sidebarTab') || 'request');
	}

	const setMapIntent = function (intent) {
		if (!$mapIntentTabs.length || !$mapForms.length) {
			return;
		}

		const safeIntent = intent || 'sales';
		$mapForms.addClass('hidden');
		$mapForms.filter(`[data-map-form="${safeIntent}"]`).removeClass('hidden');

		$mapIntentTabs.each(function () {
			const $tab = $(this);
			const isActive = $tab.data('mapIntentTab') === safeIntent;
			$tab.toggleClass('is-active bg-[#1e73be] text-white', isActive);
			$tab.toggleClass('bg-[#d8e8fb] text-[#1e73be]', !isActive);
		});
	};

	if ($mapIntentTabs.length && $mapForms.length) {
		$mapIntentTabs.on('click', function () {
			setMapIntent($(this).data('mapIntentTab'));
		});

		setMapIntent($mapIntentTabs.filter('.is-active').data('mapIntentTab') || 'sales');
	}

	const $mainPropertyImage = $('#property-main-image');
	const $propertyThumbs = $('.property-thumb');
	const $propertyPrev = $('#property-prev');
	const $propertyNext = $('#property-next');
	const $agentListingsSection = $('[data-agent-listings-section]');

	if ($agentListingsSection.length) {
		const $agentListingTabs = $agentListingsSection.find('[data-agent-listings-tab]');
		const $agentListingCards = $agentListingsSection.find('[data-agent-listing-card]');
		const $agentLoadMore = $agentListingsSection.find('[data-agent-load-more]');
		const batchSize = 3;
		let activeCategory = 'all';
		let visibleCount = batchSize;

		const syncAgentListings = function () {
			const $filteredCards = $agentListingCards.filter(function () {
				const category = String($(this).data('listingCategory'));
				return activeCategory === 'all' || category === activeCategory;
			});

			$agentListingCards.addClass('hidden');
			$filteredCards.each(function (index) {
				$(this).toggleClass('hidden', index >= visibleCount);
			});

			if ($agentLoadMore.length) {
				$agentLoadMore.toggleClass('hidden', $filteredCards.length <= visibleCount);
			}

			$agentListingTabs.each(function () {
				const $tab = $(this);
				const isActive = String($tab.data('agentListingsTab')) === activeCategory;
				$tab.toggleClass('bg-[#1e73be] text-white', isActive);
				$tab.toggleClass('bg-transparent text-[#1f2a44] hover:bg-[#e9eef4]', !isActive);
			});
		};

		$agentListingTabs.on('click', function () {
			activeCategory = String($(this).data('agentListingsTab') || 'all');
			visibleCount = batchSize;
			syncAgentListings();
		});

		$agentLoadMore.on('click', function () {
			visibleCount += batchSize;
			syncAgentListings();
		});

		syncAgentListings();
	}

	if ($mainPropertyImage.length && $propertyThumbs.length) {
		let activeIndex = $propertyThumbs.index($propertyThumbs.filter('.is-active'));
		if (activeIndex < 0) {
			activeIndex = 0;
		}

		const setActiveThumb = function (index) {
			const safeIndex = (index + $propertyThumbs.length) % $propertyThumbs.length;
			const $activeThumb = $propertyThumbs.eq(safeIndex);
			const newImage = $activeThumb.data('image');

			if (newImage) {
				$mainPropertyImage.attr('src', newImage);
			}

			$propertyThumbs.removeClass('is-active border-[#1e73be]').addClass('border-transparent');
			$activeThumb.addClass('is-active border-[#1e73be]').removeClass('border-transparent');
			activeIndex = safeIndex;
		};

		$propertyThumbs.on('click', function () {
			setActiveThumb($propertyThumbs.index(this));
		});

		$propertyPrev.on('click', function () {
			setActiveThumb(activeIndex - 1);
		});

		$propertyNext.on('click', function () {
			setActiveThumb(activeIndex + 1);
		});

		setActiveThumb(activeIndex);
	}

	$('.featured-slider').each(function () {
		const $slider = $(this);
		const $image = $slider.find('.featured-slide-image');
		const $prevBtn = $slider.find('.featured-slide-prev');
		const $nextBtn = $slider.find('.featured-slide-next');
		const images = String($slider.attr('data-images') || '')
			.split('|')
			.map(function (src) { return src.trim(); })
			.filter(Boolean);

		if (!$image.length || images.length < 2) {
			return;
		}

		let index = 0;

		const setSlide = function (newIndex) {
			index = (newIndex + images.length) % images.length;
			$image.addClass('opacity-60');
			window.setTimeout(function () {
				$image.attr('src', images[index]);
				$image.removeClass('opacity-60');
			}, 120);
		};

		$prevBtn.on('click', function () { setSlide(index - 1); });
		$nextBtn.on('click', function () { setSlide(index + 1); });

		window.setInterval(function () {
			setSlide(index + 1);
		}, 4500);
	});
});
