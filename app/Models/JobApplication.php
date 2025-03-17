<?php

namespace App\Models;

use App\Models\Job;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_offer_id',
        'full_name',
        'phone',
        'skill',
        'type_of_disability',
        'personal_image'
    ];

    public function jobOffer()
    {
        return $this->belongsTo(Job::class, 'job_offer_id');
    }
}
