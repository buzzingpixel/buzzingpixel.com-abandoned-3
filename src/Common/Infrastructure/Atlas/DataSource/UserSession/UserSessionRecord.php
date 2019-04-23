<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\UserSession;

use Atlas\Mapper\Record;

/**
 * @method UserSessionRow getRow()
 */
class UserSessionRecord extends Record
{
    use UserSessionFields;
}
