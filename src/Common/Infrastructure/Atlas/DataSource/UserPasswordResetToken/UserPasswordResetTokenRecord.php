<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\UserPasswordResetToken;

use Atlas\Mapper\Record;

/**
 * @method UserPasswordResetTokenRow getRow()
 */
class UserPasswordResetTokenRecord extends Record
{
    use UserPasswordResetTokenFields;
}
