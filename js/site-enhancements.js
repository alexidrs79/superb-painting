/**
 * Superb Painting — FAQ accordion, suburb search, lead tracking.
 */
(function () {
	'use strict';

	/* Lead source hidden fields (CF7) */
	function populateLeadFields(form) {
		if (!form || !form.classList.contains('wpcf7-form')) {
			return;
		}

		var wrapper = form.closest('.superb-quote-form');
		var source = form.querySelector('input[name="lead-source"]');
		var page = form.querySelector('input[name="lead-page"]');
		var formSource = form.querySelector('input[name="form-source"]');

		if (formSource) {
			var label = wrapper && wrapper.getAttribute('data-form-source');
			if (label) {
				formSource.value = label;
			}
		}
		if (source) {
			source.value = document.referrer || 'direct';
		}
		if (page) {
			page.value = window.location.href;
		}
	}

	function initLeadTracking() {
		document.querySelectorAll('form.wpcf7-form').forEach(populateLeadFields);
	}

	document.addEventListener('DOMContentLoaded', initLeadTracking);
	document.addEventListener('wpcf7mailsent', initLeadTracking);
	document.addEventListener('submit', function (e) {
		if (e.target && e.target.classList && e.target.classList.contains('wpcf7-form')) {
			populateLeadFields(e.target);
		}
	}, true);

	/* FAQ accordion — single open, first expanded on FAQ page */
	var faqItems = document.querySelectorAll('.faq-item');
	var isFaqPage = document.body.classList.contains('page-faq') ||
		window.location.pathname.replace(/\/$/, '') === '/faq';

	faqItems.forEach(function (item, index) {
		var heading = item.querySelector('h4');
		var body = item.querySelector('p');
		if (!heading || !body) {
			return;
		}

		var btnId = 'faq-btn-' + index;
		var panelId = 'faq-panel-' + index;

		heading.setAttribute('role', 'button');
		heading.setAttribute('tabindex', '0');
		heading.setAttribute('id', btnId);
		heading.setAttribute('aria-expanded', 'false');
		heading.setAttribute('aria-controls', panelId);

		body.setAttribute('id', panelId);
		body.setAttribute('role', 'region');
		body.setAttribute('aria-labelledby', btnId);

		var toggle = function (forceOpen) {
			var willOpen = typeof forceOpen === 'boolean' ? forceOpen : !item.classList.contains('is-open');

			if (willOpen) {
				faqItems.forEach(function (other) {
					if (other !== item && other.classList.contains('is-open')) {
						other.classList.remove('is-open');
						var otherHeading = other.querySelector('h4');
						if (otherHeading) {
							otherHeading.setAttribute('aria-expanded', 'false');
						}
					}
				});
			}

			item.classList.toggle('is-open', willOpen);
			heading.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
		};

		heading.addEventListener('click', function () {
			toggle();
		});
		heading.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				toggle();
			}
		});

		if (isFaqPage && index === 0) {
			toggle(true);
		}
	});

	/* Suburbs search filter */
	var searchInput = document.getElementById('suburbs-search');
	var suburbsGrid = document.querySelector('.suburbs-filter-grid');
	var searchMeta = document.getElementById('suburbs-search-meta');
	var searchWrap = document.querySelector('.suburbs-search__field');
	var clearBtn = document.getElementById('suburbs-search-clear');
	var emptyState = document.getElementById('suburbs-search-empty');

	if (searchInput && suburbsGrid) {
		var cards = suburbsGrid.querySelectorAll('.suburb-card');

		var updateMeta = function (visible, total) {
			if (!searchMeta) {
				return;
			}
			if (!searchInput.value.trim()) {
				searchMeta.textContent = total + ' Melbourne suburbs';
				return;
			}
			searchMeta.textContent = visible + ' of ' + total + ' suburbs match your search';
		};

		var filter = function () {
			var q = searchInput.value.trim().toLowerCase();
			var visible = 0;

			cards.forEach(function (card) {
				var text = card.textContent.toLowerCase();
				var show = !q || text.indexOf(q) !== -1;
				card.classList.toggle('is-hidden', !show);
				if (show) {
					visible++;
				}
			});

			updateMeta(visible, cards.length);

			if (emptyState) {
				emptyState.hidden = visible > 0 || !q;
			}
			if (clearBtn) {
				clearBtn.hidden = !q;
			}
			if (searchWrap) {
				searchWrap.classList.toggle('has-value', !!q);
			}
		};

		searchInput.addEventListener('input', filter);
		updateMeta(cards.length, cards.length);

		if (clearBtn) {
			clearBtn.addEventListener('click', function () {
				searchInput.value = '';
				searchInput.focus();
				filter();
			});
		}
	}
}());
