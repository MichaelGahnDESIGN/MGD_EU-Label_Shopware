<?php declare(strict_types=1);

// Zusätzlicher echter Twig-Rendercheck, wenn die vorhandene Twig-Laufzeit explizit übergeben wird.
$autoload = $argv[1] ?? dirname(__DIR__) . '/vendor/autoload.php';
if (!is_file($autoload)) { fwrite(STDERR, "Twig-Autoloadpfad als erstes Argument übergeben.\n"); exit(2); }
require $autoload;
require dirname(__DIR__) . '/src/Configuration/NoticeConfig.php';

function expectRender(bool $condition, string $message): void
{
    if (!$condition) { throw new RuntimeException($message); }
}

$root = dirname(__DIR__) . '/src/Resources/views/storefront/';
$standardTwig = static fn(string $source): string => str_replace(['sw_extends', 'sw_include'], ['extends', 'include'], $source);
$loader = new Twig\Loader\ArrayLoader([
    // Die native 6.7-Struktur: CMS ersetzt base_head; Content-Meta erbt das allgemeine Meta.
    '@Storefront/storefront/base.html.twig' => '<html>{% block base_head %}BASE{% endblock %}<body>{% block base_main_inner %}CHECKOUT{% endblock %}</body></html>',
    '@Storefront/storefront/layout/meta.html.twig' => '<head>{% block layout_head_stylesheet %}<link href="native.css">{% endblock %}{% if not feature("v6.8.0.0") %}{{ block("layout_head_javascript_assets") }}{% endif %}{% if feature("v6.8.0.0") %}{% block layout_head_javascript_assets %}<script src="native.js"></script>{% endblock %}{% endif %}</head>',
    'cms-meta' => '{% extends "plugin-meta" %}',
    'cms-page' => '{% extends "@Storefront/storefront/base.html.twig" %}{% block base_head %}{% include "cms-meta" %}{% endblock %}',
    'plugin-meta' => $standardTwig(file_get_contents($root . 'layout/meta.html.twig')),
    'plugin-base' => $standardTwig(file_get_contents($root . 'base.html.twig')),
    '@MgdEuLabel/storefront/component/mgd-eu-label/link.html.twig' => $standardTwig(file_get_contents($root . 'component/mgd-eu-label/link.html.twig')),
]);
$twig = new Twig\Environment($loader, ['autoescape' => 'html']);
$twig->addFunction(new Twig\TwigFunction('feature', static fn(string $name): bool => false));
$twig->addFunction(new Twig\TwigFunction('asset', static fn(string $path): string => '/' . $path));
$twig->addFunction(new Twig\TwigFunction('mgd_eu_notice', static fn(array $context): array => $context));
$normalizer = new Mgd\EuLabel\Configuration\NoticeConfig();
foreach ([true, false] as $enabled) {
    $html = $twig->render('cms-page', ['context' => $normalizer->normalize(['enabled' => $enabled], 'de-DE')]);
    expectRender(substr_count($html, 'bundles/mgdeulabel/notice.css') === (int) $enabled, 'CSS fehlt/dupliziert bei CMS-eigenem base_head');
    expectRender(substr_count($html, 'bundles/mgdeulabel/notice.js') === (int) $enabled, 'JS fehlt/dupliziert im nativen Legacy-Scriptpfad');
    expectRender(str_contains($html, 'native.css') && str_contains($html, 'native.js'), 'Native Theme-Assets gingen verloren');
}
foreach (['frontend.checkout.cart.page', 'frontend.checkout.confirm.page', 'frontend.checkout.register.page', 'frontend.checkout.finish.page', 'frontend.account.register.page'] as $route) {
    $attributes = new class($route) {
        public function __construct(private readonly string $route) {}
        public function get(string $key): ?string { return $key === '_route' ? $this->route : null; }
    };
    $context = ['context' => $normalizer->normalize(['checkoutLink' => true], 'de-DE'), 'controllerName' => 'Register', 'app' => ['request' => ['attributes' => $attributes]]];
    $html = $twig->load('plugin-base')->renderBlock('base_main_inner', $context);
    expectRender(str_contains($html, 'CHECKOUT'), 'Native Checkout-Inhalte gingen verloren');
    expectRender(str_contains($html, 'data-mgd-eu-label-open') === str_starts_with($route, 'frontend.checkout.'), 'Checkout-Link für Route falsch: ' . $route);
}
fwrite(STDOUT, "PASS Twig-Render: CMS-Assets, Legacy-Scriptpfad, native Inhalte und vier Checkout-Routen\n");

// Native Blockgrenzen nach Shopware 6.7.15.1; die Plugin-Templates selbst werden vollständig gerendert.
$loader->setTemplate('@Storefront/storefront/element/cms-element-product-description-reviews.html.twig', '<ul>{% block element_product_description_reviews_tabs_navigation_review %}<li>Bewertungen</li>{% endblock %}</ul><div>{% block element_product_description_reviews_tabs_content_review %}Bewertungen-Inhalt{% endblock %}</div>');
$tabSource = $standardTwig(file_get_contents($root . 'element/cms-element-product-description-reviews.html.twig'));
$tabSource = str_replace("{% sw_icon 'arrow-medium-right' style {'pack':'solid'} %}", '', $tabSource);
$loader->setTemplate('plugin-tabs', $tabSource);
$loader->setTemplate('@Storefront/storefront/utilities/offcanvas.html.twig', '<div class="offcanvas-content">{% block utilities_offcanvas_content %}{% endblock %}</div>');
foreach (['product-content', 'text', 'dialog'] as $part) {
    $loader->setTemplate('@MgdEuLabel/storefront/component/mgd-eu-label/' . $part . '.html.twig', $standardTwig(file_get_contents($root . 'component/mgd-eu-label/' . $part . '.html.twig')));
}
foreach ([false, true] as $enabled) {
    foreach ([false, true] as $productTab) {
        $notice = $normalizer->normalize(['enabled' => $enabled, 'productTab' => $productTab], 'de-DE');
        $html = $twig->render('plugin-tabs', ['context' => $notice, 'element' => ['id' => 'test-element']]);
        expectRender(str_contains($html, 'mgd-guarantee-tab-test-element') === ($enabled && $productTab), 'Produktreiter-Schalter falsch');
        expectRender(str_contains($html, 'legal-guarantee-de.svg') === ($enabled && $productTab), 'Produktgrafik-Schalter falsch');
        expectRender(str_contains($html, 'Bewertungen-Inhalt'), 'Vorhandene Produktinhalte gingen verloren');
    }
}
$notice = $normalizer->normalize(['language' => 'en', 'productTab' => true, 'linkText' => '<script>bad</script>'], 'de-DE');
$html = $twig->render('plugin-tabs', ['context' => $notice, 'element' => ['id' => 'test-element']]);
expectRender(str_contains($html, 'legal-guarantee-en.svg') && !str_contains($html, '<script>'), 'Sprache oder Escaping falsch');
if (isset($argv[2])) {
    $notice = $normalizer->normalize([], 'de-DE');
    $html = '<!doctype html><html lang="de"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Gewährleistung Test</title><style>body{font:16px system-ui;margin:24px} dialog{display:block}</style><link rel="stylesheet" href="/notice.css"></head><body><footer>';
    $html .= $twig->render('@MgdEuLabel/storefront/component/mgd-eu-label/link.html.twig', ['notice' => $notice]);
    $html .= '</footer>' . $twig->render('@MgdEuLabel/storefront/component/mgd-eu-label/dialog.html.twig', ['notice' => $notice]);
    $html .= '<script src="/notice.js" defer></script></body></html>';
    file_put_contents($argv[2], $html);
}
fwrite(STDOUT, "PASS Twig-Render: Produktreiter, Ausschalten, Elterninhalte, Englisch und Escaping\n");
