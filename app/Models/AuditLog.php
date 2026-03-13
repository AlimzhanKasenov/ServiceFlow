<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'request_id',
        'user_id',
        'action',
        'from_stage_id',
        'to_stage_id'
    ];
}
