<?php declare(strict_types=1);

namespace Mgd\EuLabel\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

/** Ausführung benötigt wie alle Shopware-Aufgaben einen konfigurierten Worker/Scheduler. */
final class UpdateTask extends ScheduledTask
{
    public static function getTaskName(): string { return 'mgd_eu_label.update_check'; }
    public static function getDefaultInterval(): int { return 21600; }
}
