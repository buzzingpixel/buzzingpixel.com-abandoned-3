<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\FlashDatum;

use Atlas\Table\TableSelect;

/**
 * @method FlashDatumRow|null fetchRow()
 * @method FlashDatumRow[] fetchRows()
 */
class FlashDatumTableSelect extends TableSelect
{
}
