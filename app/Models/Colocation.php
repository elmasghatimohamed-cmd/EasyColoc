<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Invitation;
use App\Models\Settlement;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'invite_token', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function owner()
    {
        return $this->users()->wherePivot('role', 'owner');
    }

    public function activeUsers()
    {
        return $this->users()->wherePivot('left_at', null);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }
}
