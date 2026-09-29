# Entwicklerarchitektur

## Grenzen

Die Hauptklasse `Mgd\EuLabel\MgdEuLabel` registriert das Shopware-Plugin. Konfiguration, Twig-Darstellung, öffentliche Assets und Release-Vorbereitung sind getrennte Verantwortungsbereiche. Der normale Storefront-Pfad enthält keine externe Updateanfrage.

`src/Configuration/` normalisiert SystemConfig-Werte. Ein Twig-Zugriff liefert die Anzeigeentscheidung für den Verkaufskanal und Sprachkontext. Templates unter `src/Resources/views/` ergänzen native Blöcke; CSS/JavaScript und Originalgrafiken unter `src/Resources/public/` werden lokal bereitgestellt.

`src/Update/` behandelt Release-Metadaten, Archivprüfung und Installation. `src/ScheduledTask/` verbindet die optionale Prüfung mit Shopwares Queue. Admin-Routen müssen Authentifizierung und ACL verlangen.

## Regeln für Änderungen

Keine globalen CSS-Regeln und keine Kopie kompletter Shopware-Templates. `parent()` erhält vorhandene Inhalte. Keine hardcodierten Kunden-UUIDs, Domainnamen oder Footer-Kategorien. Die Komponente soll auch in einem anderen Shop mit nativen Blöcken funktionieren.

Konfigurationswerte als Klartext ausgeben und Twig-Escaping beibehalten. Keine `raw`-Ausgabe für eigene Linkbeschriftungen. Amtliche Motive sind externe Originalassets und dürfen nicht mit frei hochgeladenen Skript-SVGs gleichgesetzt werden.

## Update-Lifecycle

Dateipaketvorbereitung ist nicht Plugin-Lifecycle. Die Vorbereitung validiert und tauscht Dateien kontrolliert aus; Shopware verarbeitet anschließend sein natives Update. Neue Datenbankmigrationen künftig additiv entwerfen und eigenes Rollback-/Backup-Konzept dokumentieren.

## Tests

Referenztests müssen echte ZIP-Archive und reale Dateisystemzustände prüfen, nicht nur erfolgreich gemockte Downloads. Negative Pfad-/Identitäts-/Größentests gehören zum Sicherheitsvertrag. Native Twig-/DI-Integration, Queue und ACL brauchen zusätzliche Shopware-Tests.

Neue Anzeigeorte brauchen Browserprüfungen mit Tastatur, deaktiviertem JavaScript und Mobilansicht. Lokale QA-Artefakte und Sicherungen bleiben ignoriert und werden nicht als Release-Inhalte gepackt.
