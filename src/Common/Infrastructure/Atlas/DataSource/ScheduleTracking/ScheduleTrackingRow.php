<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ScheduleTracking;

use Atlas\Table\Row;

/**
 * @property mixed $id bigint(20,0) unsigned NOT NULL
 * @property mixed $guid varchar(255) NOT NULL
 * @property mixed $is_running tinyint(3,0) NOT NULL
 * @property mixed $last_run_start_at datetime
 * @property mixed $last_run_start_at_time_zone varchar(255)
 * @property mixed $last_run_end_at datetime
 * @property mixed $last_run_end_at_time_zone varchar(255)
 */
class ScheduleTrackingRow extends Row
{
    protected $cols = [
        'id' => null,
        'guid' => null,
        'is_running' => 0,
        'last_run_start_at' => 'NULL',
        'last_run_start_at_time_zone' => 'NULL',
        'last_run_end_at' => 'NULL',
        'last_run_end_at_time_zone' => 'NULL',
    ];
}
