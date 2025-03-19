<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'description',
        'expire_date',
        'quantity',
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
