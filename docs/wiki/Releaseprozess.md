# Releaseprozess

## Versionierung

Version in `composer.json` und Changelog nachvollziehbar erhöhen. In der Alpha-Reihe gilt `0.Y.Z`: Y für größere Funktionen, Z für kleine Verbesserungen/Fehlerkorrekturen. Git-Tag und Paketversion müssen übereinstimmen, etwa `v0.1.0` und `0.1.0`.

## Prüfungen

```bash
composer validate --no-check-publish
php tests/run.php
find src tests scripts -name '*.php' -exec php -l {} \;
php scripts/build-release.php
unzip -l dist/MgdEuLabel.zip
```

Paketwurzel `MgdEuLabel/`, richtige Plugin-Klasse und unveränderte Originalgrafiken kontrollieren. Keine `.env`, Git-Daten, Backups, Dokumentations-Screenshots oder Tests im ZIP. Hash des fertigen Pakets mit dem veröffentlichten Asset vergleichen.

## GitHub

Nur geprüfte Änderungen nach `main` übernehmen. Release-Workflow anhand der tatsächlichen Workflow-Datei auslösen und seinen Abschluss prüfen. `MgdEuLabel.zip` und Prüfsumme veröffentlichen. GitHub stellt für hochgeladene Release-Assets einen Digest bereit; der Updater verlangt passende Integritätsmetadaten.

„Push erfolgreich“ bedeutet nicht „Release erfolgreich“. Actions-Status, Assetnamen, Download und Paketinhalt separat prüfen. Source-Code-Archive sind nicht die Updatequelle.

Geänderte Composer-`require`-Deklarationen brauchen einen gesonderten Plattform-/Installationshinweis im Release. Die erste Updater-Version bereitet solche Pakete nicht automatisch vor; das ist eine Fail-Closed-Sicherheitsgrenze, kein Grund, erforderliche Abhängigkeiten aus dem Manifest zu entfernen.

## Wiki

`docs/wiki/` ist die versionierte Quelle. Dieselben Seiten in das GitHub-Wiki übertragen; dort `.md`-Dateiendungen in internen Seitenlinks entfernen. Änderungen an Konfiguration oder Updateverhalten gleichzeitig im Handbuch erklären. Für historische Versionen auf die taggebundene Handbuchkopie verweisen.

## Freigabegrenzen

Lokale Unit-/Referenztests, echte Shopware-Installation, Backend-Update und Browser-Abnahme getrennt protokollieren. Ein nicht getesteter Updatepfad darf nicht als produktionsgeprüft beschrieben werden. Sicherungen und Playtest-Dateien bleiben lokal, nicht im öffentlichen Release.
