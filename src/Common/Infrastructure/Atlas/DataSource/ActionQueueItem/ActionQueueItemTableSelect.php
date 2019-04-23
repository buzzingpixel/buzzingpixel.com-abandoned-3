<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ActionQueueItem;

use Atlas\Table\TableSelect;

/**
 * @method ActionQueueItemRow|null fetchRow()
 * @method ActionQueueItemRow[] fetchRows()
 */
class ActionQueueItemTableSelect extends TableSelect
{
}
