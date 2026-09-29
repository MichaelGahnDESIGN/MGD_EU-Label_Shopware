<?php declare(strict_types=1);

namespace Mgd\EuLabel\Configuration;

/** Trennt robuste Konfigurationswerte von Darstellung und Shopware-Datenzugriff. */
final class NoticeConfig
{
    /** @param array<string, mixed> $values @return array<string, mixed> */
    public function normalize(array $values, string $locale): array
    {
        $language = $values['language'] ?? 'auto';
        if (!in_array($language, ['de', 'en'], true)) {
            $language = str_starts_with(strtolower($locale), 'de') ? 'de' : 'en';
        }
        $result = ['language' => $language];
        foreach (['enabled' => true, 'footerLink' => true, 'headerLink' => false, 'checkoutLink' => false, 'inlineNotice' => false] as $key => $default) {
            $result[$key] = filter_var($values[$key] ?? $default, FILTER_VALIDATE_BOOL);
        }
        $text = is_string($values['linkText'] ?? null) ? trim($values['linkText']) : '';
        $result['linkText'] = $text !== '' ? mb_substr($text, 0, 200) : ($language === 'de' ? 'Gesetzliche Gewährleistung' : 'Legal guarantee');
        $result['closeText'] = $language === 'de' ? 'Schließen' : 'Close';
        $result['imageAlt'] = $language === 'de' ? 'Offizielle EU-Information zur gesetzlichen Gewährleistung. Eine ergänzende Zusammenfassung steht unterhalb der Grafik.' : 'Official EU legal guarantee notice. An additional summary follows below the image.';
        $result['detailsText'] = $language === 'de' ? 'Ergänzende Zusammenfassung' : 'Additional summary';
        return $result;
    }
}
