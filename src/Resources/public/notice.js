/* Native dialog übernimmt Fokusfalle und Escape; der Grafiklink bleibt als Rückfallziel erhalten. */
(() => {
    'use strict';
    const dialog = document.getElementById('mgd-eu-label-dialog');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    let opener = null;
    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;
        const link = event.target.closest('[data-mgd-eu-label-open]');
        // Modifizierte Klicks behalten das gewohnte Öffnen in neuem Fenster oder Tab.
        if (link && !event.ctrlKey && !event.metaKey && !event.shiftKey && !event.altKey && event.button === 0) {
            if (dialog.open) return;
            try {
                dialog.showModal();
                opener = link;
                event.preventDefault();
            } catch (_) { /* Der normale href-Link funktioniert weiterhin. */ }
        }
        if (event.target.closest('[data-mgd-eu-label-close]')) dialog.close();
    });
    dialog.addEventListener('close', () => {
        if (opener && opener.isConnected) opener.focus({ preventScroll: true });
        opener = null;
    });
})();
