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
}
