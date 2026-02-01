<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeeAvailability extends Model
{
    use HasFactory;

    protected $table = 'employee_availability';

    protected $fillable = [
        'employee_id',
        'start_datetime',
        'end_datetime',
        'status',
        'reason',
        'job_id',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Get the employee that owns the availability record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the job that caused this unavailability (if applicable).
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Scope to get active unavailability records for a specific datetime.
     */
    public function scopeActiveAt($query, $datetime)
    {
        return $query->where('start_datetime', '<=', $datetime)
                    ->where('end_datetime', '>=', $datetime);
    }

    /**
     * Scope to get future unavailability records.
     */
    public function scopeFuture($query)
    {
        return $query->where('end_datetime', '>', now());
    }

    /**
     * Scope to get current unavailability records.
     */
    public function scopeCurrent($query)
    {
        return $query->where('start_datetime', '<=', now())
                    ->where('end_datetime', '>=', now());
    }

    /**
     * Check if the availability period conflicts with another period.
     */
    public function conflictsWith($startDateTime, $endDateTime)
    {
        return $this->start_datetime < $endDateTime && $this->end_datetime > $startDateTime;
    }

    /**
     * Create an unavailability record for a job assignment.
     */
    public static function markUnavailableForJob(Employee $employee, Job $job)
    {
        // Calculate job duration (default 2 hours, can be customized based on service)
        $jobStart = $job->scheduled_date;
        $jobEnd = $jobStart->copy()->addHours(2);

        return static::create([
            'employee_id' => $employee->id,
            'start_datetime' => $jobStart,
            'end_datetime' => $jobEnd,
            'status' => 'assigned',
            'reason' => "Assigned to Job #{$job->id}",
            'job_id' => $job->id,
        ]);
    }

    /**
     * Remove availability records for a specific job.
     */
    public static function clearForJob(Job $job)
    {
        return static::where('job_id', $job->id)->delete();
    }

    /**
     * Check if employee is available at a specific datetime.
     */
    public static function isAvailableAt(Employee $employee, $datetime)
    {
        return !static::where('employee_id', $employee->id)
                    ->activeAt($datetime)
                    ->exists();
    }

    /**
     * Get employees available for a specific datetime.
     */
    public static function getAvailableEmployeesAt($datetime)
    {
        $unavailableEmployeeIds = static::activeAt($datetime)
                                        ->pluck('employee_id')
                                        ->toArray();

        return Employee::whereNotIn('id', $unavailableEmployeeIds)->get();
    }
}
