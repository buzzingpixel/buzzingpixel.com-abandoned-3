<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ActionQueueBatch;

use Atlas\Table\Row;

/**
 * @property mixed $guid binary(16) NOT NULL
 * @property mixed $name varchar(255) NOT NULL
 * @property mixed $title varchar(255) NOT NULL
 * @property mixed $has_started tinyint(3,0) NOT NULL
 * @property mixed $is_running tinyint(3,0) NOT NULL
 * @property mixed $assume_dead_after datetime NOT NULL
 * @property mixed $assume_dead_after_time_zone varchar(255) NOT NULL
 * @property mixed $initial_assume_dead_after datetime NOT NULL
 * @property mixed $initial_assume_dead_after_time_zone varchar(255) NOT NULL
 * @property mixed $is_finished tinyint(3,0) NOT NULL
 * @property mixed $finished_due_to_error tinyint(3,0) NOT NULL
 * @property mixed $percent_complete float(12) NOT NULL
 * @property mixed $added_at datetime NOT NULL
 * @property mixed $added_at_time_zone varchar(255) NOT NULL
 * @property mixed $finished_at datetime
 * @property mixed $finished_at_time_zone varchar(255)
 * @property mixed $context text(65535)
 */
class ActionQueueBatchRow extends Row
{
    protected $cols = [
        'guid' => null,
        'name' => null,
        'title' => null,
        'has_started' => 0,
        'is_running' => 0,
        'assume_dead_after' => null,
        'assume_dead_after_time_zone' => null,
        'initial_assume_dead_after' => null,
        'initial_assume_dead_after_time_zone' => null,
        'is_finished' => 0,
        'finished_due_to_error' => 0,
        'percent_complete' => 0.0,
        'added_at' => null,
        'added_at_time_zone' => null,
        'finished_at' => 'NULL',
        'finished_at_time_zone' => 'NULL',
        'context' => 'NULL',
    ];
}
