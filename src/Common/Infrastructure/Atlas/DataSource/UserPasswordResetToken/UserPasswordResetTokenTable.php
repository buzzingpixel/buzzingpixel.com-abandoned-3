<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\UserPasswordResetToken;

use Atlas\Table\Table;

/**
 * @method UserPasswordResetTokenRow|null fetchRow($primaryVal)
 * @method UserPasswordResetTokenRow[] fetchRows(array $primaryVals)
 * @method UserPasswordResetTokenTableSelect select(array $whereEquals = [])
 * @method UserPasswordResetTokenRow newRow(array $cols = [])
 * @method UserPasswordResetTokenRow newSelectedRow(array $cols)
 */
class UserPasswordResetTokenTable extends Table
{
    const DRIVER = 'mysql';

    const NAME = 'user_password_reset_tokens';

    const COLUMNS = [
        'guid' => [
            'name' => 'guid',
            'type' => 'binary',
            'size' => 16,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => true,
            'options' => null,
        ],
        'user_guid' => [
            'name' => 'user_guid',
            'type' => 'binary',
            'size' => 16,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'added_at' => [
            'name' => 'added_at',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'added_at_time_zone' => [
            'name' => 'added_at_time_zone',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'guid',
        'user_guid',
        'added_at',
        'added_at_time_zone',
    ];

    const COLUMN_DEFAULTS = [
        'guid' => null,
        'user_guid' => null,
        'added_at' => null,
        'added_at_time_zone' => null,
    ];

    const PRIMARY_KEY = [
        'guid',
    ];

    const AUTOINC_COLUMN = null;

    const AUTOINC_SEQUENCE = null;
}
