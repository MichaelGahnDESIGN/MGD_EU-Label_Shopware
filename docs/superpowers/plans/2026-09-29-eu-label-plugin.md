# MGD EU Label Plugin Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development. Umsetzung mit separater Spezifikations- und Qualitätsprüfung; keine Kunden-Zugangsdaten in diesem Repository.

**Goal:** Wiederverwendbares Shopware-6.7-Plugin für den offiziellen EU-Gewährleistungshinweis mit Footer-Lightbox, Einstellungen und kontrollierten GitHub-Updates veröffentlichen.

**Architecture:** Das Plugin erweitert native Twig-Blöcke ohne Core- oder Theme-Dateien zu verändern. Offizielle farbige Originaldateien werden lokal ausgeliefert. Konfiguration, Darstellung und GitHub-Paketprüfung sind getrennte Komponenten; spätere produktbezogene GARAN-Daten gehören nicht in die aktuelle globale Hinweis-Konfiguration.

**Tech Stack:** PHP 8.2+, Shopware 6.7, Symfony-DI/HttpClient, Twig, native HTML-Dialoge mit isoliertem JavaScript, Shopware-SystemConfig, GitHub Releases und Actions.

## Rahmen und Quellen

- Original-Repository nach `MichaelGahnDESIGN/MGD_EU-Label_Shopware` umbenannt; Entwicklung auf `codex/eu-label-plugin`.
- EU-Leitlinie vom April 2026, Abschnitt 2.3: klickbare Hinweise z. B. im Katalog, Header und Checkout; Fußzeile allein ist kein Nachweis ausreichender Hervorhebung. Keine Rechtsberatung oder Konformitätszusage.
- Native Shopware-Twig-Dateien aus der aktuellen Kundeninstallation nur als lokale, nicht veröffentlichte Referenz verwenden.
- Updater-Vorbild: öffentliches `MGD_Ausverkauft_Shopware-Plugin`; hier keine ungeprüfte Übernahme seiner direkten Dateikopien.
- Keine Bestellmails, Zahlungs- oder Produktdaten verändern. Keine GARAN-Musterdaten erfinden.

## Aufgabe 1: Plugin und Sicherheitsgrenzen

**Dateien:** `composer.json`, `src/MgdEuLabel.php`, `src/Configuration/`, `src/Resources/config/`, `src/Resources/views/storefront/`, `src/Resources/public/`, `src/Update/`, `src/ScheduledTask/`, `tests/`, `scripts/build-release.php`, `.github/workflows/`.

- [ ] Zuerst PHP-Referenztests für Konfigurationsnormalisierung und Release-Paketprüfung anlegen und den erwarteten Fehler bei fehlenden Klassen nachweisen.
- [ ] `Mgd\\EuLabel\\MgdEuLabel` als Plugin-Klasse und `MgdEuLabel` als ZIP-Wurzel verwenden; Version `0.1.0`, Shopware `~6.7.0`.
- [ ] Konfiguration: aktiv, Footer-Link, Header-Link, Checkout-Hinweis, direkte Footer-Anzeige, Sprache auto/de/en, individuelle Linkbeschriftung; keine Änderung am offiziellen Bild.
- [ ] Footer-Link ergänzt vorhandene Navigation, ohne deren Inhalte zu ersetzen. Native Dialog-Lightbox außerhalb der Footer-CSS-Kaskade: Escape, Fokus-Rückgabe, Tastatur, responsive Anzeige, lesbarer Bildinhalt und klickbarer QR-Ziel-Link. Ohne JavaScript funktioniert der Link zur vollständigen Originaldatei.
- [ ] GitHub-Updater: festes öffentliches Repository und Asset `MgdEuLabel.zip`; ausschließlich stabile höhere Semver-Versionen. Keine frei konfigurierbare Download-URL. Größenlimit, SHA-256-Abgleich des Release-Assets, strenge ZIP-Pfade/Wurzel, keine Symlinks, Plugin-Klasse und Version prüfen. Private temporäre Staging-/Backup-Verzeichnisse, Prozesssperre, kontrollierter Dateiaustausch mit Wiederherstellung bei Fehlern. Anschließend nur native Plugin-Liste aktualisieren, Lifecycle-Update bleibt in Shopware.
- [ ] Updateprüfung standardmäßig aus, optional via Scheduled Task; Backend-Konfiguration erklärt Queue-/Cron-Abhängigkeit. GitHub-Ausfall beeinflusst niemals den Storefront-Aufruf.
- [ ] Reproduzierbares Paket mit genau Plugin-Dateien und Original-Assets erzeugen, keine Tests, Zugangsdaten, Git- oder Wiki-Verzeichnisse im ZIP.
- [ ] PHP-Tests mit `php tests/run.php`, Syntaxprüfungen mit `find src tests scripts -name '*.php' -exec php -l {} \;`, Composer-Metadaten mit `composer validate --strict` prüfen. Negative Archivtests umfassen `../`, absolute Pfade, Backslash, Symlink und falsche Identität.
- [ ] Implementierung committen und erst Spezifikationsprüfung, danach Qualitätsprüfung durchführen; offene Befunde vor Veröffentlichung beheben.

## Aufgabe 2: Handbuch und Veröffentlichung

**Dateien:** `README.md`, `CHANGELOG.md`, `SECURITY.md`, `CONTRIBUTING.md`, `LICENSE`, `docs/wiki/*.md`, `assets/`, `.github/workflows/quality.yml` und `release.yml`.

- [ ] Deutsche README mit MGD-Header, Voraussetzungen, konkretem Funktionsumfang, Konfigurationstabelle, Installationsanleitung, GitHub-Updater, Sicherheits-/Datenschutzhinweisen, Testgrenzen und offiziellen EU-Quellen erstellen.
- [ ] Wiki: Installation, Einstellungen, rechtliche Anzeigegrenzen, Barrierefreiheit, Theme-Integration, Update/Rückfall, Entwicklerarchitektur, Erweiterungsmodell, Fehlerbehebung und Releaseprozess; GitHub-Wiki und versionierte Kopie synchron halten.
- [ ] Lizenz des eigenen Codes und Herkunft/Nutzungsbedingungen der EU-Originaldateien getrennt dokumentieren; Originalgrafiken unverändert lassen.
- [ ] ZIP-Inhaltsliste und Dateihashes überprüfen; GitHub-Release mit Paket und Prüfsumme veröffentlichen. Erfolg anhand tatsächlicher Actions-/Release-Ausgabe feststellen.

## Aufgabe 3: Kundenintegration und Live-Prüfung

**Nur außerhalb des öffentlichen Plugin-Repositories:** vorhandener Kunden-Client und ignorierte Sicherungen im Schwarzwaldladen-Projekt.

- [ ] Plugin-/Theme-Zustand und Konfiguration lesen. Sicherung der betroffenen Konfiguration vor Aktivierung; keine Theme-/Footer-Navigationsdaten löschen.
- [ ] Kontrolliert installieren und aktivieren; Assets veröffentlichen, Theme kompilieren, Cache leeren. Kundenwunsch Footer-Link aktivieren; zusätzliche Platzierung erst nach geklärter Auswahl live umstellen.
- [ ] Chrome: Footer-Link, vollständige Grafik, Escape, Tab/Fokus-Rückgabe, Hintergrundinteraktion, 390/768/1440 Pixel, JavaScript-freier Fallback und vorhandene Navigation prüfen.
- [ ] Backend-Update-Erkennung und Fehlerszenarien getrennt von reiner Paket-/Unit-Prüfung berichten. Keine produktive Update-Ausführung simulieren oder als geprüft ausgeben.
- [ ] Abschlussbericht unterscheidet Quellcode, Tests, GitHub/Wiki, Release, Installation und Live-Abnahme.
