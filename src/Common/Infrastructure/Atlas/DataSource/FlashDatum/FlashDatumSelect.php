<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\FlashDatum;

use Atlas\Mapper\MapperSelect;

/**
 * @method FlashDatumRecord|null fetchRecord()
 * @method FlashDatumRecord[] fetchRecords()
 * @method FlashDatumRecordSet fetchRecordSet()
 */
class FlashDatumSelect extends MapperSelect
{
}
