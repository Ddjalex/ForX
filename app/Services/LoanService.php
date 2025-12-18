<?php

namespace App\Services;

class LoanService
{
    public static function getLoanPlans(): array
    {
        return Database::fetchAll(
            "SELECT * FROM loan_plans WHERE status = 'active' ORDER BY min_amount ASC"
        ) ?: self::getDefaultPlans();
    }
    
    public static function getUserLoans(int $userId): array
    {
        return Database::fetchAll(
            "SELECT * FROM loans WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        ) ?: [];
    }
    
    public static function applyForLoan(int $userId, int $planId, float $amount, string $collateral): array
    {
        $plan = Database::fetch("SELECT * FROM loan_plans WHERE id = ? AND status = 'active'", [$planId]);
        
        if (!$plan) {
            return ['success' => false, 'error' => 'Invalid loan plan'];
        }
        
        if ($amount < $plan['min_amount'] || $amount > $plan['max_amount']) {
            return ['success' => false, 'error' => 'Amount must be between $' . $plan['min_amount'] . ' and $' . $plan['max_amount']];
        }
        
        $interestAmount = $amount * ($plan['interest_rate'] / 100) * ($plan['duration_days'] / 365);
        $totalRepayment = $amount + $interestAmount;
        $collateralRequired = $amount * ($plan['collateral_ratio'] / 100);
        
        $loanId = Database::insert('loans', [
            'user_id' => $userId,
            'plan_id' => $planId,
            'amount' => $amount,
            'interest_rate' => $plan['interest_rate'],
            'interest_amount' => $interestAmount,
            'total_repayment' => $totalRepayment,
            'collateral_type' => $collateral,
            'collateral_amount' => $collateralRequired,
            'duration_days' => $plan['duration_days'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        return ['success' => true, 'loan_id' => $loanId, 'message' => 'Loan application submitted successfully'];
    }
    
    public static function getDefaultPlans(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Starter Loan',
                'description' => 'Perfect for beginners looking for quick capital',
                'min_amount' => 100,
                'max_amount' => 1000,
                'interest_rate' => 5.5,
                'duration_days' => 30,
                'collateral_ratio' => 150,
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Growth Loan',
                'description' => 'Ideal for expanding your trading portfolio',
                'min_amount' => 1000,
                'max_amount' => 10000,
                'interest_rate' => 4.5,
                'duration_days' => 60,
                'collateral_ratio' => 140,
                'status' => 'active',
            ],
            [
                'id' => 3,
                'name' => 'Pro Trader Loan',
                'description' => 'Maximum capital for professional traders',
                'min_amount' => 10000,
                'max_amount' => 100000,
                'interest_rate' => 3.5,
                'duration_days' => 90,
                'collateral_ratio' => 130,
                'status' => 'active',
            ],
            [
                'id' => 4,
                'name' => 'Enterprise Loan',
                'description' => 'Large scale funding for institutional traders',
                'min_amount' => 50000,
                'max_amount' => 500000,
                'interest_rate' => 2.5,
                'duration_days' => 180,
                'collateral_ratio' => 120,
                'status' => 'active',
            ],
        ];
    }
    
    public static function calculateLoan(float $amount, float $interestRate, int $durationDays): array
    {
        $interestAmount = $amount * ($interestRate / 100) * ($durationDays / 365);
        $totalRepayment = $amount + $interestAmount;
        $dailyRate = $interestRate / 365;
        
        return [
            'principal' => $amount,
            'interest_amount' => round($interestAmount, 2),
            'total_repayment' => round($totalRepayment, 2),
            'daily_rate' => round($dailyRate, 4),
            'duration_days' => $durationDays,
        ];
    }
}
