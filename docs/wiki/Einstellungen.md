# Einstellungen

Öffne **Erweiterungen → Meine Erweiterungen → MGD EU Label Plugin → Konfiguration**. Wähle „Alle Verkaufskanäle“ für geerbte Standardwerte oder einen konkreten Kanal für Abweichungen. Dokumentiere vor Änderungen, welche Werte geerbt und welche überschrieben sind.

## Anzeige

`enabled` schaltet die Storefront-Ausgabe an oder aus. `footerLink` ergänzt den nativen Footer, ohne seine bestehenden Links zu löschen. `headerLink` und `checkoutLink` bieten zusätzliche sichtbare Auslöser. `productTab` ergänzt einen Reiter im nativen Erlebniswelten-Element „Produktbeschreibung und Bewertungen“. Die frühere Direktanzeige `inlineNotice` wird ab 1.2.0 ignoriert; unterhalb des Footers erscheint keine große Grafik mehr.

Standardmäßig ist nur der Footer-Link aktiv. Das ist eine technische Voreinstellung, keine rechtliche Empfehlung für jeden Shop. Stelle zusätzliche Orte anhand deines Layouts und deiner rechtlichen Prüfung ein.

Die Bildbestandteile, Farben, QR-Codes und Texte des amtlichen Motivs sind keine bearbeitbaren Konfigurationsfelder. Eigene Gestaltung gehört ausschließlich um das Bild herum.

## Sprache und Text

`language=de` ist der Standard, unabhängig von der Sprache des Verkaufskanals. Der bisherige Wert `auto` bleibt aus Kompatibilitätsgründen auswählbar, zeigt aber ebenfalls Deutsch. Nur `language=en` zeigt explizit die englische Originaldatei. Für andere Sprachmärkte genügt dies nicht automatisch; ergänze deren offizielle Dateien und Sprachauswahl vor der Freigabe.

`linkText` leer lassen, wenn der lokalisierte Standardtext gewünscht ist. Ein eigener Text soll den Inhalt verständlich benennen, nicht nur „Mehr“ oder „Info“. Der Wert ist Klartext; HTML oder Skripte gehören nicht hinein.

## Updates

`automaticUpdates` ist zunächst aus. Nach bewusster Aktivierung prüft ein Scheduled Task ungefähr stündlich Releases und kann Dateien vorbereiten. Das ist eine betrieblich relevante Einstellung: Schreibrechte, Queue, Wartung und Sicherungen vorher prüfen. Das native Shopware-Update muss anschließend separat ausgeführt werden.

Diese Betriebseinstellung ausdrücklich unter **„Alle Verkaufskanäle“** setzen. Der Scheduled Task liest den globalen Wert; ein ausschließlich kanalspezifischer Override startet keine automatische Prüfung. Ein Plugin-Dateiupdate betrifft die gemeinsame Installation.

## Nach jeder Änderung

Speichern, Cache berücksichtigen und den echten Verkaufskanal öffnen. Linktext, Sprache, vollständiges Motiv und Mobilansicht prüfen. Bei einem CDN gegebenenfalls dessen Cache gezielt erneuern. Browserprüfung nicht durch eine grüne Speicherbestätigung ersetzen.

## Anzeige und Sprache ab Version 1.2.0

Die Plugin-Einstellungen sind für eine deutsche Shopware-Administrationssprache vollständig deutsch. Wer Englisch bevorzugt, kann die Sprache seines Administrationsprofils auf Englisch umstellen. Das ist unabhängig von der Einstellung **Grafiksprache**: Das Label und seine Standardtexte erscheinen standardmäßig auf Deutsch; Englisch lässt sich ausdrücklich auswählen.

**Link im Footer-Service-Menü** zeigt einen kompakten Link. Ein Klick öffnet die vollständige Originalgrafik in einem Modal. Schließen funktioniert über den Schließen-Knopf und Escape; anschließend kehrt der Tastaturfokus zum Link zurück. Ohne JavaScript öffnet der Link die Originalgrafik direkt. Die frühere Ausgabe als großes Bild am Seitenende entfällt, auch wenn `inlineNotice` früher eingeschaltet war.

**Gewährleistung als Reiter auf Produktseiten anzeigen** ist standardmäßig aus. Eingeschaltet ergänzt es das native CMS-Element „Produktbeschreibung und Bewertungen“ um einen Reiter mit Grafik und ergänzender Zusammenfassung. Auf Mobilgeräten verwendet es Shopwares vorhandene Offcanvas-Ansicht. Eigene Produktlayouts müssen dieses Element enthalten; Themes, die die betreffenden Blöcke ersetzen, benötigen gegebenenfalls eine Anpassung. Footer-Link und Reiter lassen sich gemeinsam verwenden.

Nach dem Update Assets installieren und Shopware-/Theme- sowie gegebenenfalls CDN-Cache erneuern. Eine Datenbankmigration ist nicht nötig. Die offiziellen SVG-Dateien werden unverändert übernommen. Die neue Platzierung ist keine rechtliche Konformitätszusage.
