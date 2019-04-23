<?php
declare(strict_types=1);

namespace App\Common\Infrastructure\Atlas\DataSource\ScheduleTracking;

use Atlas\Mapper\MapperSelect;

/**
 * @method ScheduleTrackingRecord|null fetchRecord()
 * @method ScheduleTrackingRecord[] fetchRecords()
 * @method ScheduleTrackingRecordSet fetchRecordSet()
 */
class ScheduleTrackingSelect extends MapperSelect
{
}
