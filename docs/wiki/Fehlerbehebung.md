# Fehlerbehebung

## Plugin nicht erkennbar

Prüfe `MgdEuLabel/composer.json`, den technischen Plugin-Klassennamen und Ordner unter `custom/plugins/`. Kein zusätzliches äußeres Repository-Verzeichnis verwenden. `plugin:refresh` ausführen und PHP-/Shopware-Anforderungen kontrollieren.

## Footer-Link fehlt

Aktivierung, `enabled`, `footerLink` und konkreten Verkaufskanal prüfen. Cache/CDN erneuern. Bei eigenem Theme die native Footer-Vererbung prüfen. Keine bestehenden Kategorien löschen, um vermeintlich Platz für den Link zu schaffen.

## Link öffnet nur eine Datei

Ohne JavaScript ist das beabsichtigter Fallback. Andernfalls prüfen, ob Plugin-JavaScript mit HTTP 200 ausgeliefert wird, durch CSP blockiert ist oder ein anderes Skript Fehler wirft. Öffentliche Assets installieren; Browserfehler mit sauberem Cache reproduzieren.

## Grafik leer oder falsche Sprache

Bild-URL und `assets:install` prüfen. Bei `auto` wird nur Deutsch erkannt, sonst Englisch. Sprachkontext und feste Einstellung prüfen; Englisch ist kein automatischer Nachweis der richtigen Absatzmarktsprache.

## Text/Schließen schlecht lesbar

Globale Theme-Regeln oder erzwungene Footer-Farben können hineinwirken. Betroffene CSS-Regel in den Browserwerkzeugen feststellen und gezielt korrigieren. Nicht das offizielle Motiv umfärben. Mobile Höhe, Scrollbereich und Browserzoom prüfen.

## Kein Update angeboten

Existiert ein höheres stabiles Release mit exakt `MgdEuLabel.zip`? Ist automatische Prüfung aktiviert, läuft die Queue, stimmen Digest und Schreibrechte? Eine unveränderte Versionsnummer ist kein Update. Ein heruntergeladenes Paket muss durch Plugin-Refresh erkannt und dann nativ aktualisiert werden.

## Paket wird abgelehnt

Ablehnung bei falscher Klasse, Version, Pfad, Symlink oder Größe ist eine Sicherheitsfunktion. Nicht Prüfungen abschalten. Paket aus dem offiziellen Release erneut laden bzw. einen korrekt gebauten Maintainer-Release erstellen.

Sind die Composer-`require`-Deklarationen gegenüber der installierten Version geändert, verweigert die erste Ausbaustufe die automatische Dateivorbereitung. Plattformanforderungen kontrolliert prüfen und die neue Version manuell über Shopwares Installation/Deployment einspielen. Nicht nur die Versionsnummer oder Anforderungen umschreiben, um die Prüfung zu umgehen.

## Supportangaben

Plugin-/Shopware-/PHP-Version, Theme, Verkaufskanal-Sprache, Zeitpunkt, anonymisierte Fehlermeldung und reproduzierbare Schritte nennen. Keine Zugangsdaten, Kundendaten oder Session-Tokens versenden.
