<?php declare(strict_types=1);

use Mgd\EuLabel\Update\ArchiveValidator;

// Reproduzierbarer ZIP-Build ohne Abhängigkeit von einem installierten Shopware oder Storefront-Build.
require __DIR__ . '/../src/Update/ReleaseMetadata.php';
require __DIR__ . '/../src/Update/RequirementPolicy.php';
require __DIR__ . '/../src/Update/ArchiveValidator.php';

$root = dirname(__DIR__);
$destination = $argv[1] ?? $root . '/dist';
$manifest = json_decode((string) file_get_contents($root . '/composer.json'), true, 32, JSON_THROW_ON_ERROR);
$version = $manifest['version'] ?? '';
if (!is_string($version) || !preg_match('/^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)$/D', $version)) { throw new RuntimeException('Ungültige stabile Plugin-Version.'); }
if (!is_dir($destination) && !mkdir($destination, 0755, true)) { throw new RuntimeException('Release-Verzeichnis kann nicht erstellt werden.'); }
if (is_link($destination)) { throw new RuntimeException('Release-Ziel darf kein Symlink sein.'); }
$archivePath = $destination . '/MgdEuLabel.zip';
if (is_link($archivePath) || is_link($archivePath . '.sha256')) { throw new RuntimeException('Release-Dateien dürfen keine Symlinks sein.'); }
$temporary = tempnam($destination, '.mgd-eu-build-');
if ($temporary === false) { throw new RuntimeException('Temporäres ZIP kann nicht erstellt werden.'); }
$files = ['composer.json'];
foreach (['LICENSE', 'LICENSE.md'] as $license) { if (is_file($root . '/' . $license)) { $files[] = $license; } }
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src', FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    $relative = substr($file->getPathname(), strlen($root) + 1);
    if ($file->isLink()) { throw new RuntimeException('Symlinks sind im Release nicht erlaubt.'); }
    if ($file->isFile() && ArchiveValidator::allowedPath('MgdEuLabel/' . $relative)) { $files[] = $relative; }
}
sort($files, SORT_STRING);
$zip = new ZipArchive();
if ($zip->open($temporary, ZipArchive::OVERWRITE) !== true) { throw new RuntimeException('ZIP kann nicht erstellt werden.'); }
try {
    foreach ($files as $file) {
        $name = 'MgdEuLabel/' . $file;
        if (!$zip->addFile($root . '/' . $file, $name)) { throw new RuntimeException('Release-Datei kann nicht hinzugefügt werden.'); }
        $zip->setMtimeName($name, 946684800); // Fester Zeitstempel verhindert wechselnde Hashes ohne Inhaltsänderung.
        $zip->setExternalAttributesName($name, ZipArchive::OPSYS_UNIX, 0100644 << 16);
    }
} finally { $zip->close(); }
try {
    (new ArchiveValidator())->validate($temporary, $version);
    if (!rename($temporary, $archivePath)) { throw new RuntimeException('ZIP kann nicht veröffentlicht werden.'); }
    $digest = hash_file('sha256', $archivePath);
    if (file_put_contents($archivePath . '.sha256', $digest . '  MgdEuLabel.zip' . PHP_EOL, LOCK_EX) === false) { throw new RuntimeException('SHA256-Datei kann nicht geschrieben werden.'); }
    fwrite(STDOUT, 'Release ' . $version . ': ' . $archivePath . PHP_EOL . 'SHA256 ' . $digest . PHP_EOL);
} finally { if (is_file($temporary)) { unlink($temporary); } }
