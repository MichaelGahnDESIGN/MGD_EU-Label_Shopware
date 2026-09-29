# Erweiterungsmodell

Das Plugin heißt bewusst „EU Label Plugin“, obwohl die erste Version ausschließlich den allgemeinen Gewährleistungshinweis enthält. Erweiterbarkeit bedeutet klare Modulgrenzen, nicht bereits fertige Garantie- oder Rücknahmefunktionen.

## GARAN-Modul – nächste eigenständige Ausbaustufe

Produktdaten sollten pro Artikel/Variante gepflegt werden: Aktivierung, Hersteller/Marke, Modellkennung, Garantiedauer, Garantieerklärung und Herkunft der Herstellerinformation. Die Voraussetzungen sind gesondert zu prüfen: kostenlose Herstellergarantie zur Haltbarkeit, gesamtes Produkt, mehr als zwei Jahre.

Die amtliche GARAN-Vorlage enthält feste und genau definierte veränderbare Felder. Ein beliebiges Garantie-Piktogramm oder ein aus einem Freitext extrahierter Jahreswert genügt nicht. Warenwirtschaftssynchronisation, Variantenvererbung und Änderungen an Garantiebedingungen müssen vor der Umsetzung entschieden werden.

Globale Einstellungen betreffen Position, Sprache und Darstellungsart. Produktdaten gehören dagegen in einen eigenen validierten Speicherbereich, zum Beispiel einen eindeutig benannten Custom-Field-Satz oder eine additive Entität. Vorher bestehende ERP-Felder prüfen, statt sie ungefragt zu überschreiben.

## Elektrogeräte-Rückgabe-Modul

Zunächst den konkreten Anwendungsfall klären: Rücknahmehinweis des Händlers ist nicht dasselbe wie eine WEEE-Produktkennzeichnung. Pflichten hängen unter anderem vom Händler-/Sortimentskontext ab. Ein Modul braucht eigene Quellen, Texte, Geltungsprüfung, Einstellungen und Anzeigeorte.

Nicht einfach das Gewährleistungsmotiv durch ein neues Bild ersetzen und identische Pflichten annehmen. Produktrücknahme kann eigene Kontakt-/Prozessinformationen brauchen; diese Version stellt noch keinen Rückgabeprozess bereit.

## Integrationsvertrag

Jedes Modul erhält eigene Konfigurationsschlüssel und eine eigene Präsentationskomponente. Gemeinsame Infrastruktur wie Dialog und Updateprüfung darf wiederverwendet werden. Validierung, rechtlicher Inhalt und Produktdaten bleiben getrennt.

Neue Pflichtfelder brauchen Migration, Tests, Dokumentation und einen nachvollziehbaren Rückfall. Änderungen dürfen keine falschen Garantieversprechen erzeugen und keine bestehenden Produkte pauschal als GARAN-fähig markieren.
