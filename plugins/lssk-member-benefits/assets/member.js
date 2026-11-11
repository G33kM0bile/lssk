(() => {
	window.addEventListener('pageshow', (event) => {
		if (event.persisted) location.reload();
	});

	const match = location.hash.match(/^#tilgang=([a-f0-9]{64})$/);
	if (match) {
		history.replaceState(null, '', location.pathname + location.search);
		const form = document.querySelector('#lssk-access-form');
		if (form) {
			const token = document.createElement('input');
			token.type = 'hidden';
			token.name = 'lssk_qr';
			token.value = match[1];
			form.append(token);
			form.submit();
		}
	}

	if (!navigator.clipboard) return;
	document.querySelectorAll('.copy-code').forEach((button) => {
		button.hidden = false;
		const codeRow = button.parentElement;
		const status = codeRow.querySelector('.copy-status');
		button.addEventListener('click', async () => {
			try {
				await navigator.clipboard.writeText(codeRow.querySelector('code').textContent);
				status.textContent = 'Kopiert';
			} catch {
				status.textContent = 'Marker og kopier koden manuelt.';
			}
		});
	});
})();
