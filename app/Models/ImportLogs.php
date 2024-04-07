<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLogs extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'import_logs';
    protected $guarded = [];
}
