<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Pipeline
 *
 * Модель pipeline (воронки процессов).
 *
 * Pipeline определяет последовательность стадий,
 * по которым проходит заявка.
 *
 * Пример pipeline:
 *
 * HR Recruitment
 * IT Support
 * Finance Approval
 *
 * Таблица: pipelines
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class Pipeline extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Стадии pipeline
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * Права pipeline
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(PipelinePermission::class);
    }
}
