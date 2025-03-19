<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    protected $fillable = [
        'name',
        'description',
        'number_of_device',
        'image',
    ];


    public function donations()
    {
        return $this->morphMany(Donation::class, 'donatable');
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    
}
