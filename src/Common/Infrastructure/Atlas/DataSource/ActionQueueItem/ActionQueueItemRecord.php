<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ActionQueueItem;

use Atlas\Mapper\Record;

/**
 * @method ActionQueueItemRow getRow()
 */
class ActionQueueItemRecord extends Record
{
    use ActionQueueItemFields;
}
