<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ActionQueueBatch;

use Atlas\Mapper\Record;

/**
 * @method ActionQueueBatchRow getRow()
 */
class ActionQueueBatchRecord extends Record
{
    use ActionQueueBatchFields;
}
