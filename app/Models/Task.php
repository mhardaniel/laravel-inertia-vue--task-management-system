<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Prunable;

    protected $fillable = [
        // 'parent_id',
        'title',
        'body',
        'status',
        // 'images',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subTasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}
