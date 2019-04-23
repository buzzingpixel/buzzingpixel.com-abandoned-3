<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\UserPasswordResetToken;

use Atlas\Mapper\MapperSelect;

/**
 * @method UserPasswordResetTokenRecord|null fetchRecord()
 * @method UserPasswordResetTokenRecord[] fetchRecords()
 * @method UserPasswordResetTokenRecordSet fetchRecordSet()
 */
class UserPasswordResetTokenSelect extends MapperSelect
{
}
