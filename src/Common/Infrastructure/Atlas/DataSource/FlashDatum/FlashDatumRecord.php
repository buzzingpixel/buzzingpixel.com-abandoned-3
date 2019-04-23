<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\FlashDatum;

use Atlas\Mapper\Record;

/**
 * @method FlashDatumRow getRow()
 */
class FlashDatumRecord extends Record
{
    use FlashDatumFields;
}
