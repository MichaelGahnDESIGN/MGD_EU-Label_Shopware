'use strict';

// Führt das echte Frontend-Skript aus; die kleine DOM-Attrappe prüft nur dessen Ereignislogik.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
class ElementMock { closest() { return null; } }
const documentListeners = new Map();
const dialogListeners = new Map();
const dialog = new ElementMock();
dialog.open = false;
dialog.addEventListener = (type, callback) => dialogListeners.set(type, callback);
dialog.getBoundingClientRect = () => ({ left: 100, right: 500, top: 100, bottom: 600 });
dialog.showModal = () => { dialog.open = true; };
dialog.close = () => { dialog.open = false; dialogListeners.get('close')?.(); };
const opener = new ElementMock();
opener.isConnected = true;
opener.focus = () => { opener.focused = true; };
opener.closest = selector => selector === '[data-mgd-eu-label-open]' ? opener : null;
vm.runInNewContext(fs.readFileSync(require('node:path').join(__dirname, '../src/Resources/public/notice.js'), 'utf8'), {
    Element: ElementMock,
    document: { getElementById: () => dialog, addEventListener: (type, callback) => documentListeners.set(type, callback) },
});
function open() {
    opener.focused = false;
    documentListeners.get('click')({ target: opener, button: 0, preventDefault() {} });
    assert.equal(dialog.open, true);
}
function gesture(downTarget, downX, downY, clickTarget, clickX, clickY) {
    dialogListeners.get('pointerdown')?.({ target: downTarget, clientX: downX, clientY: downY, button: 0 });
    dialogListeners.get('click')?.({ target: clickTarget, clientX: clickX, clientY: clickY, button: 0 });
}
open();
gesture(dialog, 50, 200, dialog, 50, 200);
assert.equal(dialog.open, false, 'Klick außerhalb muss schließen');
assert.equal(opener.focused, true, 'Fokus muss zum Auslöser zurückkehren');
open();
gesture(dialog, 120, 120, dialog, 120, 120);
assert.equal(dialog.open, true, 'Freier Platz innerhalb des Dialogs darf nicht schließen');
const image = new ElementMock();
gesture(image, 200, 200, image, 200, 200);
assert.equal(dialog.open, true, 'Grafik/Text dürfen nicht schließen');
gesture(image, 200, 200, dialog, 50, 200);
assert.equal(dialog.open, true, 'Hinausziehen aus dem Dialog darf nicht schließen');
gesture(dialog, 50, 200, dialog, 200, 200);
assert.equal(dialog.open, true, 'Hineinziehen in den Dialog darf nicht schließen');
dialogListeners.get('pointerdown')?.({ target: dialog, clientX: 50, clientY: 200, button: 0 });
dialogListeners.get('pointercancel')?.();
dialogListeners.get('click')?.({ target: dialog, clientX: 50, clientY: 200, button: 0 });
assert.equal(dialog.open, true, 'Abgebrochene Touch-Geste darf nicht schließen');
console.log('PASS Dialog: Hintergrund, Innenfläche, Grafik, Ziehen, Pointer-Abbruch und Fokus');
