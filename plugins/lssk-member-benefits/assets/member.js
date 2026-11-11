(() => {
	window.addEventListener('pageshow', (event) => {
		if (event.persisted) location.reload();
	});

	const match = document.body.classList.contains('lssk-standalone') && location.hash.match(/^#tilgang=([a-f0-9]{64})$/);
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

	function showCopyButtons(container) {
		if (!navigator.clipboard) return;
		container.querySelectorAll('.copy-code').forEach((button) => { button.hidden = false; });
	}

	showCopyButtons(document);
	document.addEventListener('click', async (event) => {
		const button = event.target.closest('.lssk-benefits .copy-code');
		if (button && navigator.clipboard) {
			const codeRow = button.parentElement;
			const status = codeRow.querySelector('.copy-status');
			try {
				await navigator.clipboard.writeText(codeRow.querySelector('code').textContent);
				status.textContent = 'Kopiert';
			} catch {
				status.textContent = 'Marker og kopier koden manuelt.';
			}
		}
	});

	document.querySelectorAll('[data-lssk-embed]').forEach((container) => {
		const endpoint = new URL(container.dataset.lsskEmbed, location.href);
		if (endpoint.origin !== location.origin) return;

		async function refresh(body) {
			const response = await fetch(endpoint, { method: body ? 'POST' : 'GET', body, cache: 'no-store' });
			if (!response.ok) throw new Error();
			const template = document.createElement('template');
			template.innerHTML = await response.text();
			const panel = template.content.querySelector('[data-lssk-panel]');
			if (!panel) throw new Error();
			container.replaceChildren(panel);
			showCopyButtons(container);
			if (body) {
				const focusTarget = panel.querySelector('[role="alert"], input, .lssk-embedded-logout button');
				if (focusTarget) {
					if (focusTarget.getAttribute('role') === 'alert') focusTarget.tabIndex = -1;
					focusTarget.focus();
				}
			}
		}

		refresh().catch(() => {});
		container.addEventListener('submit', async (event) => {
			event.preventDefault();
			const body = new URLSearchParams(new FormData(event.target, event.submitter));
			if (event.submitter) event.submitter.disabled = true;
			try {
				await refresh(body);
			} catch {
				const error = document.createElement('p');
				error.className = 'error';
				error.setAttribute('role', 'alert');
				error.textContent = 'Kunne ikke laste rabattkodene. Prøv på nytt.';
				const previous = container.querySelector('[role="alert"]');
				if (previous) previous.replaceWith(error);
				else event.target.before(error);
			} finally {
				if (event.submitter) event.submitter.disabled = false;
			}
		});
	});
})();
