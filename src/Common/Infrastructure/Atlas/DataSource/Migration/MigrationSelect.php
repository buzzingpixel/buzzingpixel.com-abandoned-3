<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\Migration;

use Atlas\Mapper\MapperSelect;

/**
 * @method MigrationRecord|null fetchRecord()
 * @method MigrationRecord[] fetchRecords()
 * @method MigrationRecordSet fetchRecordSet()
 */
class MigrationSelect extends MapperSelect
{
}
