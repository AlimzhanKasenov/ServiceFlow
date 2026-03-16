<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Webhook
 *
 * Модель webhook интеграции.
 */
class Webhook extends Model
{
    protected $fillable = [
        'organization_id',
        'event',
        'url',
        'secret',
        'active'
    ];
}
