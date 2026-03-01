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

    public function calculateBalances(): array
    {
        $activeUsers = $this->activeUsers()->get();
        $memberCount = $activeUsers->count();

        if ($memberCount === 0) {
            return [];
        }

        $totalExpenses = (float) $this->expenses()->sum('amount');
        $sharePerUser = $totalExpenses / $memberCount;

        $totalPaidByUser = $this->expenses()
            ->selectRaw('payer_id, COALESCE(SUM(amount), 0) as total_paid')
            ->groupBy('payer_id')
            ->pluck('total_paid', 'payer_id');

        $balances = [];

        foreach ($activeUsers as $user) {
            $totalPaid = (float) ($totalPaidByUser[$user->id] ?? 0);
            $balances[$user->id] = [
                'user' => $user,
                'total_paid' => $totalPaid,
                'share' => $sharePerUser,
                'balance' => $totalPaid - $sharePerUser,
            ];
        }

        foreach ($this->settlements()->get() as $payment) {
            if (isset($balances[$payment->from_user_id])) {
                $balances[$payment->from_user_id]['balance'] += (float) $payment->amount;
            }
            if (isset($balances[$payment->to_user_id])) {
                $balances[$payment->to_user_id]['balance'] -= (float) $payment->amount;
            }
        }

        foreach ($balances as $userId => $data) {
            $balances[$userId]['total_paid'] = round($data['total_paid'], 2);
            $balances[$userId]['share'] = round($data['share'], 2);
            $balances[$userId]['balance'] = round($data['balance'], 2);
        }

        return $balances;
    }

    public function calculateSettlements(): array
    {
        $balances = $this->calculateBalances();

        if (empty($balances)) {
            return [];
        }

        $creditors = [];
        $debtors = [];

        foreach ($balances as $userId => $data) {
            if ($data['balance'] > 0.01) {
                $creditors[] = ['user_id' => $userId, 'amount' => $data['balance']];
            } elseif ($data['balance'] < -0.01) {
                $debtors[] = ['user_id' => $userId, 'amount' => abs($data['balance'])];
            }
        }

        usort($creditors, fn($a, $b) => $b['amount'] <=> $a['amount']);
        usort($debtors, fn($a, $b) => $b['amount'] <=> $a['amount']);

        $settlements = [];
        $creditorIndex = 0;
        $debtorIndex = 0;

        while ($creditorIndex < count($creditors) && $debtorIndex < count($debtors)) {
            $credit = $creditors[$creditorIndex]['amount'];
            $debt = $debtors[$debtorIndex]['amount'];
            $transfer = round(min($credit, $debt), 2);

            if ($transfer <= 0) {
                break;
            }

            $settlements[] = [
                'from_user_id' => $debtors[$debtorIndex]['user_id'],
                'to_user_id' => $creditors[$creditorIndex]['user_id'],
                'amount' => $transfer,
            ];

            $creditors[$creditorIndex]['amount'] = round($credit - $transfer, 2);
            $debtors[$debtorIndex]['amount'] = round($debt - $transfer, 2);

            if ($creditors[$creditorIndex]['amount'] <= 0.01) {
                $creditorIndex++;
            }

            if ($debtors[$debtorIndex]['amount'] <= 0.01) {
                $debtorIndex++;
            }
        }

        return $settlements;
    }
}
