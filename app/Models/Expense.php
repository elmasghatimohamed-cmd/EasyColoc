<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'colocation_id',
        'payer_id',
        'category_id',
        'title',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    // Ajout de la relation many-to-many avec les utilisateurs qui partagent la dépense
    public function users()
    {
        return $this->belongsToMany(User::class, 'expense_user')
            ->withTimestamps();
    }

    // Calcul du montant par personne
    public function getAmountPerPersonAttribute()
    {
        $activeMembersCount = $this->colocation->users()->wherePivot('left_at', null)->count();
        return $activeMembersCount > 0 ? $this->amount / $activeMembersCount : 0;
    }
}
