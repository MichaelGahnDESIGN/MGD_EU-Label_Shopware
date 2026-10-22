# Änderungen

## 1.2.1 – 2026-10-23

- Release-Build verweigert Pakete ohne fertige JavaScript-, CSS- und Grafikdateien.
- Upload und GitHub-Update ohne eigenen Administration-/Theme-Build dokumentiert.
- Veröffentlichung der Dateien bleibt im nativen Shopware-Updateablauf.


## 1.2.0 – 2026-10-22

- Deutsche und englische Einstellungstexte explizit zugeordnet.
- Footer-Link mit Modal als Standard; alte Direktanzeige am Seitenende entfernt.
- Geschlossene Dialoge gegen allgemeine Theme-Anzeigeregeln geschützt.
- Optionaler Produktreiter mit nativer mobiler Offcanvas-Ansicht.
- Render- und Verhaltenstests für Sprache, Schalter und bestehende Produktinhalte ergänzt.


## 1.1.4 – 2026-10-22

- Plugin-Icon lokal eingebunden und im Release-Paket enthalten.
- Darstellung in der Erweiterungsverwaltung ergänzt beziehungsweise verbessert.
- Keine Änderung an Fachlogik, Einstellungen oder Datenbank.

## 1.1.3 – deutsche Standardsprache und kürzerer Update-Check

- Die amtliche deutsche Grafik ist Standard, auch bei englischem Verkaufskanal; Englisch erscheint nur bei expliziter Auswahl.
- Bisher gespeicherte `auto`-Werte bleiben funktionsfähig und zeigen jetzt Deutsch.
- Optionale GitHub-Release-Prüfung von sechs Stunden auf eine Stunde verkürzt; ein Release löst ohne Polling oder manuelle Admin-Aktion keinen sofortigen Updatehinweis aus.
- Keine geänderten Shopware- oder PHP-Anforderungen.

## 1.1.2 – Schließen über den Hintergrund

- Klick oder Tippen auf den abgedunkelten Bereich außerhalb der Lightbox schließt sie.
- Innenabstände, Grafik, Text und begonnene Wisch-/Ziehgesten innerhalb des Dialogs schließen ihn nicht versehentlich.
- Fokus-Rückgabe, Escape und Schließen-Button bleiben erhalten.
- Ausführbarer JavaScript-Regressionstest für die tatsächliche Ereignislogik.
- Versionssprung von 0.1.0 auf 1.1.2 auf ausdrücklichen Kundenwunsch; keine geänderten Plattformanforderungen.

## 0.1.0 – erste Ausbaustufe

- Eigenständiges Shopware-6.7-Plugin für den harmonisierten EU-Gewährleistungshinweis.
- Footer-Lightbox, optionale zusätzliche Anzeigeorte und Verkaufskanal-Einstellungen.
- Unveränderte lokale EU-Originaldateien in Deutsch und Englisch.
- Kontrollierte GitHub-Release-Vorbereitung mit nativem Shopware-Update.
- Paketbau, Sicherheitstests, deutsche README und ausführliches Handbuch.

GARAN-Produktlabels und Elektrogeräte-Rückgabe sind noch keine Funktionen dieser Version.
