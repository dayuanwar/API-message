<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageDetail extends Model
{
    use HasFactory;

    protected $table = 'message_detail';
    protected $primaryKey = 'id';
}
