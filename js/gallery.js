/**
 * Superb Painting — gallery filter & lightbox.
 */
(function () {
	'use strict';

	var galleryPage = document.querySelector('.gallery-page');
	if (!galleryPage) {
		return;
	}

	var items = galleryPage.querySelectorAll('.gallery-item');
	var tabs = galleryPage.querySelectorAll('.gallery-filter-tab');
	var touchStartX = 0;

	/* Filter tabs with counts */
	var counts = { all: items.length };
	items.forEach(function (item) {
		var cat = item.getAttribute('data-category') || '';
		counts[cat] = (counts[cat] || 0) + 1;
	});

	tabs.forEach(function (tab) {
		var filter = tab.getAttribute('data-filter');
		var count = counts[filter] || 0;
		if (count && filter !== 'all') {
			var badge = document.createElement('span');
			badge.className = 'gallery-filter-tab__count';
			badge.textContent = count;
			tab.appendChild(badge);
		}

		tab.addEventListener('click', function () {
			var activeFilter = tab.getAttribute('data-filter');
			tabs.forEach(function (t) {
				t.classList.remove('active');
			});
			tab.classList.add('active');

			items.forEach(function (item, i) {
				var cat = item.getAttribute('data-category') || '';
				var show = activeFilter === 'all' || cat === activeFilter;
				item.classList.toggle('hidden', !show);
				if (show) {
					item.classList.remove('gallery-item--enter');
					void item.offsetWidth;
					item.style.setProperty('--gallery-stagger', (i % 6) * 60 + 'ms');
					item.classList.add('gallery-item--enter');
				}
			});
		});
	});

	/* Initial stagger */
	items.forEach(function (item, i) {
		item.style.setProperty('--gallery-stagger', (i % 6) * 60 + 'ms');
		item.classList.add('gallery-item--enter');
	});

	/* Lightbox via shared module */
	function getVisibleItems() {
		return Array.from(items).filter(function (item) {
			return !item.classList.contains('hidden');
		});
	}

	function toLightboxData(visible) {
		return visible.map(function (item) {
			var img = item.querySelector('img');
			var caption = item.querySelector('.gallery-item-caption');
			return {
				src: img ? img.src : '',
				alt: img ? img.alt : '',
				caption: caption ? caption.textContent : (img ? img.alt : ''),
			};
		});
	}

	items.forEach(function (item) {
		item.setAttribute('role', 'button');
		item.setAttribute('tabindex', '0');

		var open = function () {
			var visible = getVisibleItems();
			var idx = visible.indexOf(item);
			if (window.SuperbLightbox) {
				window.SuperbLightbox.open(toLightboxData(visible), idx >= 0 ? idx : 0);
			}
		};

		item.addEventListener('click', open);
		item.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				open();
			}
		});
	});
})();
