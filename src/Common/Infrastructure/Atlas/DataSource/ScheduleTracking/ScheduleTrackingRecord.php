<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ScheduleTracking;

use Atlas\Mapper\Record;

/**
 * @method ScheduleTrackingRow getRow()
 */
class ScheduleTrackingRecord extends Record
{
    use ScheduleTrackingFields;
}
