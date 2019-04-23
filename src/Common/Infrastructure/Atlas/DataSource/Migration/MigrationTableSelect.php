<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\Migration;

use Atlas\Table\TableSelect;

/**
 * @method MigrationRow|null fetchRow()
 * @method MigrationRow[] fetchRows()
 */
class MigrationTableSelect extends TableSelect
{
}
