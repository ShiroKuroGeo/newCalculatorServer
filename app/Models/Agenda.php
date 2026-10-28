<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable([
    'user_id',
    'kind',
    'title',
    'date',
    'time',
    'place',
    'budget',
    'notes',
    'status',
])]
class Agenda extends Model
{
    protected $table = 'agendas';

    protected $casts = [
        'budget' => 'float',
        'date' => 'date:Y-m-d',
    ];

    public function time(){
        return Attribute::get(fn ($v) => $v ? substr($v, 0, 5) : null);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function scopeMine(Builder $q){
        return $q->where('user_id', auth()->id());
    }
}
