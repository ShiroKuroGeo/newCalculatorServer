<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;

#[Fillable([
    'user_id',
    'label',
    'amount',
    'status',
    'date',
])]
class Entry extends Model
{
    protected $table = 'entries';

    public function User(){
        return $this->belongsTo(User::class);
    }
    
    public function scopeMine(Builder $builder) {
        return $builder->where('user_id', auth()->id());
    }
}
