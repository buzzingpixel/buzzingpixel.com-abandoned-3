<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\Migration;

use Atlas\Mapper\Record;

/**
 * @method MigrationRow getRow()
 */
class MigrationRecord extends Record
{
    use MigrationFields;
}
