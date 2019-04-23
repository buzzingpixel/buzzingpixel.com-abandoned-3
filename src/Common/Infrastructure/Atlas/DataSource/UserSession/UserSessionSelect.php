<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\UserSession;

use Atlas\Mapper\MapperSelect;

/**
 * @method UserSessionRecord|null fetchRecord()
 * @method UserSessionRecord[] fetchRecords()
 * @method UserSessionRecordSet fetchRecordSet()
 */
class UserSessionSelect extends MapperSelect
{
}
