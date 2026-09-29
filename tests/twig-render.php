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
