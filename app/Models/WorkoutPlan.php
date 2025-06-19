<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'description',
    ];

    // Relazione: un piano ha molti esercizi
    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    // Relazione: un piano appartiene a un cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
