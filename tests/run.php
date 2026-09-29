<?php declare(strict_types=1);

// Diese eigenständigen Verhaltenstests benötigen nur PHP mit ext-zip.
spl_autoload_register(static function (string $class): void {
    $prefix = 'Mgd\\EuLabel\\';
    if (str_starts_with($class, $prefix)) {
        $path = __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (is_file($path)) { require $path; }
    }
});

function check(bool $condition, string $message): void
{
    if (!$condition) { throw new RuntimeException($message); }
}

function rejects(callable $operation, string $message): void
{
    try { $operation(); } catch (RuntimeException|InvalidArgumentException $error) { return; }
    throw new RuntimeException($message);
}

function manifest(string $version = '0.2.0', ?array $requirements = ['php' => '>=8.2', 'ext-zip' => '*', 'ext-curl' => '*', 'ext-mbstring' => '*', 'shopware/core' => '~6.7.0', 'shopware/storefront' => '~6.7.0']): string
{
    $manifest = ['name' => 'mgd/eu-label-shopware', 'type' => 'shopware-platform-plugin', 'version' => $version,
        'autoload' => ['psr-4' => ['Mgd\\EuLabel\\' => 'src/']],
        'extra' => ['shopware-plugin-class' => 'Mgd\\EuLabel\\MgdEuLabel']];
    if ($requirements !== null) { $manifest['require'] = $requirements; }
    return json_encode($manifest, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
}

function archive(array $entries): string
{
    $path = tempnam(sys_get_temp_dir(), 'mgd-eu-test-');
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::OVERWRITE);
    foreach ($entries as $name => $body) { $zip->addFromString($name, $body); }
    $zip->close();
    return $path;
}

$tests = [];
$tests['Konfiguration: sichere Standardwerte und sprachliche Rückfälle'] = static function (): void {
    check(class_exists(Mgd\EuLabel\Configuration\NoticeConfig::class), 'Konfigurations-Normalizer fehlt');
    $config = new Mgd\EuLabel\Configuration\NoticeConfig();
    $de = $config->normalize([], 'de-DE');
    check($de['enabled'] && $de['footerLink'] && !$de['headerLink'] && !$de['checkoutLink'] && !$de['inlineNotice'], 'Anzeige-Standardwerte');
    check($de['language'] === 'de' && $de['linkText'] === 'Gesetzliche Gewährleistung', 'Deutsche Sprache');
    check($config->normalize([], 'fr-FR')['language'] === 'en', 'Unbekannte Sprache erhält EN');
    check($config->normalize(['language' => 'de', 'enabled' => 'false'], 'en-US')['enabled'] === false, 'Text false darf nicht aktivieren');
    check($config->normalize(['language' => 'bad', 'linkText' => '  Test  '], 'en-US')['linkText'] === 'Test', 'Text normalisiert');
};

$tests['Release: ausschließlich stabile höhere Version aus festem Repository'] = static function (): void {
    check(class_exists(Mgd\EuLabel\Update\ReleaseMetadata::class), 'Release-Prüfung fehlt');
    $release = ['tag_name' => 'v0.2.0', 'draft' => false, 'prerelease' => false, 'assets' => [[
        'name' => 'MgdEuLabel.zip', 'size' => 1000, 'digest' => 'sha256:' . str_repeat('a', 64),
        'browser_download_url' => 'https://github.com/MichaelGahnDESIGN/MGD_EU-Label_Shopware/releases/download/v0.2.0/MgdEuLabel.zip',
    ]]];
    $parse = static fn(array $value) => Mgd\EuLabel\Update\ReleaseMetadata::fromGitHub($value, '0.1.0');
    check($parse($release)->version === '0.2.0', 'Stabile neuere Version akzeptieren');
    foreach ([['tag_name' => 'v0.1.0'], ['tag_name' => 'v0.2.0-beta.1'], ['draft' => true], ['prerelease' => true]] as $change) {
        rejects(static fn() => $parse(array_replace($release, $change)), 'Ungültiges Release abweisen');
    }
    foreach ([['digest' => null], ['size' => 30000000], ['browser_download_url' => 'https://evil.example/MgdEuLabel.zip']] as $change) {
        $bad = $release; $bad['assets'][0] = array_replace($bad['assets'][0], $change);
        rejects(static fn() => $parse($bad), 'Ungültiges Asset abweisen');
    }
};

$tests['ZIP: echte Archive, Traversal, Symlinks, falsche Identität und Bomben'] = static function (): void {
    check(class_exists(Mgd\EuLabel\Update\ArchiveValidator::class), 'Archiv-Prüfung fehlt');
    $validator = new Mgd\EuLabel\Update\ArchiveValidator();
    $good = ['MgdEuLabel/composer.json' => manifest(), 'MgdEuLabel/src/MgdEuLabel.php' => '<?php // Test'];
    $path = archive($good);
    try { check(count($validator->validate($path, '0.2.0')) === 2, 'Echtes korrektes ZIP akzeptieren'); }
    finally { unlink($path); }
    foreach (['../escape.php', '/absolute.php', 'MgdEuLabel/../escape.php', 'MgdEuLabel/src\\evil.php', 'Other/file.php', 'MgdEuLabel/.env', 'MgdEuLabel/src/config.env'] as $name) {
        $path = archive($good + [$name => 'bad']);
        try { rejects(static fn() => $validator->validate($path, '0.2.0'), 'Gefährlicher Pfad zugelassen: ' . $name); }
        finally { unlink($path); }
    }
    foreach ([str_replace('mgd/eu-label-shopware', 'evil/plugin', manifest()), manifest('0.3.0')] as $body) {
        $path = archive(array_replace($good, ['MgdEuLabel/composer.json' => $body]));
        try { rejects(static fn() => $validator->validate($path, '0.2.0'), 'Falsche Plugin-Identität zugelassen'); }
        finally { unlink($path); }
    }
    $path = archive($good + ['MgdEuLabel/src/link.php' => 'target']);
    $zip = new ZipArchive(); $zip->open($path); $zip->setExternalAttributesName('MgdEuLabel/src/link.php', ZipArchive::OPSYS_UNIX, 0120777 << 16); $zip->close();
    try { rejects(static fn() => $validator->validate($path, '0.2.0'), 'Symlink zugelassen'); }
    finally { unlink($path); }
    $path = archive($good + ['MgdEuLabel/src/bomb.php' => str_repeat('0', 2000000)]);
    try { rejects(static fn() => $validator->validate($path, '0.2.0'), 'Kompressionsbombe zugelassen'); }
    finally { unlink($path); }
};

$tests['Installer: atomarer Austausch und Rückfall bei Refresh-Fehler'] = static function (): void {
    check(class_exists(Mgd\EuLabel\Update\AtomicInstaller::class), 'Installer fehlt');
    $root = sys_get_temp_dir() . '/mgd-eu-install-' . bin2hex(random_bytes(8));
    mkdir($root . '/custom/plugins/MgdEuLabel', 0700, true);
    mkdir($root . '/public', 0700);
    file_put_contents($root . '/custom/plugins/MgdEuLabel/composer.json', manifest('0.1.0'));
    $path = archive(['MgdEuLabel/composer.json' => manifest(), 'MgdEuLabel/src/MgdEuLabel.php' => '<?php // Test']);
    $installer = new Mgd\EuLabel\Update\AtomicInstaller(new Mgd\EuLabel\Update\ArchiveValidator());
    try {
        rejects(static fn() => $installer->install($path, '0.2.0', $root . '/custom/plugins/MgdEuLabel', $root . '/var/mgd-eu-label', static function (): void { throw new RuntimeException('Refresh fehlgeschlagen'); }), 'Refresh-Fehler verschluckt');
        check(json_decode(file_get_contents($root . '/custom/plugins/MgdEuLabel/composer.json'), true)['version'] === '0.1.0', 'Rückfall stellt Original wieder her');
        $refreshCount = 0;
        $installer->install($path, '0.2.0', $root . '/custom/plugins/MgdEuLabel', $root . '/var/mgd-eu-label', static function () use (&$refreshCount): void { ++$refreshCount; });
        check($refreshCount === 1, 'Refresh nach Austausch');
        check(json_decode(file_get_contents($root . '/custom/plugins/MgdEuLabel/composer.json'), true)['version'] === '0.2.0', 'Neue Version installiert');
        rejects(static fn() => $installer->install($path, '0.2.0', $root . '/custom/plugins/MgdEuLabel', $root . '/public/staging', static function (): void {}), 'Staging im Webroot zugelassen');
    } finally {
        unlink($path);
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) { $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname()); }
        rmdir($root);
    }
};

$tests['Storefront: originale SVG, progressive Links und native Dialog-Steuerung'] = static function (): void {
    $base = __DIR__ . '/../src/Resources/';
    check(is_file($base . 'views/storefront/base.html.twig'), 'Storefront-Erweiterung fehlt');
    $js = file_get_contents($base . 'public/notice.js');
    check(str_contains($js, '.showModal()') && str_contains($js, '.focus('), 'Native Dialog- und Fokussteuerung');
    $link = file_get_contents($base . 'views/storefront/component/mgd-eu-label/link.html.twig');
    check(str_contains($link, 'href="{{') && str_contains($link, '.svg'), 'Direktes SVG-Fallbackziel');
    $baseTwig = file_get_contents($base . 'views/storefront/base.html.twig');
    check(str_contains($baseTwig, 'base_body_inner') && str_contains($baseTwig, 'parent()'), 'Dialog außerhalb Footer mit parent');
    $footer = file_get_contents($base . 'views/storefront/layout/footer/footer.html.twig');
    check(str_contains($footer, 'layout_footer_service_menu_content') && str_contains($footer, 'parent()'), 'Native Footerlinks erhalten');
    // In CI liegt das große Quellenarchiv bewusst nicht im Repository: feste Original-Hashes prüfen.
    $hashes = ['de' => 'fd39364dbe42fa775ff55fb9b7aa80c377a5d04219929fef86a8522eed486b1a', 'en' => 'd1b4293b75637022b582b3025f789d292e1c845660a0460d855bbe523b9763f7'];
    $zip = null;
    if (is_file(__DIR__ . '/../artifacts/eu-notice-svg.zip')) { $zip = new ZipArchive(); $zip->open(__DIR__ . '/../artifacts/eu-notice-svg.zip'); }
    foreach (['de' => 'DE', 'en' => 'EN'] as $language => $original) {
        $actual = hash_file('sha256', $base . 'public/notices/legal-guarantee-' . $language . '.svg');
        check($actual === $hashes[$language], 'EU-Original-Hash: ' . $language);
        if ($zip !== null) { check($actual === hash('sha256', $zip->getFromName('Legal guarantee_notice ' . $original . '.svg')), 'EU-Grafik bytegleich: ' . $language); }
    }
    if ($zip !== null) { $zip->close(); }
};

$tests['Download: HTTPS-Hostgrenze, Digest und vollständige Größe'] = static function (): void {
    check(class_exists(Mgd\EuLabel\Update\DownloadPolicy::class), 'Download-Integritätsprüfung fehlt');
    $policy = new Mgd\EuLabel\Update\DownloadPolicy();
    check($policy->allowedRedirect('https://release-assets.githubusercontent.com/github-production-release-asset/123/file?token=temporary'), 'GitHub Assethost erlaubt');
    foreach (['http://github.com/a', 'https://evil.example/a', 'https://github.com.evil.example/a', 'https://user:pass@github.com/a', 'https://github.com:8443/a', 'file:///tmp/a'] as $url) {
        check(!$policy->allowedRedirect($url), 'Unsicheres Redirectziel zugelassen: ' . $url);
    }
    $release = Mgd\EuLabel\Update\ReleaseMetadata::fromGitHub(['tag_name' => 'v0.2.0', 'draft' => false, 'prerelease' => false,
        'assets' => [['name' => 'MgdEuLabel.zip', 'size' => 4, 'digest' => 'sha256:' . hash('sha256', 'good'), 'browser_download_url' => 'https://github.com/MichaelGahnDESIGN/MGD_EU-Label_Shopware/releases/download/v0.2.0/MgdEuLabel.zip']]], '0.1.0');
    $file = tempnam(sys_get_temp_dir(), 'mgd-eu-digest-');
    try {
        file_put_contents($file, 'good'); $policy->verify($file, $release);
        file_put_contents($file, 'evil'); rejects(static fn() => $policy->verify($file, $release), 'Digestfehler nicht erkannt');
        file_put_contents($file, 'goo'); rejects(static fn() => $policy->verify($file, $release), 'Abbruch nicht erkannt');
    } finally { unlink($file); }
};

$tests['Integration: feste Adminroute, Standard aus und sechs Stunden'] = static function (): void {
    $base = __DIR__ . '/../src/';
    check(is_file($base . 'Controller/UpdateController.php'), 'Manuelle Adminroute fehlt');
    $controller = file_get_contents($base . 'Controller/UpdateController.php');
    check(str_contains($controller, 'ApiRouteScope::ID') && str_contains($controller, "'system_config:update'") && str_contains($controller, "methods: ['POST']"), 'Admin-Authentifizierung und ACL fehlen');
    $config = simplexml_load_file($base . 'Resources/config/config.xml');
    $auto = $config->xpath('//input-field[name="automaticUpdates"]/defaultValue');
    check((string) $auto[0] === 'false', 'Automatik muss standardmäßig aus sein');
    check(str_contains(file_get_contents($base . 'ScheduledTask/UpdateTask.php'), '21600'), 'Sechs-Stunden-Intervall');
};

$tests['Release-Paket: feste Root, Allowlist und reproduzierbare Integritätsdatei'] = static function (): void {
    check(is_file(__DIR__ . '/../scripts/build-release.php'), 'Release-Buildskript fehlt');
    $destination = sys_get_temp_dir() . '/mgd-eu-release-' . bin2hex(random_bytes(8));
    $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/../scripts/build-release.php') . ' ' . escapeshellarg($destination);
    exec($command, $output, $status);
    check($status === 0, 'Release-Build fehlgeschlagen: ' . implode("\n", $output));
    try {
        $zip = $destination . '/MgdEuLabel.zip';
        $version = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true)['version'];
        $files = (new Mgd\EuLabel\Update\ArchiveValidator())->validate($zip, $version);
        check(in_array('MgdEuLabel/src/Resources/public/notices/legal-guarantee-de.svg', $files, true), 'DE SVG im ZIP fehlt');
        check(str_contains(file_get_contents($destination . '/MgdEuLabel.zip.sha256'), hash_file('sha256', $zip)), 'Digestdatei stimmt nicht');
        foreach ($files as $file) { check(!str_contains($file, '/tests/') && !str_contains($file, '/docs/') && !str_contains($file, '/artifacts/'), 'Entwicklungsdateien im ZIP'); }
    } finally {
        foreach (['MgdEuLabel.zip', 'MgdEuLabel.zip.sha256'] as $name) { if (is_file($destination . '/' . $name)) { unlink($destination . '/' . $name); } }
        if (is_dir($destination)) { rmdir($destination); }
    }
};

$tests['Installer: geänderte Runtime-Anforderungen dürfen niemals aktive Dateien austauschen'] = static function (): void {
    $root = sys_get_temp_dir() . '/mgd-eu-require-' . bin2hex(random_bytes(8));
    mkdir($root . '/custom/plugins/MgdEuLabel', 0700, true);
    $original = manifest('0.1.0');
    file_put_contents($root . '/custom/plugins/MgdEuLabel/composer.json', $original);
    $installer = new Mgd\EuLabel\Update\AtomicInstaller(new Mgd\EuLabel\Update\ArchiveValidator());
    $require = json_decode($original, true)['require'];
    $removed = $require; unset($removed['ext-zip']);
    $cases = [null, [], array_replace($require, ['php' => '>=8.4']), array_replace($require, ['shopware/core' => '~6.8.0', 'shopware/storefront' => '~6.8.0']), $removed, $require + ['vendor/new-package' => '^1.0']];
    try {
        foreach ($cases as $requirements) {
            $path = archive(['MgdEuLabel/composer.json' => manifest('0.2.0', $requirements), 'MgdEuLabel/src/MgdEuLabel.php' => '<?php // Test']);
            $refreshes = 0;
            try {
                rejects(static fn() => $installer->install($path, '0.2.0', $root . '/custom/plugins/MgdEuLabel', $root . '/var/mgd-eu-label', static function () use (&$refreshes): void { ++$refreshes; }), 'Geänderte Runtime-Anforderungen wurden zugelassen');
                check(file_get_contents($root . '/custom/plugins/MgdEuLabel/composer.json') === $original, 'Aktive Dateien wurden vor Kompatibilitätsprüfung verändert');
                check($refreshes === 0, 'Refresh trotz abgewiesener Runtime-Anforderungen');
                check(glob($root . '/var/mgd-eu-label/backup-*') === [], 'Sicherung/Rename trotz abgewiesener Runtime-Anforderungen');
            } finally { unlink($path); }
        }
        // Gleiche Anforderungen bleiben bei anderer JSON-Schlüsselreihenfolge kompatibel.
        $path = archive(['MgdEuLabel/composer.json' => manifest('0.2.0', array_reverse($require, true)), 'MgdEuLabel/src/MgdEuLabel.php' => '<?php // Test']);
        try { $installer->install($path, '0.2.0', $root . '/custom/plugins/MgdEuLabel', $root . '/var/mgd-eu-label', static function (): void {}); }
        finally { unlink($path); }
        check(json_decode(file_get_contents($root . '/custom/plugins/MgdEuLabel/composer.json'), true)['version'] === '0.2.0', 'Identische Anforderungen wurden abgewiesen');
    } finally {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) { $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname()); }
        rmdir($root);
    }
};

$failed = 0;
foreach ($tests as $name => $test) {
    try { $test(); fwrite(STDOUT, 'PASS ' . $name . PHP_EOL); }
    catch (Throwable $error) { ++$failed; fwrite(STDERR, 'FAIL ' . $name . ': ' . $error->getMessage() . PHP_EOL); }
}
exit($failed > 0 ? 1 : 0);
