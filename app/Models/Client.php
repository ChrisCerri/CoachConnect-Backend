<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'personal_trainer_id',
        'name',
        'email',
        'phone',
        'notes',
    ];


    public function personalTrainer()
    {
        return $this->belongsTo(User::class, 'personal_trainer_id');
    }


    public function trainingPlans()
    {
        return $this->hasMany(TrainingPlan::class);
    }
}