<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalServiceConfiguration extends Model
{
    protected $table = "external_service_configurations";
    protected $guarded = ['id'];
}
