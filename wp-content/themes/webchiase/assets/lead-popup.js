(function ($) {
	'use strict';
	var KEY = 'lnt_lead_seen';

	function seen() { try { return sessionStorage.getItem(KEY); } catch (e) { return null; } }
	function mark() { try { sessionStorage.setItem(KEY, '1'); } catch (e) {} }

	function openPopup() {
		// Flatsome lazy-load Magnific Popup; loadMagnificPopup() trả về Promise.
		var ready = $.loadMagnificPopup ? $.loadMagnificPopup() : Promise.resolve();
		ready.then(function () {
			var lb = (window.flatsomeVars && flatsomeVars.lightbox) || {};
			$.magnificPopup.open({
				items: { src: '#lnt-lead-popup', type: 'inline' },
				removalDelay: 300,
				closeBtnInside: lb.close_btn_inside,
				closeMarkup: lb.close_markup,
				callbacks: { close: mark }
			});
		});
	}

	// Nút ở footer (data-lnt-open) mở popup bất cứ lúc nào.
	$(document).on('click', '[data-lnt-open]', function (e) { e.preventDefault(); openPopup(); });

	$(function () {
		if (!seen()) {
			setTimeout(openPopup, parseInt(LNT_LEAD.delay, 10) || 0);
		}
	});

	// Gửi CF7 thành công: ghi nhớ và tự đóng popup sau 2.5s.
	document.addEventListener('wpcf7mailsent', function (e) {
		if (!$(e.target).closest('#lnt-lead-popup').length) return;
		mark();
		setTimeout(function () { if ($.magnificPopup) $.magnificPopup.close(); }, 2500);
	}, false);
})(jQuery);
