# GitHub-Update-System

## Quelle und Ablauf

Vertraute Quelle: `MichaelGahnDESIGN/MGD_EU-Label_Shopware`. Paketname: `MgdEuLabel.zip`. Automatische Prüfung ist standardmäßig aus. Bei Aktivierung läuft ein Scheduled Task ungefähr stündlich, sofern Shopware Tasks und Queue verarbeitet. Ein GitHub-Release löst ohne Webhook keinen sofortigen Push an Shopware aus; der Task oder die manuelle Admin-Aktion muss es abfragen. Nach dem Update auf 1.1.3 den registrierten Task-Intervallwert kontrollieren, falls die ältere Sechs-Stunden-Registrierung bestehen bleibt.

1. Stabiles Release über GitHubs API lesen.
2. Versionsnummer mit der lokal installierten Paketversion vergleichen.
3. Exaktes Release-Asset, HTTPS-URL, Größe und SHA-256-Digest prüfen.
4. Archiv in privatem Arbeitsverzeichnis prüfen: Wurzel, Pfade, Symlinks, Größen, Plugin-Identität und Version. Abhängigkeitsdeklarationen müssen zur bereits installierten Version passen.
5. Kontrolliert vorbereiten und Shopwares Plugin-Liste aktualisieren.
6. Im nativen Backend das eigentliche Update ausführen.

Fehlende oder nicht passende Sicherheitsmetadaten werden nicht durch „trotzdem installieren“ umgangen. Pre-Releases und Downgrades werden nicht automatisch vorbereitet.

Die erste Version ist bei Laufzeitanforderungen bewusst konservativ: Ändert ein Release die `require`-Deklarationen für PHP, Shopware oder andere Pakete/Erweiterungen, wird es **vor** dem Austausch aktiver Dateien abgelehnt. Auch eine möglicherweise kompatible Änderung wird nicht ohne Prüfung angenommen. Solche Releases über eine kontrollierte manuelle Shopware-Installation einspielen, damit dessen native Anforderungsprüfung und die notwendige Plattform-/Abhängigkeitsaktualisierung vor der Aktivierung stattfinden können. Der GitHub-Updater führt keine Composer-Abhängigkeitsinstallation aus.

## Manuelle Prüfung für Integrationen

```text
POST /api/_action/mgd-eu-label/update/check
ACL: system_config:update
```

Diese Admin-Aktion benötigt gültige Authentifizierung. Keine Zugangsdaten in Browserkonsolen, Dokumentation oder öffentliche Issues kopieren. Sie prüft und bereitet vor; sie ersetzt nicht den Shopware-Lifecycle. Es gibt in der ersten Ausbaustufe keinen separat gebauten visuellen GitHub-Update-Assistenten.

## Betriebsvoraussetzungen

Ausgehendes HTTPS zu `api.github.com`, `github.com` und GitHubs Release-Asset-Dienst muss erlaubt sein. Der Server benötigt beschreibbare Plugin- und private Arbeitsverzeichnisse. Der Webserver darf diese privaten Verzeichnisse nicht öffentlich ausliefern. Bei read-only Deployments bleibt manuelle Paketinstallation über die Deployment-Pipeline der passende Weg.

Updatevorbereitung ändert Dateien einer gemeinsamen Installation, nicht nur eines Verkaufskanals. `automaticUpdates` deshalb unter **„Alle Verkaufskanäle“** aktivieren. Vor Änderungen Sicherungen erstellen und Wartungs-/Queue-Strategie beachten.

Staging, Sperrdatei und Dateisicherungen liegen unter `var/mgd-eu-label` im Shopware-Projektverzeichnis, außerhalb des üblichen `public/`-Webroots. Ist dein Webroot anders konfiguriert, muss dieser Pfad ausdrücklich vor Webzugriff geschützt sein. Der Updater verweigert Symlinks für seine Arbeitsverzeichnisse.

Der Verzeichnisaustausch verwendet zwei einzelne Rename-Schritte: alte Version in die Sicherung, neue Version an den Pluginpfad. Dazwischen besteht eine kurze Umschaltlücke. Die Sperre verhindert parallele Updates, **nicht** parallele PHP-Lesezugriffe der Storefront. Vorbereitung deshalb im Wartungsfenster durchführen und Worker geordnet behandeln. Es wird kein unterbrechungsfreies Cluster-Deployment zugesagt.

## Vertrauensgrenze

Ein GitHub-Digest sichert Paketintegrität innerhalb der Releasequelle, keine unabhängige Signatur. Ein kompromittierter Maintainer kann ein schädliches Paket mit passendem Digest veröffentlichen. Zwei-Faktor-Authentifizierung, Review, minimale Workflow-Rechte und kontrollierte Releases bleiben erforderlich.

Bei GitHub-Ausfall funktioniert die Storefront weiter; nur die Updateprüfung schlägt fehl. Nicht mit immer kürzeren Wiederholungsintervallen reagieren. Logs ohne Tokens prüfen und nach Behebung erneut kontrolliert starten.
