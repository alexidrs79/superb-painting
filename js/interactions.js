/**
 * Superb Painting — premium micro-interactions (carousel, lightbox, timeline, UX polish).
 */
(function () {
	'use strict';

	var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ------------------------------------------------------------------ */
	/* Shared lightbox                                                     */
	/* ------------------------------------------------------------------ */
	var lightboxEl = null;
	var lightboxItems = [];
	var lightboxIndex = 0;
	var touchStartX = 0;

	function buildLightbox() {
		if (lightboxEl) {
			var closeBtn = lightboxEl.querySelector('.superb-lightbox-close');
			var prevBtn = lightboxEl.querySelector('.superb-lightbox-prev');
			if (
				closeBtn && closeBtn.parentElement === lightboxEl &&
				prevBtn && prevBtn.parentElement === lightboxEl
			) {
				return lightboxEl;
			}
			lightboxEl.remove();
			lightboxEl = null;
		}

		lightboxEl = document.createElement('div');
		lightboxEl.className = 'superb-lightbox';
		lightboxEl.setAttribute('role', 'dialog');
		lightboxEl.setAttribute('aria-modal', 'true');
		lightboxEl.innerHTML =
			'<div class="superb-lightbox-backdrop"></div>' +
			'<button type="button" class="superb-lightbox-close" aria-label="Close">' +
				'<svg class="superb-lightbox-icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
					'<path fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>' +
				'</svg>' +
			'</button>' +
			'<button type="button" class="superb-lightbox-prev" aria-label="Previous">' +
				'<svg class="superb-lightbox-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
					'<path fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M13 7l-5 5 5 5"/>' +
				'</svg>' +
			'</button>' +
			'<button type="button" class="superb-lightbox-next" aria-label="Next">' +
				'<svg class="superb-lightbox-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
					'<path fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M11 7l5 5-5 5"/>' +
				'</svg>' +
			'</button>' +
			'<div class="superb-lightbox-content">' +
				'<figure class="superb-lightbox-figure">' +
					'<img src="" alt="" class="superb-lightbox-img">' +
				'</figure>' +
				'<div class="superb-lightbox-caption"></div>' +
			'</div>';
		document.body.appendChild(lightboxEl);

		lightboxEl.querySelector('.superb-lightbox-backdrop').addEventListener('click', closeLightbox);
		lightboxEl.querySelector('.superb-lightbox-close').addEventListener('click', closeLightbox);
		lightboxEl.querySelector('.superb-lightbox-prev').addEventListener('click', showPrev);
		lightboxEl.querySelector('.superb-lightbox-next').addEventListener('click', showNext);

		lightboxEl.addEventListener('touchstart', function (e) {
			touchStartX = e.changedTouches[0].screenX;
		}, { passive: true });

		lightboxEl.addEventListener('touchend', function (e) {
			var diff = e.changedTouches[0].screenX - touchStartX;
			if (Math.abs(diff) > 50) {
				if (diff > 0) {
					showPrev();
				} else {
					showNext();
				}
			}
		}, { passive: true });

		document.addEventListener('keydown', function (e) {
			if (!lightboxEl || !lightboxEl.classList.contains('is-open')) {
				return;
			}
			if (e.key === 'Escape') {
				closeLightbox();
			}
			if (e.key === 'ArrowLeft') {
				showPrev();
			}
			if (e.key === 'ArrowRight') {
				showNext();
			}
		});

		return lightboxEl;
	}

	function preloadAdjacent() {
		if (!lightboxItems.length) {
			return;
		}
		var next = lightboxItems[(lightboxIndex + 1) % lightboxItems.length];
		var prev = lightboxItems[(lightboxIndex - 1 + lightboxItems.length) % lightboxItems.length];
		[new Image(), new Image()].forEach(function (img, i) {
			var item = i === 0 ? next : prev;
			if (item && item.src) {
				img.src = item.src;
			}
		});
	}

	function updateLightbox() {
		if (!lightboxItems.length) {
			return;
		}
		if (lightboxIndex >= lightboxItems.length) {
			lightboxIndex = 0;
		}
		if (lightboxIndex < 0) {
			lightboxIndex = lightboxItems.length - 1;
		}

		var item = lightboxItems[lightboxIndex];
		var img = lightboxEl.querySelector('.superb-lightbox-img');
		var caption = lightboxEl.querySelector('.superb-lightbox-caption');

		img.src = item.src || '';
		img.alt = item.alt || '';
		caption.textContent = item.caption || item.alt || '';

		var showNav = lightboxItems.length > 1;
		lightboxEl.querySelector('.superb-lightbox-prev').style.display = showNav ? '' : 'none';
		lightboxEl.querySelector('.superb-lightbox-next').style.display = showNav ? '' : 'none';

		preloadAdjacent();
	}

	function openLightbox(items, index) {
		lightboxItems = items;
		lightboxIndex = index || 0;
		buildLightbox();
		updateLightbox();
		lightboxEl.classList.add('is-open');
		document.body.style.overflow = 'hidden';
		lightboxEl.querySelector('.superb-lightbox-close').focus();
	}

	function closeLightbox() {
		if (!lightboxEl) {
			return;
		}
		lightboxEl.classList.remove('is-open');
		document.body.style.overflow = '';
	}

	function showPrev() {
		lightboxIndex--;
		updateLightbox();
	}

	function showNext() {
		lightboxIndex++;
		updateLightbox();
	}

	window.SuperbLightbox = { open: openLightbox, close: closeLightbox };

	/* Gallery preview lightbox (homepage) */
	function initGalleryPreviewLightbox() {
		var items = document.querySelectorAll('.gallery-preview__item');
		if (!items.length) {
			return;
		}

		var data = Array.from(items).map(function (item) {
			var img = item.querySelector('img');
			return {
				src: img ? img.src : '',
				alt: img ? img.alt : '',
				caption: img ? img.alt : '',
			};
		});

		items.forEach(function (item, i) {
			item.setAttribute('role', 'button');
			item.setAttribute('tabindex', '0');
			item.style.cursor = 'pointer';

			var open = function () {
				openLightbox(data, i);
			};

			item.addEventListener('click', open);
			item.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					open();
				}
			});
		});
	}

	/* Suburb page gallery lightbox */
	function initSuburbGalleryLightbox() {
		document.querySelectorAll('.suburb-gallery-grid').forEach(function (grid) {
			var imgs = grid.querySelectorAll('img');
			if (!imgs.length) {
				return;
			}

			var data = Array.from(imgs).map(function (img) {
				return {
					src: img.getAttribute('data-full') || img.currentSrc || img.src,
					alt: img.alt || '',
					caption: img.alt || '',
				};
			});

			imgs.forEach(function (img, i) {
				img.setAttribute('role', 'button');
				img.setAttribute('tabindex', '0');

				var open = function (e) {
					if (e) {
						e.preventDefault();
					}
					openLightbox(data, i);
				};

				img.addEventListener('click', open);
				img.addEventListener('keydown', function (e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						open();
					}
				});
			});
		});
	}

	/* ------------------------------------------------------------------ */
	/* Testimonials carousel                                               */
	/* ------------------------------------------------------------------ */
	function initTestimonialsCarousel() {
		var carousel = document.querySelector('.testimonials-carousel');
		if (!carousel) {
			return;
		}

		var track = carousel.querySelector('.testimonials-carousel__track');
		var slides = track ? track.querySelectorAll('.testimonial-card') : [];
		var prevBtn = carousel.querySelector('.testimonials-carousel__prev');
		var nextBtn = carousel.querySelector('.testimonials-carousel__next');
		var dotsWrap = carousel.querySelector('.testimonials-carousel__dots');
		var autoplayMs = parseInt(carousel.getAttribute('data-autoplay') || '8000', 10);
		var current = 0;
		var timer = null;
		var isCarouselMode = false;

		if (!track || slides.length < 2) {
			return;
		}

		slides.forEach(function (_, i) {
			var dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'testimonials-carousel__dot';
			dot.setAttribute('aria-label', 'Go to testimonial ' + (i + 1));
			dot.addEventListener('click', function () {
				goTo(i);
			});
			dotsWrap.appendChild(dot);
		});

		var dots = dotsWrap.querySelectorAll('.testimonials-carousel__dot');

		function checkMode() {
			isCarouselMode = window.matchMedia('(max-width: 992px)').matches;
			carousel.classList.toggle('is-carousel-mode', isCarouselMode);
			if (isCarouselMode) {
				goTo(current, true);
				startAutoplay();
			} else {
				stopAutoplay();
				track.style.transform = '';
			}
			updateDots();
		}

		function updateDots() {
			dots.forEach(function (dot, i) {
				dot.classList.toggle('is-active', i === current);
				dot.setAttribute('aria-current', i === current ? 'true' : 'false');
			});
		}

		function goTo(index, instant) {
			current = (index + slides.length) % slides.length;
			if (isCarouselMode) {
				var offset = current * 100;
				track.style.transition = instant || prefersReduced ? 'none' : 'transform 0.45s ease';
				track.style.transform = 'translateX(-' + offset + '%)';
			}
			updateDots();
		}

		function startAutoplay() {
			stopAutoplay();
			if (prefersReduced || !isCarouselMode) {
				return;
			}
			timer = setInterval(function () {
				goTo(current + 1);
			}, autoplayMs);
		}

		function stopAutoplay() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				goTo(current - 1);
				startAutoplay();
			});
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				goTo(current + 1);
				startAutoplay();
			});
		}

		carousel.addEventListener('mouseenter', stopAutoplay);
		carousel.addEventListener('mouseleave', startAutoplay);
		carousel.addEventListener('focusin', stopAutoplay);
		carousel.addEventListener('focusout', startAutoplay);

		/* Touch swipe */
		var startX = 0;
		carousel.addEventListener('touchstart', function (e) {
			startX = e.changedTouches[0].screenX;
		}, { passive: true });
		carousel.addEventListener('touchend', function (e) {
			if (!isCarouselMode) {
				return;
			}
			var diff = e.changedTouches[0].screenX - startX;
			if (Math.abs(diff) > 40) {
				goTo(diff > 0 ? current - 1 : current + 1);
				startAutoplay();
			}
		}, { passive: true });

		carousel.addEventListener('keydown', function (e) {
			if (!isCarouselMode) {
				return;
			}
			if (e.key === 'ArrowLeft') {
				e.preventDefault();
				goTo(current - 1);
			}
			if (e.key === 'ArrowRight') {
				e.preventDefault();
				goTo(current + 1);
			}
		});

		window.addEventListener('resize', checkMode);
		checkMode();
	}

	/* ------------------------------------------------------------------ */
	/* Process timeline                                                    */
	/* ------------------------------------------------------------------ */
	function initProcessTimeline() {
		var stepsWrap = document.querySelector('.our-process__steps');
		if (!stepsWrap || !('IntersectionObserver' in window)) {
			return;
		}

		var steps = stepsWrap.querySelectorAll('.process-step');
		var progress = stepsWrap.querySelector('.our-process__progress-fill');

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-active');
					}
				});
				updateProgress(steps, progress);
			},
			{ threshold: 0.5, rootMargin: '0px 0px -10% 0px' }
		);

		steps.forEach(function (step) {
			observer.observe(step);
		});

		function updateProgress(stepsList, bar) {
			if (!bar) {
				return;
			}
			var active = 0;
			stepsList.forEach(function (step, i) {
				if (step.classList.contains('is-active')) {
					active = i + 1;
				}
			});
			var pct = stepsList.length > 1 ? ((active - 1) / (stepsList.length - 1)) * 100 : 0;
			bar.style.width = Math.max(0, pct) + '%';
		}
	}

	/* ------------------------------------------------------------------ */
	/* Hero badges pulse                                                   */
	/* ------------------------------------------------------------------ */
	function initHeroBadges() {
		var badges = document.querySelectorAll('.hero-split__badge');
		if (
			!badges.length ||
			prefersReduced ||
			!('IntersectionObserver' in window) ||
			window.matchMedia('(max-width: 992px)').matches
		) {
			return;
		}

		var observer = new IntersectionObserver(
			function (entries, obs) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('hero-badge-pulsed');
						obs.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.8 }
		);

		badges.forEach(function (badge) {
			observer.observe(badge);
		});
	}

	/* ------------------------------------------------------------------ */
	/* Homepage stats counters                                             */
	/* ------------------------------------------------------------------ */
	function initStatsCounters() {
		var counters = document.querySelectorAll('.stats-row__number[data-count]');
		if (!counters.length) {
			return;
		}

		function finishCounter(el, value, suffix) {
			el.textContent = value + suffix;
			el.classList.add('stats-row__number--done');
		}

		function animateCounter(el) {
			if (el.classList.contains('stats-row__number--done')) {
				return;
			}

			var target = parseInt(el.getAttribute('data-count'), 10);
			var suffix = el.getAttribute('data-suffix') || '';
			if (isNaN(target)) {
				return;
			}

			if (prefersReduced) {
				finishCounter(el, target, suffix);
				return;
			}

			var duration = 1600;
			var startTime = null;

			function step(timestamp) {
				if (!startTime) {
					startTime = timestamp;
				}
				var progress = Math.min((timestamp - startTime) / duration, 1);
				var eased = 1 - Math.pow(1 - progress, 3);
				var current = Math.round(eased * target);
				el.textContent = current + suffix;
				if (progress < 1) {
					window.requestAnimationFrame(step);
				} else {
					finishCounter(el, target, suffix);
				}
			}

			window.requestAnimationFrame(step);
		}

		if (!('IntersectionObserver' in window)) {
			counters.forEach(animateCounter);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries, obs) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						animateCounter(entry.target);
						obs.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.35, rootMargin: '0px 0px -8% 0px' }
		);

		counters.forEach(function (el) {
			observer.observe(el);
		});
	}

	/* ------------------------------------------------------------------ */
	/* CF7 — submit state, validation display, inline success/errors       */
	/* ------------------------------------------------------------------ */
	function superbGetCf7Form(node) {
		if (!node) {
			return null;
		}
		if (node.classList && node.classList.contains('wpcf7-form')) {
			return node;
		}
		if (node.querySelector) {
			return node.querySelector('form.wpcf7-form');
		}
		return null;
	}

	function superbIsSuperbCf7Form(form) {
		return !!(form && form.closest('.superb-quote-form, .superb-painting'));
	}

	function superbScrollToCf7Message(form) {
		if (!form) {
			return;
		}
		var target = form.querySelector('.superb-form-thanks, .wpcf7-response-output');
		if (target) {
			target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		}
	}

	function superbRevealCf7Response(form) {
		if (!form) {
			return;
		}
		form.querySelectorAll('.wpcf7-response-output').forEach(function (output) {
			output.removeAttribute('aria-hidden');
			output.setAttribute('role', 'alert');
		});
	}

	function superbSetCf7Submitting(form, isSubmitting) {
		if (!form || !form.classList) {
			return;
		}
		form.classList.toggle('superb-cf7-submitting', !!isSubmitting);
		var submit = form.querySelector('input[type="submit"], button[type="submit"]');
		if (!submit) {
			return;
		}
		submit.disabled = !!isSubmitting;
		submit.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
		if (isSubmitting) {
			if (!submit.dataset.superbOriginalLabel) {
				submit.dataset.superbOriginalLabel = submit.value || submit.textContent || '';
			}
			if (submit.tagName === 'INPUT') {
				submit.value = 'Sending…';
			} else {
				submit.textContent = 'Sending…';
			}
			return;
		}
		if (submit.dataset.superbOriginalLabel) {
			if (submit.tagName === 'INPUT') {
				submit.value = submit.dataset.superbOriginalLabel;
			} else {
				submit.textContent = submit.dataset.superbOriginalLabel;
			}
		}
	}

	function superbPatchCf7Validate() {
		if (typeof wpcf7 === 'undefined' || typeof wpcf7.validate !== 'function' || wpcf7.superbValidatePatched) {
			return;
		}
		var originalValidate = wpcf7.validate;
		wpcf7.validate = function (form, options) {
			options = options || {};
			if (options.target && form && !form.classList.contains('superb-cf7-show-errors')) {
				return;
			}
			return originalValidate.call(this, form, options);
		};
		wpcf7.superbValidatePatched = true;
	}

	function superbMarkCf7ShowErrors(form) {
		if (form && form.classList) {
			form.classList.add('superb-cf7-show-errors');
		}
	}

	function superbResetCf7ShowErrors(form) {
		if (form && form.classList) {
			form.classList.remove('superb-cf7-show-errors');
		}
	}

	function superbWatchCf7SubmitTimeout(form) {
		if (!form || !form.dataset) {
			return;
		}
		if (form.dataset.superbSubmitTimer) {
			clearTimeout(Number(form.dataset.superbSubmitTimer));
		}
		form.dataset.superbSubmitTimer = String(window.setTimeout(function () {
			if (!form || form.dataset.status !== 'submitting') {
				return;
			}
			superbMarkCf7ShowErrors(form);
			superbSetCf7Submitting(form, false);
			form.classList.remove('sent');
			form.classList.add('failed');
			form.dataset.status = 'failed';
			var output = form.querySelector('.wpcf7-response-output');
			if (output) {
				output.textContent = 'We could not send your quote request (mail server timeout). Please call 0400 164 985 or try again shortly.';
				output.removeAttribute('aria-hidden');
				output.style.display = 'block';
			}
			superbScrollToCf7Message(form);
		}, 30000));
	}

	function superbClearCf7SubmitTimeout(form) {
		if (form && form.dataset && form.dataset.superbSubmitTimer) {
			clearTimeout(Number(form.dataset.superbSubmitTimer));
			delete form.dataset.superbSubmitTimer;
		}
	}

	function superbFinishCf7Error(form) {
		if (!form) {
			return;
		}
		superbClearCf7SubmitTimeout(form);
		superbMarkCf7ShowErrors(form);
		superbSetCf7Submitting(form, false);
		superbRevealCf7Response(form);
		superbScrollToCf7Message(form);
	}

	function superbShowCf7Success(form) {
		if (!form) {
			return;
		}
		var wrap = form.closest('.superb-quote-form');
		if (!wrap || wrap.querySelector('.superb-form-thanks')) {
			superbScrollToCf7Message(form);
			return;
		}
		var output = form.querySelector('.wpcf7-response-output');
		if (output) {
			output.setAttribute('aria-hidden', 'true');
			output.style.display = 'none';
			output.classList.add('superb-cf7-response-hidden');
		}
		var thanks = document.createElement('div');
		thanks.className = 'superb-form-thanks';
		thanks.setAttribute('role', 'status');
		thanks.innerHTML = '<strong>Thank you!</strong> We\'ll be in touch within 24 hours with your free quote.';
		form.insertBefore(thanks, form.firstChild);
		form.classList.add('superb-cf7-success-shown');
		form.querySelectorAll('.superb-cf7-grid, .superb-cf7-field, .superb-cf7-row, .superb-cf7-submit, .superb-cf7-file').forEach(function (el) {
			el.style.display = 'none';
		});
		var submit = form.querySelector('.wpcf7-submit');
		if (submit && submit.closest('p')) {
			submit.closest('p').style.display = 'none';
		}
		superbScrollToCf7Message(form);
	}

	function superbFormatFileSize(bytes) {
		if (bytes < 1024) {
			return bytes + ' B';
		}
		if (bytes < 1048576) {
			return (bytes / 1024).toFixed(1) + ' KB';
		}
		return (bytes / 1048576).toFixed(1) + ' MB';
	}

	function superbBindFileUploads(form) {
		if (!form || form.dataset.superbFileBound) {
			return;
		}
		var input = form.querySelector('input[type="file"][name="your-photos"]');
		if (!input) {
			return;
		}
		form.dataset.superbFileBound = '1';
		var drop = input.closest('.superb-cf7-file-drop');
		if (!drop) {
			return;
		}
		var preview = drop.querySelector('.superb-file-preview');
		if (!preview) {
			preview = document.createElement('div');
			preview.className = 'superb-file-preview';
			preview.setAttribute('aria-live', 'polite');
			drop.appendChild(preview);
		}
		var hint = drop.querySelector('.superb-cf7-file-hint');
		var render = function () {
			preview.innerHTML = '';
			if (!input.files || !input.files.length) {
				drop.classList.remove('has-file');
				if (hint) {
					hint.style.display = '';
				}
				return;
			}
			drop.classList.add('has-file');
			if (hint) {
				hint.style.display = 'none';
			}
			Array.prototype.forEach.call(input.files, function (file) {
				var item = document.createElement('div');
				item.className = 'superb-file-item';
				item.textContent = '\u2713 ' + file.name + ' (' + superbFormatFileSize(file.size) + ') — ready to send';
				preview.appendChild(item);
			});
		};
		input.addEventListener('change', render);
		form.addEventListener('wpcf7reset', render);
	}

	function superbBindCf7FieldHelpers(form) {
		if (!form || form.dataset.superbFieldsBound) {
			return;
		}
		form.dataset.superbFieldsBound = '1';
		superbBindFileUploads(form);
		var fields = {
			'your-name': function (v) {
				return v.trim().length >= 2;
			},
			'your-phone': function (v) {
				return /^[\d\s+()-]{8,}$/.test(v.trim());
			},
			'your-email': function (v) {
				return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim());
			},
		};
		Object.keys(fields).forEach(function (name) {
			var input = form.querySelector('[name="' + name + '"]');
			if (!input) {
				return;
			}
			var validate = function () {
				if (!form.classList.contains('superb-cf7-show-errors')) {
					return;
				}
				var ok = fields[name](input.value);
				input.classList.toggle('superb-field-invalid', !ok && input.value.trim() !== '');
				input.classList.toggle('superb-field-valid', ok && input.value.trim() !== '');
				input.setAttribute('aria-invalid', !ok && input.value.trim() !== '' ? 'true' : 'false');
			};
			input.addEventListener('blur', validate);
			input.addEventListener('input', function () {
				if (input.classList.contains('superb-field-invalid')) {
					validate();
				}
			});
		});
	}

	function initFormValidation() {
		superbPatchCf7Validate();

		document.querySelectorAll('form.wpcf7-form').forEach(superbBindCf7FieldHelpers);

		document.addEventListener('beforesubmit', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbMarkCf7ShowErrors(form);
			superbSetCf7Submitting(form, true);
			superbWatchCf7SubmitTimeout(form);
		});

		document.addEventListener('wpcf7statuschanged', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form) || !e.detail) {
				return;
			}
			if (e.detail.status === 'submitting') {
				superbMarkCf7ShowErrors(form);
				superbSetCf7Submitting(form, true);
				superbWatchCf7SubmitTimeout(form);
			}
		});

		document.addEventListener('wpcf7invalid', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbFinishCf7Error(form);
		});
		document.addEventListener('wpcf7unaccepted', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbFinishCf7Error(form);
		});
		document.addEventListener('wpcf7spam', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbFinishCf7Error(form);
		});
		document.addEventListener('wpcf7mailfailed', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbFinishCf7Error(form);
		});
		document.addEventListener('wpcf7mailsent', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbClearCf7SubmitTimeout(form);
			superbSetCf7Submitting(form, false);
			superbShowCf7Success(form);
		});
		document.addEventListener('wpcf7reset', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form)) {
				return;
			}
			superbClearCf7SubmitTimeout(form);
			superbResetCf7ShowErrors(form);
			superbSetCf7Submitting(form, false);
		});

		/* wpcf7submit always fires last — never start "Sending…" here (that caused stuck buttons). */
		document.addEventListener('wpcf7submit', function (e) {
			var form = superbGetCf7Form(e.target);
			if (!superbIsSuperbCf7Form(form) || !e.detail) {
				return;
			}
			var status = e.detail.status || form.getAttribute('data-status') || '';
			superbClearCf7SubmitTimeout(form);
			superbSetCf7Submitting(form, false);
			if (status === 'mail_sent') {
				superbShowCf7Success(form);
				return;
			}
			if (status === 'mail_failed' || status === 'validation_failed' || status === 'spam' || status === 'acceptance_missing') {
				superbFinishCf7Error(form);
			}
		});

		document.addEventListener('wpcf7init', function (e) {
			var form = superbGetCf7Form(e.target);
			superbBindCf7FieldHelpers(form);
			superbBindFileUploads(form);
		});
	}

	/* ------------------------------------------------------------------ */
	/* Mobile CTA smart hide                                               */
	/* ------------------------------------------------------------------ */
	function initMobileCta() {
		var bar = document.querySelector('.superb-mobile-cta');
		if (!bar) {
			return;
		}

		var isContact = document.body.classList.contains('page-contact') ||
			document.body.classList.contains('page-contact-us') ||
			window.location.pathname.indexOf('/contact') !== -1;

		if (isContact) {
			bar.classList.add('is-hidden');
			return;
		}

		var quoteForm = document.getElementById('quote-form');
		var hero = document.querySelector('.hero-split');
		var hasShown = false;

		if (quoteForm && 'IntersectionObserver' in window) {
			var formObserver = new IntersectionObserver(
				function (entries) {
					entries.forEach(function (entry) {
						bar.classList.toggle('is-hidden', entry.isIntersecting);
					});
				},
				{ threshold: 0.2 }
			);
			formObserver.observe(quoteForm);
		}

		if (hero) {
			var heroObserver = new IntersectionObserver(
				function (entries) {
					entries.forEach(function (entry) {
						if (!entry.isIntersecting && !hasShown) {
							hasShown = true;
							bar.classList.add('is-visible');
						}
					});
				},
				{ threshold: 0 }
			);
			heroObserver.observe(hero);
		} else {
			bar.classList.add('is-visible');
		}
	}

	/* ------------------------------------------------------------------ */
	/* Scroll progress + back to top                                       */
	/* ------------------------------------------------------------------ */
	function initScrollUi() {
		var progress = document.querySelector('.superb-scroll-progress');
		var backTop = document.querySelector('.superb-back-to-top');

		var onScroll = function () {
			var scrollTop = window.scrollY;
			var docHeight = document.documentElement.scrollHeight - window.innerHeight;

			if (progress && docHeight > 0) {
				progress.style.width = Math.min(100, (scrollTop / docHeight) * 100) + '%';
			}

			if (backTop) {
				backTop.classList.toggle('is-visible', scrollTop > 600);
			}
		};

		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();

		if (backTop) {
			backTop.addEventListener('click', function () {
				window.scrollTo({ top: 0, behavior: prefersReduced ? 'auto' : 'smooth' });
			});
		}
	}

	/* ------------------------------------------------------------------ */
	/* Suburb card media wrap (suburbs page shortcode cards)               */
	/* ------------------------------------------------------------------ */
	function initSuburbCardMedia() {
		document.querySelectorAll('.suburb-card a').forEach(function (link) {
			if (link.querySelector('.suburb-card__media')) {
				return;
			}
			var img = link.querySelector('.suburb-card__img');
			if (!img) {
				return;
			}
			var media = document.createElement('div');
			media.className = 'suburb-card__media';
			img.parentNode.insertBefore(media, img);
			media.appendChild(img);
			var overlay = document.createElement('span');
			overlay.className = 'suburb-card__overlay';
			overlay.textContent = 'View suburb';
			media.appendChild(overlay);
		});
	}

	/* ------------------------------------------------------------------ */
	/* GTranslate — sync switchers + overlay dropdown (no layout shift)   */
	/* ------------------------------------------------------------------ */
	function initGtranslateSync() {
		var root = '.superb-gtranslate';
		var defaultLang = 'en';

		function getLangFromCookie() {
			var match = document.cookie.match(/(?:^|;\s*)googtrans=([^;]*)/);
			if (!match) {
				return '';
			}
			var parts = decodeURIComponent(match[1]).split('/');
			return parts[2] || '';
		}

		function updateHeaderNavLayout() {
			var activeLang = getLangFromCookie() || defaultLang;
			document.documentElement.classList.toggle(
				'superb-header-nav-expanded',
				activeLang !== defaultLang
			);
		}

		function setGtranslateOpen(container, isOpen) {
			if (!container) {
				return;
			}
			container.classList.toggle('is-open', isOpen);
		}

		function closeGtranslateMenus(except) {
			document.querySelectorAll(root + '.is-open').forEach(function (container) {
				if (except && container === except) {
					return;
				}
				setGtranslateOpen(container, false);
			});
		}

		function syncSelectedHtml(html) {
			if (!html) {
				return;
			}
			document.querySelectorAll(root + ' .gt_selected a').forEach(function (anchor) {
				anchor.innerHTML = html;
			});
		}

		function syncFromOption(link) {
			if (!link) {
				return;
			}
			syncSelectedHtml(link.innerHTML);
		}

		function syncFromCookie() {
			updateHeaderNavLayout();
			var lang = getLangFromCookie();
			if (!lang) {
				return;
			}
			var source = document.querySelector(root + ' a[data-gt-lang="' + lang + '"]');
			if (source) {
				syncFromOption(source);
			}
		}

		document.addEventListener('click', function (e) {
			var container = e.target.closest('.superb-gtranslate--header, .superb-gtranslate--footer, .superb-gtranslate--mobile');
			if (container && e.target.closest('.gt_selected, .gt_switcher')) {
				if (e.target.closest('.gt_selected')) {
					closeGtranslateMenus(container);
					setGtranslateOpen(container, !container.classList.contains('is-open'));
				}
				return;
			}

			var optionLink = e.target.closest(root + ' .gt_option a');
			if (optionLink) {
				setTimeout(function () {
					syncFromOption(optionLink);
					updateHeaderNavLayout();
					closeGtranslateMenus();
				}, 50);
				return;
			}

			closeGtranslateMenus();
		});

		function patchDoGTranslate() {
			if (typeof window.doGTranslate !== 'function' || window.superbGtranslateSyncPatched) {
				return !!window.superbGtranslateSyncPatched;
			}
			var original = window.doGTranslate;
			window.doGTranslate = function () {
				var result = original.apply(this, arguments);
				setTimeout(function () {
					syncFromCookie();
					updateHeaderNavLayout();
					closeGtranslateMenus();
				}, 100);
				setTimeout(function () {
					syncFromCookie();
					updateHeaderNavLayout();
				}, 500);
				return result;
			};
			window.superbGtranslateSyncPatched = true;
			return true;
		}

		var attempts = 0;
		var timer = setInterval(function () {
			if (patchDoGTranslate() || ++attempts > 40) {
				clearInterval(timer);
			}
		}, 250);

		updateHeaderNavLayout();
		setTimeout(syncFromCookie, 400);
		setTimeout(syncFromCookie, 1200);
	}

	/* ------------------------------------------------------------------ */
	/* Mobile drawer — GTranslate dropdown positioning                     */
	/* ------------------------------------------------------------------ */
	function initMobileGtranslate() {
		var drawer = document.getElementById('mobile-drawer');
		if (!drawer) {
			return;
		}

		function setOpen(isOpen) {
			drawer.classList.toggle('superb-gtranslate-open', isOpen);
		}

		drawer.addEventListener('click', function (e) {
			if (e.target.closest('.superb-gtranslate--mobile .gt_selected, .superb-gtranslate--mobile .gt_switcher')) {
				setOpen(true);
			}
		});

		document.addEventListener('click', function (e) {
			if (!e.target.closest('.superb-gtranslate--mobile')) {
				setOpen(false);
			}
		});
	}

	/* ------------------------------------------------------------------ */
	/* Init                                                                */
	/* ------------------------------------------------------------------ */
	document.addEventListener('DOMContentLoaded', function () {
		initSuburbCardMedia();
		initGalleryPreviewLightbox();
		initSuburbGalleryLightbox();
		initTestimonialsCarousel();
		initProcessTimeline();
		initHeroBadges();
		initStatsCounters();
		initFormValidation();
		initGtranslateSync();
		initMobileGtranslate();
		initMobileCta();
		initScrollUi();
	});
}());
