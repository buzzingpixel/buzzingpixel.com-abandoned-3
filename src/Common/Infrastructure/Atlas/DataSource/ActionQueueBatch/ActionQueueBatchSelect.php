<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ActionQueueBatch;

use Atlas\Mapper\MapperSelect;

/**
 * @method ActionQueueBatchRecord|null fetchRecord()
 * @method ActionQueueBatchRecord[] fetchRecords()
 * @method ActionQueueBatchRecordSet fetchRecordSet()
 */
class ActionQueueBatchSelect extends MapperSelect
{
}
