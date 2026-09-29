# Sicherheit, Datenschutz und Rückfall

## Daten und Verbindungen

Das Plugin benötigt keine Bestellungen, Kundenprofile oder Zahlungsdaten. Es führt keine eigene Kundentabelle ein und setzt keine Tracking-Cookies. Normale Seitenaufrufe laden die Originalgrafik lokal, nicht von der Kommission oder einem CDN.

Bei einer Updateprüfung sieht GitHub die Server-IP und übliche HTTP-Metadaten. Ein Besucher folgt dem EU-Informationslink nur bewusst. GitHub ist damit ein technischer Updateanbieter, nicht ein Dienst zur Analyse der Besucher.

## Rechte

Konfiguration und Updatevorbereitung sind privilegierte Administration-Aktionen. Die Update-Route darf nicht als öffentliche Storefront-Route freigegeben werden. Reine Lesekonten dürfen keinen Dateiaustausch auslösen. Ohne passend getestete ACL-Auslegung keine eigenen Rollen-Freigaben erweitern.

## Sicherung und Rückfall

Vor Installation/Update Dateien, Datenbank und Plugin-Konfiguration sichern. Ein automatisches Dateibackup ersetzt keine komplette Shop-Sicherung. Prüfe freien Speicher und bewahre Backups außerhalb des Webroots auf.

Bei fehlgeschlagener Paketvorbereitung soll die bisherige Plugin-Version wiederhergestellt werden. Nach einem bereits abgeschlossenen nativen Update reicht eine alte Datei möglicherweise nicht: spätere Migrationen könnten Daten verändert haben. Dann die dokumentierte Versions-/Datenbank-Wiederherstellung verwenden.

Nach Wiederherstellung `plugin:refresh`, Cache, Assets und Theme berücksichtigen und Shop sowie Administration kontrollieren. Nicht blind downgraden oder fremde Dateien im Plugin-Verzeichnis löschen.

## Deaktivierung

Automatische Prüfung deaktivieren, wenn Updatequelle verdächtig ist oder das Hosting Dateiaustausch nicht unterstützt. Storefront-Ausgabe kann unabhängig abgeschaltet werden; rechtlich benötigte Informationen vorher anderweitig bereitstellen.

Sicherheitsvorfälle vertraulich melden. Logs auf Tokens, FTP-URLs, persönliche Daten und Dateipfade mit sensiblen Informationen prüfen, bevor sie geteilt werden.
