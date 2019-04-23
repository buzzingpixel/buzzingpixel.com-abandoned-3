<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\UserSession;

use Atlas\Table\TableSelect;

/**
 * @method UserSessionRow|null fetchRow()
 * @method UserSessionRow[] fetchRows()
 */
class UserSessionTableSelect extends TableSelect
{
}
