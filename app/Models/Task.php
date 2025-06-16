<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
     use HasFactory, HasUuids,SoftDeletes;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['user_id', 'title', 'description', 'status'];
    


    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
