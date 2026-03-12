<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Pipeline
 *
 * Воронка процесса ServiceFlow.
 * Определяет набор стадий обработки заявок.
 */
class Pipeline extends Model
{
    /**
     * Отключаем защиту массового заполнения
     * (для dev режима удобно)
     */
    protected $guarded = [];

    /**
     * Pipeline принадлежит организации
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Стадии pipeline
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }
}
