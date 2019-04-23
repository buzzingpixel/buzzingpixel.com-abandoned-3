<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ActionQueueItem;

use Atlas\Mapper\MapperSelect;

/**
 * @method ActionQueueItemRecord|null fetchRecord()
 * @method ActionQueueItemRecord[] fetchRecords()
 * @method ActionQueueItemRecordSet fetchRecordSet()
 */
class ActionQueueItemSelect extends MapperSelect
{
}
