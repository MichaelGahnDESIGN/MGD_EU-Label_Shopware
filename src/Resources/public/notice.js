/* Native dialog übernimmt Fokusfalle und Escape; der Grafiklink bleibt als Rückfallziel erhalten. */
(() => {
    'use strict';
    const dialog = document.getElementById('mgd-eu-label-dialog');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    let opener = null;
    let backdropPointerDown = false;
    // Native Backdrop-Klicks haben den Dialog als Ziel. Die Geometrie trennt sie
    // von dessen Innenabstand; Beginn und Ende müssen außen liegen, damit eine
    // Textauswahl oder Wischgeste aus dem Dialog heraus ihn nicht versehentlich schließt.
    const outsideDialog = (event) => {
        const bounds = dialog.getBoundingClientRect();
        return event.clientX < bounds.left || event.clientX > bounds.right
            || event.clientY < bounds.top || event.clientY > bounds.bottom;
    };
    dialog.addEventListener('pointerdown', (event) => {
        backdropPointerDown = event.button === 0 && event.target === dialog && outsideDialog(event);
    });
    dialog.addEventListener('pointercancel', () => { backdropPointerDown = false; });
    dialog.addEventListener('click', (event) => {
        const dismiss = backdropPointerDown && event.target === dialog && outsideDialog(event);
        backdropPointerDown = false;
        if (dismiss) dialog.close();
    });
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
        backdropPointerDown = false;
        if (opener && opener.isConnected) opener.focus({ preventScroll: true });
        opener = null;
    });
})();
