# Installation und Updates

## Vorbereitung

Prüfe Shopware 6.7, passende PHP-Version und ZIP-Erweiterung. Sichere Shopdateien, Datenbank und Konfiguration außerhalb des öffentlichen Webverzeichnisses. Halte fest, wie Dateien und Datenbank wiederhergestellt werden. Nutze bei unbekannten Themes zuerst Staging.

Lade ausschließlich das Release-Asset `MgdEuLabel.zip` aus dem vorgesehenen Repository. GitHubs „Source code“-Archiv ist ein Entwicklerarchiv, kein zugesichertes Shopware-Installationspaket. Das Plugin-Paket besitzt die Wurzel `MgdEuLabel/composer.json`.

## Administration

In **Erweiterungen → Meine Erweiterungen** hochladen, installieren, aktivieren und Konfiguration öffnen. Nach Aktivierung öffentliche Assets veröffentlichen, Cache leeren und aktives Theme kompilieren. Der technische Pluginname lautet `MgdEuLabel`; der sichtbare Titel ist „MGD EU Label Plugin“.

## CLI

Ordner nach `custom/plugins/MgdEuLabel` kopieren, im Shopware-Hauptverzeichnis:

```bash
bin/console plugin:refresh
bin/console plugin:install --activate MgdEuLabel
bin/console assets:install
bin/console cache:clear
bin/console theme:compile
```

Nach einem Dateipaketupdate:

```bash
bin/console plugin:refresh
bin/console plugin:update MgdEuLabel
bin/console assets:install
bin/console cache:clear
bin/console theme:compile
```

## Abnahme

Öffne Startseite und Kategorie. Prüfe Footer-Link, Dialog, gesamte Grafik, Informationslink, Escape und Fokus-Rückgabe. Wiederhole in einem schmalen mobilen Viewport. Bestehende Footer-Links und Header-Navigation dürfen nicht verschwinden. Zusätzliche Anzeigeorte gesondert prüfen.

## Deaktivierung und Deinstallation

Deaktivierung entfernt die Storefront-Erweiterung, ersetzt aber keine ggf. erforderliche gesetzliche Information. Vor dem Deaktivieren eine andere geeignete Anzeige organisieren. Deinstallation und eventuelle Entfernung der SystemConfig-Daten bewusst im Shopware-Dialog wählen. Das Plugin führt keine eigenen Kunden- oder Produkttabellen ein.

Nach Rückbau Assets, Cache und Theme erneuern; kein hängen gebliebenes Linkziel darf bleiben. Ein manuell eingefügter Theme-Link wird nicht automatisch entfernt.

## Fertiges Upload-Paket ab 1.2.1

`MgdEuLabel.zip` enthält sämtliche fertigen JavaScript-, CSS- und Grafikdateien. In Shopware hochladen und den regulären Installations-/Updatevorgang abschließen; ein eigener Administration- oder Theme-Build ist für dieses Plugin nicht erforderlich. Shopwares normaler Updateablauf veröffentlicht die Assets automatisch. Anschließend die geöffnete Administration bzw. Shopseite neu laden.

Der GitHub-Updater verwendet dasselbe vollständige ZIP. Nach der Vorbereitung weiterhin das native Update in Shopware ausführen; das bloße Herunterladen ist noch kein abgeschlossener Updatevorgang. Nur bei serverseitigen Sonderkonfigurationen (z. B. deaktivierter Asset-Veröffentlichung oder vorgeschaltetem CDN) kann ein zusätzlicher betrieblicher Schritt erforderlich sein.
