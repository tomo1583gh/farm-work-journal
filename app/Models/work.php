<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'category_name', 'work_time', 'content', 'work_date', 'weather', 'image_path'];
}
