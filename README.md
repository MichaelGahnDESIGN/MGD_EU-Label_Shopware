<!-- MGD-HEADER -->
<p align="center"><a href="https://Michael-Gahn.de"><img src="assets/mgd-logo.png" alt="Michael Gahn DESIGN" width="48"></a></p>
<p align="center"><img src="assets/banner.svg" alt="MGD EU Label Plugin für Shopware" width="100%"></p>
<p align="center">
  <a href="https://github.com/MichaelGahnDESIGN/MGD_EU-Label_Shopware/releases/latest"><img alt="Release" src="https://img.shields.io/github/v/release/MichaelGahnDESIGN/MGD_EU-Label_Shopware?label=Release"></a>
  <img alt="Shopware 6.7" src="https://img.shields.io/badge/Shopware-6.7-189eff">
  <img alt="PHP ab 8.2" src="https://img.shields.io/badge/PHP-ab%208.2-777bb4">
  <img alt="GPL-2.0-or-later" src="https://img.shields.io/badge/Lizenz-GPL--2.0--or--later-blue">
  <a href="https://Michael-Gahn.de"><img alt="Michael Gahn DESIGN" src="https://img.shields.io/badge/by-Michael%20Gahn%20DESIGN-cd1616"></a>
</p>
<!-- /MGD-HEADER -->

# MGD EU Label Plugin

Der offizielle EU-Gewährleistungshinweis als wiederverwendbares Shopware-Plugin: lokales Originalmotiv, Footer-Link mit Lightbox, zusätzliche Anzeigeorte und verkaufskanalbezogene Einstellungen. Ohne Änderungen an Shopware-Core-Dateien und ohne Abhängigkeit von einem bestimmten Kunden-Theme.

Entwickelt von [Michael Gahn DESIGN](https://Michael-Gahn.de).

## Handbuch und Downloads

- [Installierbares Plugin-ZIP](https://github.com/MichaelGahnDESIGN/MGD_EU-Label_Shopware/releases/latest)
- [GitHub-Wiki](https://github.com/MichaelGahnDESIGN/MGD_EU-Label_Shopware/wiki) und [versionierte Handbuchkopie](docs/wiki/Home.md)
- [Änderungen](CHANGELOG.md), [Sicherheit](SECURITY.md), [Mitarbeit](CONTRIBUTING.md)

Verwende das Release-Asset **MgdEuLabel.zip**, nicht GitHubs automatisch erzeugtes „Source code“-ZIP. Das Release-Paket besitzt die für Shopware vorgesehene Ordnerstruktur.

## Funktionen

- Footer-Eintrag „Gesetzliche Gewährleistung“ mit vollständigem Hinweis in einer Lightbox.
- Unveränderte offizielle farbige SVG-Originaldateien der Europäischen Kommission, Deutsch und Englisch.
- Optionale Links im Kopfbereich und Checkout sowie direkt sichtbarer Hinweis unterhalb des Footers.
- Eigene Linkbeschriftung; das offizielle Motiv selbst ist nicht bearbeitbar.
- Einstellungen pro Verkaufskanal in Shopwares normaler Plugin-Konfiguration.
- Automatische Sprachauswahl zwischen Deutsch und Englisch oder feste Sprache.
- Tastaturbedienung, Escape, Fokus-Rückgabe und funktionierender Link ohne JavaScript.
- Klickbarer Informationslink als Alternative zum QR-Code.
- GitHub-Release-Prüfung und kontrollierte Paketvorbereitung; das eigentliche Update bleibt bei Shopware.
- Keine Kundendatenbank, Tracking-Cookies oder externen Bildabrufe im normalen Shop-Aufruf.

**Noch nicht enthalten:** Produktbezogene GARAN-Labels, Garantiebedingungen pro Artikel, Elektrogeräte-Rückgabe-Labels, Bestellmail-Anhänge und weitere Sprachdateien. Die Architektur trennt diese künftigen Module vom allgemeinen Hinweis. Siehe [Erweiterungsmodell](docs/wiki/Erweiterungsmodell.md).

## Rechtliche Einordnung

Gesetzliche Gewährleistung ist nicht gleich freiwillige Herstellergarantie. Das Plugin erfindet keine Garantieansprüche und bewertet nicht, welche Pflichten für einen bestimmten Händler gelten.

Die [EU-Durchführungsverordnung 2025/1960](https://eur-lex.europa.eu/eli/reg_impl/2025/1960/oj/deu) legt Motiv und Inhalt fest. Die [praktischen Leitlinien der Kommission, April 2026](https://commission.europa.eu/publications/practical-guidelines-and-high-resolution-vector-files-eu-notice-and-label-product-guarantees_en), Abschnitt 2.3, zeigen klickbare Anzeigen im Katalog, Kopfbereich und Checkout als Beispiele. Der vollständige Hinweis muss lesbar erscheinen; eine erreichbare Seite allein beweist noch keine hinreichend hervorgehobene Platzierung.

**Ein Footer-Link allein ist keine Zusage rechtlicher Konformität.** Prüfe Platzierung, Lesbarkeit, Sprache und weitere Informationen mit deiner Rechtsberatung. Bestellbestätigung und nationale Vorschriften sind gesondert zu betrachten. Das Plugin verändert keine Bestellmails. Die Leitlinien selbst sind keine verbindliche gerichtliche Auslegung.

Originalgrafiken werden nicht beschnitten, umgefärbt oder mit Werbung überlagert. Online wird nur die farbige Version verwendet. Gestaltungseinstellungen betreffen die Einbindung, nicht den festgelegten Bildinhalt.

## Voraussetzungen

| Voraussetzung | Stand |
| --- | --- |
| Shopware | 6.7.x mit Storefront; andere Versionen nicht freigegeben |
| PHP | ab 8.2, zusätzlich Anforderungen deiner Shopware-Version |
| Erweiterungen | ZIP, cURL, mbstring, JSON und normale Shopware-Anforderungen |
| Theme | Native Footer-/Base-/Meta-Twig-Blöcke oder entsprechende Integration |
| Updatequelle | Öffentliches GitHub-Repository, kein persönlicher Token erforderlich |
| Automatische Prüfung | Verarbeitete Scheduled Tasks und Message Queue |
| Dateirechte | Plugin-Verzeichnis und private Update-Verzeichnisse beschreibbar |

Versionsangaben ersetzen keinen Test mit deinem Theme. Headless-/Store-API-Frontends werden nicht automatisch erweitert.

## Installation im Backend

1. Vor Produktivänderungen Dateien und Datenbank sichern; Wiederherstellung prüfen.
2. `MgdEuLabel.zip` aus dem Release herunterladen.
3. **Erweiterungen → Meine Erweiterungen → Erweiterung hochladen** öffnen.
4. Plugin installieren und aktivieren.
5. Über das Erweiterungsmenü **Konfiguration** öffnen und gegebenenfalls Verkaufskanal auswählen.
6. Cache leeren, öffentliche Plugin-Assets veröffentlichen und aktives Theme neu kompilieren.
7. Footer-Link, Grafik, Tastatur, Mobilansicht und gewünschte weitere Anzeigeorte im echten Shop prüfen.

## Installation per CLI

Ordner `MgdEuLabel` aus dem Paket nach `custom/plugins/` kopieren. Im Shopware-Hauptverzeichnis:

```bash
bin/console plugin:refresh
bin/console plugin:install --activate MgdEuLabel
bin/console assets:install
bin/console cache:clear
bin/console theme:compile
```

Nicht das gesamte Git-Repository oder die ZIP-Datei als Plugin-Ordner ablegen. Weitere Details: [Installation](docs/wiki/Installation-und-Updates.md).

## Einstellungen

Werte liegen unter `MgdEuLabel.config.*`. Die Verkaufskanal-Vererbung folgt Shopwares SystemConfig-Verhalten.

| Schlüssel | Bedeutung | Standard |
| --- | --- | --- |
| `enabled` | Storefront-Hinweis aktiv | an |
| `footerLink` | Footer-Link mit Lightbox | an |
| `headerLink` | Zusätzlicher Link im Kopfbereich | aus |
| `checkoutLink` | Zusätzlicher Link im Checkout | aus |
| `inlineNotice` | Vollständiges Motiv unterhalb des Footers | aus |
| `language` | `auto`, `de` oder `en` | auto |
| `linkText` | Eigene Beschriftung, leer für Übersetzung | leer |
| `automaticUpdates` | Regelmäßige GitHub-Prüfung und Paketvorbereitung | aus |

Bei `auto` erhalten deutsche Sprachkontexte die deutsche Originaldatei. Andere Kontexte fallen auf Englisch zurück. **Das ersetzt nicht die erforderliche Landessprache.** Vor Freigabe weiterer Sprachmärkte müssen deren offizielle Motive ergänzt werden.

Anzeigeorte nach dem Speichern im Shop prüfen. Proxy-/CDN-/Shopware-Caches können ältere Seiten ausliefern. Die Lightbox öffnet sich nicht ungefragt und blockiert keinen Einkauf.

## GitHub-Updates im Shopware-Backend

Die feste Updatequelle ist dieses öffentliche Repository. Automatische Dateiveränderungen bleiben zunächst aus und müssen bewusst aktiviert werden.

Bei aktivierter Prüfung fragt ein Scheduled Task ungefähr alle sechs Stunden ein stabiles Release ab. Ein neueres Paket wird nur nach Prüfung von Metadaten, Prüfsumme, Plugin-Identität und ZIP-Struktur vorbereitet. Shopwares Plugin-Liste wird aktualisiert; das Backend bietet dann das native Plugin-Update an. Download ersetzt keine Lifecycle-Schritte oder Migrationen.

Zusätzlich existiert eine authentifizierte Admin-API-Aktion:

```text
POST /api/_action/mgd-eu-label/update/check
Berechtigung: system_config:update
```

Das ist keine öffentliche Shop-URL und kein eigenständiger visueller Update-Assistent. Es braucht eine gültige Admin-Sitzung oder passende Integration. Die regelmäßige Prüfung wird über die normale Backend-Konfiguration aktiviert; das eigentliche Update klickst du in **Meine Erweiterungen** an.

Vor Updates sichern, Wartungsbedarf klären, natives Update ausführen, Cache/Assets/Theme erneuern und Funktionen prüfen. Ein vorbereitetes ZIP ist noch kein abgeschlossenes Shopware-Update.

### Sicherheitsprinzipien

- Feste HTTPS-Quelle, keine frei eingebbaren Download-URLs.
- Größenlimit und SHA-256-Abgleich des GitHub-Asset-Digests.
- Prüfung von Archivpfaden, Symlinks, Plugin-Klasse und Version.
- Geänderte PHP-, Shopware- oder andere Composer-Abhängigkeitsdeklarationen werden vor dem Dateiaustausch abgelehnt; solche Versionswechsel brauchen eine manuelle Installation mit Shopwares Anforderungsprüfung.
- Private Arbeitsverzeichnisse, Prozesssperre und Rückfall bei Austauschfehlern.
- Keine GitHub-Abfrage im normalen Storefront-Aufruf; keine Shopinhalte in Updateanfragen.

Eine Prüfsumme bestätigt das veröffentlichte Asset, nicht die Vertrauenswürdigkeit eines kompromittierten Maintainerkontos. Accounts, Review und Releases absichern. Siehe [Update-System](docs/wiki/GitHub-Update-System.md).

## Entwicklung und Tests

```bash
composer validate --no-check-publish
php tests/run.php
find src tests scripts -name '*.php' -exec php -l {} \;
php scripts/build-release.php
```

Der Paketbau schreibt nach `dist/` (Git-ignoriert). Im ZIP liegen nur Laufzeitdateien und notwendige Lizenz-/Quellenhinweise, keine Tests, Zugangsdaten oder Git-Dateien. Der Composer-Versionshinweis ist bei ZIP-Plugins erwartbar; die explizite Version wird für Shopwares Updates benötigt. Daher ohne `--strict` prüfen und den erwarteten Versionshinweis von echten Schemafehlern unterscheiden. [Releaseprozess](docs/wiki/Releaseprozess.md).

Referenztests ersetzen keine Shopware-Installation. Theme, Admin-Lifecycle, Queue und Updatevorbereitung separat prüfen. Keine pauschale WCAG-, Rechts- oder Checkout-Zertifizierung.

## Datenschutz

Grafik und Lightbox kommen vom eigenen Server. Keine eigenen Cookies, Besucherkennungen, Kundenprofile, Bestell- oder Zahlungsdaten werden benötigt.

Nur bei einer Updateprüfung verbindet sich der Shop-Server mit GitHub. Dabei sieht GitHub technisch bedingt Server-IP und Request-Metadaten. Der EU-Informationslink wird erst beim Anklicken aufgerufen. Diese externen Verbindungen gehören ins Betriebskonzept.

## Erweiterbarkeit und Lizenz

Ein künftiges GARAN-Modul braucht getrennte Produktdaten wie Hersteller, Modellkennung, Dauer und Garantiebedingungen sowie die Prüfung der konkreten Garantie-Voraussetzungen. Eine Zahl am Produkt reicht nicht. Ein Elektrogeräte-Rückgabe-Modul braucht eigenständige Pflichtentexte und Anzeigeorte. Siehe [Architektur](docs/wiki/Entwicklerarchitektur.md).

Eigener Code: **GPL-2.0-or-later**, © 2026 Michael Gahn DESIGN, [LICENSE](LICENSE). EU-Grafiken werden nicht als eigene Gestaltung beansprucht; ihre Herkunft und unveränderte Nutzung sind [gesondert dokumentiert](docs/wiki/Originaldateien-und-Quellen.md). Die Code-Lizenz hebt Vorgaben für amtliche Motive nicht auf.

[Michael-Gahn.de](https://Michael-Gahn.de) · [GitHub](https://github.com/MichaelGahnDESIGN) · [Issues](https://github.com/MichaelGahnDESIGN/MGD_EU-Label_Shopware/issues)
