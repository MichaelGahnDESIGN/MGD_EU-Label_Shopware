# Mitarbeit

Änderungen in eigenen Branches entwickeln. Issues und Pull Requests nennen Problem, Shopware-Version, gewünschtes Verhalten und Tests. Keine Produktivzugänge oder personenbezogenen Beispiele einreichen.

UTF-8 und deutsche Kommentare verwenden. Klassen nach Verantwortung trennen, keine globalen Theme-Regeln oder Core-Dateiänderungen. Vor einer Freigabe `composer validate --no-check-publish`, `php tests/run.php`, Syntaxprüfungen und `php scripts/build-release.php` ausführen. Die explizite Plugin-Version führt zum erwarteten Composer-Versionshinweis; Schemafehler dürfen nicht ignoriert werden. Storefront-Änderungen im Browser mit Tastatur und schmalem Viewport prüfen.

Update-Code benötigt Tests für beschädigte Archive, parallele Aufrufe und Rückfall. EU-Grafiken nicht neu zeichnen, beschneiden oder umfärben. Neue Sprachdateien aus offiziellen Veröffentlichungen übernehmen und Hash/Quelle dokumentieren.

GARAN-Daten gehören in ein eigenes Produktmodul. Keine bestehenden Produktfelder oder Shop-Konfigurationen ungefragt verändern. Migrationen müssen additiv sein und Tests sowie Rückfallbeschreibung mitbringen.
