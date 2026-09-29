# Themes und Integration

Das Plugin erweitert native Shopware-Twig-Blöcke mit `sw_extends` und erhält bestehende Inhalte über `parent()`. Es ersetzt weder Footer-Navigation noch Header oder Checkout vollständig.

Die lokalen CSS-/JavaScript-Dateien werden in den Stylesheet-/JavaScript-Blöcken von `storefront/layout/meta.html.twig` eingebunden. Das ist wichtig, weil beispielsweise CMS-Seiten `base_head` vollständig ersetzen. Der Dialog selbst bleibt außerhalb des Footers, damit kundenspezifische Footer-Farben nicht seine Lesbarkeit verändern.

## Eigene Themes

Ein Theme, das einen betroffenen Block vollständig ersetzt und keinen Elterninhalt ausgibt, kann Plugin-Inhalte unterdrücken. Prüfe die Twig-Vererbungsreihenfolge und die Sichtbarkeit von Plugin-Views. Ergänze gezielt den Plugin-Include, statt Shopware-Core-Dateien zu bearbeiten.

Die Lightbox liegt außerhalb der Footer-CSS-Kaskade. Das ist wichtig, weil viele Themes Footer-Text pauschal weiß setzen. Plugin-Stile verwenden eigene `mgd-eu-label`-Klassen und sollen globale `button`, `a`, `img` oder `.modal`-Regeln vermeiden.

## Assets und Content Security Policy

Öffentliche Bilder, CSS und JavaScript müssen nach Installation über `assets:install` bereitstehen. Bei strengem Content Security Policy-Konzept die eigenen Asset-URLs prüfen. Es sollen keine externen Skript-CDNs oder frei eingebbare Skripte notwendig sein.

## Headless und Spezial-Checkouts

Ein Store-API-/Headless-Frontend benutzt die Twig-Templates nicht. Dort muss die Anzeige separat implementiert werden. Ein individuell ersetzter Checkout braucht eine eigene Integrationsprüfung; Aktivierung einer Einstellung allein beweist dort keine Sichtbarkeit.

## Mehrsprachigkeit

Das Bild folgt dem Sprachkontext bzw. der festen Plugin-Auswahl. Individuelle Linktexte sind Konfigurationswerte, nicht automatisch pro Sprache übersetzte Rechtstexte. Für mehrere Sprachen bevorzugt Standardübersetzungen nutzen und jeden Absatzmarkt separat prüfen.
