(() => {
	const chooseLogo = document.querySelector('#lssk-choose-logo');
	if (chooseLogo) {
		const input = document.querySelector('#lssk-logo');
		const preview = document.querySelector('#lssk-logo-preview');
		const frame = wp.media({
			title: 'Velg firmalogo',
			button: { text: 'Bruk logo' },
			library: { type: 'image' },
			multiple: false,
		});
		frame.on('select', () => {
			const logo = frame.state().get('selection').first().toJSON();
			const image = new Image();
			image.src = logo.sizes?.thumbnail?.url || logo.url;
			image.alt = '';
			image.width = 120;
			input.value = logo.id;
			preview.replaceChildren(image);
		});
		chooseLogo.addEventListener('click', () => frame.open());
		document.querySelector('#lssk-remove-logo').addEventListener('click', () => {
			input.value = '0';
			preview.replaceChildren();
		});
	}

	const canvas = document.querySelector('#lssk-qr-canvas');
	if (!canvas || typeof qrcodegen === 'undefined') return;

	const { QrCode, QrSegment } = qrcodegen;
	const accessUrl = canvas.dataset.accessUrl;
	const download = document.querySelector('#lssk-qr-download');
	const brandedDownload = document.querySelector('#lssk-qr-branded-download');
	const status = document.querySelector('#lssk-qr-logo-status');
	const plainQr = QrCode.encodeText(accessUrl, QrCode.Ecc.MEDIUM);
	const quietZone = 4;
	const scale = 12;
	canvas.style.width = 'min(260px, 100%)';
	canvas.style.height = 'auto';

	function drawQr(qr) {
		canvas.width = canvas.height = (qr.size + 2 * quietZone) * scale;
		const context = canvas.getContext('2d');
		context.fillStyle = '#fff';
		context.fillRect(0, 0, canvas.width, canvas.height);
		context.fillStyle = '#000';
		for (let y = 0; y < qr.size; y++) {
			for (let x = 0; x < qr.size; x++) {
				if (qr.getModule(x, y)) {
					context.fillRect((x + quietZone) * scale, (y + quietZone) * scale, scale, scale);
				}
			}
		}
		return context;
	}

	drawQr(plainQr);
	canvas.hidden = false;
	download.href = canvas.toDataURL('image/png');
	download.hidden = false;

	let pendingLogo;
	const posterButton = document.querySelector('#lssk-poster-download');
	function loadLogo(url) {
		const logo = new Image();
		pendingLogo = logo;
		posterButton.disabled = true;
		brandedDownload.hidden = true;
		brandedDownload.removeAttribute('href');
		status.hidden = true;
		logo.crossOrigin = 'anonymous';
		const showError = () => {
			if (pendingLogo !== logo) return;
			drawQr(plainQr);
			status.textContent = 'QR-koden med logo kunne ikke lages. Velg et annet bilde eller last ned QR-koden uten logo.';
			status.hidden = false;
			posterButton.disabled = false;
		};
		logo.onerror = showError;
		logo.onload = () => {
			if (pendingLogo !== logo) return;
			try {
				// Keep the logo inside the gap between the central alignment patterns.
				const qr = QrCode.encodeSegments(QrSegment.makeSegments(accessUrl), QrCode.Ecc.HIGH, 14, 20);
				const context = drawQr(qr);
				const box = 15 * scale;
				const offset = (canvas.width - box) / 2;
				const fit = (box - scale) / Math.max(logo.naturalWidth, logo.naturalHeight);
				const width = logo.naturalWidth * fit;
				const height = logo.naturalHeight * fit;
				context.fillStyle = '#fff';
				context.fillRect(offset, offset, box, box);
				context.drawImage(logo, (canvas.width - width) / 2, (canvas.height - height) / 2, width, height);
				brandedDownload.href = canvas.toDataURL('image/png');
				brandedDownload.hidden = false;
				posterButton.disabled = false;
			} catch {
				showError();
			}
		};
		logo.src = url;
	}

	const logoPicker = wp.media({
		title: 'Velg logo til QR-koden',
		button: { text: 'Bruk logo' },
		library: { type: 'image' },
		multiple: false,
	});
	const chooseQrLogo = document.querySelector('#lssk-qr-choose-logo');
	const removeQrLogo = document.querySelector('#lssk-qr-remove-logo');
	let savedLogoUrl = canvas.dataset.logoUrl;
	async function saveLogo(id) {
		chooseQrLogo.disabled = removeQrLogo.disabled = true;
		try {
			const response = await fetch(ajaxurl, {
				method: 'POST',
				body: new URLSearchParams({
					action: 'lssk_save_qr_logo',
					logo_id: id,
					_ajax_nonce: canvas.dataset.logoNonce,
				}),
			});
			const result = await response.json();
			if (!response.ok || !result.success) throw new Error();
			savedLogoUrl = result.data.url;
			if (savedLogoUrl) {
				loadLogo(savedLogoUrl);
			} else {
				pendingLogo = null;
				brandedDownload.hidden = true;
				brandedDownload.removeAttribute('href');
				drawQr(plainQr);
				posterButton.disabled = false;
				status.hidden = true;
			}
		} catch {
			status.textContent = 'Kunne ikke lagre QR-logoen. Prøv på nytt.';
			status.hidden = false;
		} finally {
			chooseQrLogo.disabled = false;
			removeQrLogo.disabled = !savedLogoUrl;
		}
	}

	logoPicker.on('select', () => {
		const logo = logoPicker.state().get('selection').first().toJSON();
		saveLogo(logo.id);
	});
	chooseQrLogo.addEventListener('click', () => logoPicker.open());
	removeQrLogo.addEventListener('click', () => saveLogo(0));
	const posterForm = document.querySelector('#lssk-poster-form');
	const posterPassword = document.querySelector('#lssk-poster-password');
	posterForm.addEventListener('submit', (event) => {
		event.preventDefault();
		const password = posterPassword.value.trim();
		if (!password) return;
		const poster = document.createElement('canvas');
		poster.width = 1200;
		poster.height = 1700;
		const ctx = poster.getContext('2d');
		const modules = canvas.width / scale;
		const qrWidth = Math.floor(1050 / modules) * modules;
		const center = poster.width / 2;
		const qrTop = 42;
		ctx.fillStyle = '#fff';
		ctx.fillRect(0, 0, poster.width, poster.height);
		ctx.imageSmoothingEnabled = false;
		ctx.drawImage(canvas, Math.floor((poster.width - qrWidth) / 2), qrTop, qrWidth, qrWidth);
		const headingY = qrTop + qrWidth + 72;
		ctx.fillStyle = '#151515';
		ctx.textAlign = 'center';
		ctx.font = 'bold 76px Arial, sans-serif';
		ctx.fillText('Skann for rabattkode', center, headingY, 1080);
		const page = new URL(accessUrl);
		ctx.font = '54px Arial, sans-serif';
		ctx.fillText(page.host + page.pathname.replace(/\/$/, ''), center, headingY + 80, 1080);
		ctx.font = 'bold 54px Arial, sans-serif';
		let line = '';
		let y = headingY + 160;
		for (const character of 'Kode: ' + password) {
			if (ctx.measureText(line + character).width > 1080) {
				ctx.fillText(line, center, y);
				y += 60;
				line = '';
			}
			line += character;
		}
		ctx.fillText(line, center, y);
		const link = document.createElement('a');
		link.href = poster.toDataURL('image/png');
		link.download = 'lssk-rabattkoder-plakat.png';
		link.click();
	});
	posterButton.disabled = false;
	if (canvas.dataset.logoUrl) loadLogo(canvas.dataset.logoUrl);
})();
