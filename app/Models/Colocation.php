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

    public function activeUsers()
    {
        return $this->users()->wherePivot('left_at', null);
    }

    public function owner()
    {
        return $this->users()->wherePivot('role', 'owner')->wherePivot('left_at', null);
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

    // Calcul des soldes pour chaque utilisateur
    public function calculateBalances()
    {
        $balances = [];
        $activeUsers = $this->activeUsers()->get();

        foreach ($activeUsers as $user) {
            $totalPaid = $this->expenses()
                ->where('payer_id', $user->id)
                ->sum('amount');
                
            $userShare = $this->expenses()
                ->whereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->sum('amount') / $activeUsers->count();
                
            $balances[$user->id] = [
                'user' => $user,
                'total_paid' => $totalPaid,
                'share' => $userShare,
                'balance' => $totalPaid - $userShare
            ];
        }

        return $balances;
    }

    // Calcul des remboursements optimisés
    public function calculateSettlements()
    {
        $balances = $this->calculateBalances();
        $debtors = [];
        $creditors = [];

        // Séparer les débiteurs et créditeurs
        foreach ($balances as $userId => $balance) {
            if ($balance['balance'] > 0) {
                $creditors[] = ['user_id' => $userId, 'amount' => $balance['balance']];
            } elseif ($balance['balance'] < 0) {
                $debtors[] = ['user_id' => $userId, 'amount' => abs($balance['balance'])];
            }
        }

        // Algorithme de remboursement optimisé
        $settlements = [];
        $i = 0; // index for debtors
        $j = 0; // index for creditors

        while ($i < count($debtors) && $j < count($creditors)) {
            $debtor = $debtors[$i];
            $creditor = $creditors[$j];

            $amount = min($debtor['amount'], $creditor['amount']);

            $settlements[] = [
                'from_user_id' => $debtor['user_id'],
                'to_user_id' => $creditor['user_id'],
                'amount' => $amount
            ];

            $debtors[$i]['amount'] -= $amount;
            $creditors[$j]['amount'] -= $amount;

            if ($debtors[$i]['amount'] == 0) $i++;
            if ($creditors[$j]['amount'] == 0) $j++;
        }

        return $settlements;
    }

    // Vérifier si un utilisateur est membre actif
    public function hasActiveMember(User $user)
    {
        return $this->activeUsers()->where('users.id', $user->id)->exists();
    }

    // Obtenir le rôle d'un utilisateur
    public function getUserRole(User $user)
    {
        $membership = $this->users()->where('users.id', $user->id)->first();
        return $membership ? $membership->pivot->role : null;
    }
}
