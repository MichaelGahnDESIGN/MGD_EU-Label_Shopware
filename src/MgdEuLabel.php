<?php declare(strict_types=1);

namespace Mgd\EuLabel;

use Mgd\EuLabel\Kit\Platform\Shopware\ShopwareKit;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

/** Die Standard-Lebenszyklusfunktionen von Shopware reichen ohne eigene Migrationen aus. */
class MgdEuLabel extends Plugin
{
    // Nach Update sofort melden (reportNow = ping(true))
    public function postUpdate(UpdateContext $updateContext): void
    {
        parent::postUpdate($updateContext);
        ShopwareKit::forLifecycle($updateContext, $this->container->get(SystemConfigService::class), 'mgd-eu-label-shopware')?->reportNow();
    }

    // Deinstallieren: bei theme.michael-gahn.de abmelden, Kit-Daten nur bei "Daten löschen" weg
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);
        ShopwareKit::forUninstall($uninstallContext, $this->container->get(SystemConfigService::class), 'mgd-eu-label-shopware')?->uninstall($uninstallContext);
    }
}
