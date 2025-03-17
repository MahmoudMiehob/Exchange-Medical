<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{

    use HasFactory;


    protected $table = 'jobs_offer' ;
    protected $fillable = [
        'name',
        'place',
        'image',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_offer_id');
    }
}
