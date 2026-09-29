# Einstellungen

Öffne **Erweiterungen → Meine Erweiterungen → MGD EU Label Plugin → Konfiguration**. Wähle „Alle Verkaufskanäle“ für geerbte Standardwerte oder einen konkreten Kanal für Abweichungen. Dokumentiere vor Änderungen, welche Werte geerbt und welche überschrieben sind.

## Anzeige

`enabled` schaltet die Storefront-Ausgabe an oder aus. `footerLink` ergänzt den nativen Footer, ohne seine bestehenden Links zu löschen. `headerLink` und `checkoutLink` bieten zusätzliche sichtbare Auslöser. `inlineNotice` zeigt das vollständige Motiv unterhalb des Footers direkt an, unabhängig vom Vergrößerungsdialog und außerhalb von Theme-Footer-Farbregeln.

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
