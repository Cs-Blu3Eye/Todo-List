<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'user_id',
    ];

    /**
     * Casting atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date', // Mengubah due_date menjadi objek Carbon
    ];

    /**
     * Dapatkan user yang memiliki task ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

