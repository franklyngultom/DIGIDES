<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BackupRecord extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'backup_records';

    protected $fillable = [
        'user_id',
        'filename',
        'file_path',
        'size_bytes',
        'status',
        'backup_type',
        'error_message',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['filename', 'status', 'backup_type'])
            ->useLogName('database_backup')
            ->setDescriptionForEvent(fn (string $eventName) => "Cadangan database {$this->filename} {$eventName}");
    }

    /**
     * Relation to the user who initiated the backup.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Formatted file size accessor.
     */
    protected function formattedSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                $bytes = $this->size_bytes;
                if ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2).' MB';
                }
                if ($bytes >= 1024) {
                    return number_format($bytes / 1024, 2).' KB';
                }

                return $bytes.' Bytes';
            }
        );
    }
}
