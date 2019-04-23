<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\User;

use Atlas\Mapper\Record;

/**
 * @method UserRow getRow()
 */
class UserRecord extends Record
{
    use UserFields;
}
