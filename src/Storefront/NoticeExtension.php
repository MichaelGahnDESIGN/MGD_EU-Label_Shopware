<?php declare(strict_types=1);

namespace Mgd\EuLabel\Storefront;

use Mgd\EuLabel\Configuration\NoticeConfig;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/** Auch ESI-Footer erhalten ihre eigene Sales-Channel-Konfiguration ohne externe Anfragen. */
final class NoticeExtension extends AbstractExtension
{
    public function __construct(private readonly SystemConfigService $systemConfig, private readonly NoticeConfig $normalizer) {}

    public function getFunctions(): array
    {
        return [new TwigFunction('mgd_eu_notice', $this->notice(...))];
    }

    /** @return array<string, mixed> */
    public function notice(SalesChannelContext $context): array
    {
        $domain = $this->systemConfig->getDomain('MgdEuLabel.config.', $context->getSalesChannelId(), true);
        $values = [];
        foreach ($domain as $key => $value) { $values[substr($key, strlen('MgdEuLabel.config.'))] = $value; }
        $locale = $context->getLanguageInfo()->localeCode;
        return $this->normalizer->normalize($values, $locale);
    }
}
