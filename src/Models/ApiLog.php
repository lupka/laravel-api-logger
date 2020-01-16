<?php

namespace Lupka\ApiLogger\Models;

use Illuminate\Database\Eloquent\Model;

/**
* Model of the table tasks.
*/
class ApiLog extends Model
{
    protected $table = 'api_logs';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(config('api_logger.user_class'));
    }
}
