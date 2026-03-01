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

    public function getAmountPerPersonAttribute(): float
    {
        $memberCount = $this->colocation?->activeUsers()->count() ?? 0;

        if ($memberCount === 0) {
            return (float) $this->amount;
        }

        return round(((float) $this->amount) / $memberCount, 2);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'expense_user');
    }
}
