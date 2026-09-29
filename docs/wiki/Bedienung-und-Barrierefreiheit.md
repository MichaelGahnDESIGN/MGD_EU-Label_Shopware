# Bedienung und Barrierefreiheit

Der Auslöser ist ein verständlich beschrifteter Link. Mit JavaScript öffnet sich ein nativer HTML-Dialog. Ohne JavaScript bleibt das vollständige Originalmotiv über das Linkziel erreichbar.

## Tastatur

Mit Tab den Link erreichen und Enter drücken. Im geöffneten Dialog müssen Schließen und Informationslink erreichbar sein. Escape schließt den Dialog; der Fokus kehrt zum auslösenden Link zurück. Der Hintergrund darf während des modalen Dialogs nicht normal bedienbar sein.

Native Dialogfunktionen helfen bei Fokus und Modalität. Ein Theme kann sie trotzdem durch überschreibende Stile oder Skripte beeinträchtigen; deshalb im konkreten Shop testen.

## Lesbarkeit

Das Motiv bleibt unverändert. Keine Filter, erzwungenen Footer-Schriftfarben oder beschnittenen Container verwenden. Die Dialogfläche muss scrollen können, wenn das gesamte Motiv höher als der Viewport ist. Browserzoom und direktes Öffnen der Originaldatei erlauben Vergrößerung.

SVG-Originale enthalten teilweise in Pfade umgewandelte Buchstaben. Deshalb nicht behaupten, dass der Bildtext automatisch als Screenreader-Text verfügbar sei. Unter der Grafik lässt sich eine ergänzende lesbare Zusammenfassung öffnen; sie ist **kein wortgetreues Volltranskript** und ersetzt das Originalmotiv nicht. Alternativtext und EU-Informationslink ergänzen sie. Diese Grenze bei der konkreten Zugänglichkeitsprüfung beachten.

## Prüfliste

- 390 Pixel und Desktop prüfen, ohne horizontalen Seitenüberlauf.
- 200 % Zoom prüfen; Schließen darf nicht unerreichbar werden.
- Tab, Shift+Tab, Enter und Escape testen.
- Mehrere Auslöser nacheinander öffnen; keine doppelten Dialog-IDs.
- CSS-Kontrast und Fokusmarkierung mit dem aktiven Theme prüfen.
- Altersabfrage, Cookiebanner und andere Dialoge auf gegenseitige Überlagerung prüfen.

Es wird keine vollständige WCAG-Konformität des Shops zugesagt. Insbesondere rechtlicher Informationsinhalt und weitere Shop-Komponenten brauchen ihre eigene Prüfung.
