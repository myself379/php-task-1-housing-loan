<?php

namespace App;

use App\InvalidArgumentException;

/**
 * Housing Loan
 * @param  decimal $loanValueRatio The loan value ratio in percentage
 * @param  decimal $principleAmount The principle amount borrowed
 * @param  decimal $percentageRate The percentage rate in percentage
 * @param  integer $numberOfRepayments The numbers of repayments in months
 * @param  decimal $monthlyPayments (optional, default to null) The monthly repayments
 */

class HousingLoan
{
    protected $loanValueRatio;
    protected $principleAmount;
    protected $percentageRate;
    protected $numberOfRepayments;
    protected $monthlyPayments;

    public function __construct(
        $loanValueRatio,
        $principleAmount,
        $percentageRate,
        $numberOfRepayments = null,
        $monthlyPayments = null
    ) {
        $this->_setLoanValueRatio($loanValueRatio);
        $this->principleAmount = $principleAmount;
        $this->_setPercentageRate($percentageRate);
        $this->numberOfRepayments = $numberOfRepayments;
        $this->monthlyPayments = $monthlyPayments;
    }

    protected function _setLoanValueRatio($loanValueRatio)
    {
        if ($loanValueRatio <= 0) {
            throw new InvalidArgumentException('Loan value ratio must not below 0 percent');
        }

        if ($loanValueRatio > 100) {
            throw new InvalidArgumentException('Loan value ratio must not above 100 percent');
        }

        $this->loanValueRatio = $loanValueRatio / 100;
    }

    protected function _setPercentageRate($percentageRate)
    {
        if ($percentageRate < 0) {
            throw new InvalidArgumentException('Percentage rate per period cannot be negative');
        }

        $this->percentageRate = $percentageRate / 100;
    }

    public function evalMonthlyRepayment()
    {
        $percentageInAnnual = $this->percentageRate / 12;
        $cummulativeCompound = pow(1 + $percentageInAnnual, $this->numberOfRepayments);

        $monthlyRepayment = $this->loanValueRatio * $this->principleAmount * $percentageInAnnual * $cummulativeCompound / ($cummulativeCompound - 1);

        return round($monthlyRepayment, 2);
    }

    public function evalPrincipleAmount()
    {
        $percentageInAnnual = $this->percentageRate / 12;
        $cummulativeCompound = pow(1 + $percentageInAnnual, $this->numberOfRepayments);

        $principleAmount = $this->monthlyPayments * ( $cummulativeCompound - 1) / ( $percentageInAnnual * $cummulativeCompound );

        return round($principleAmount, 2);
    }

    public function evalRepayments()
    {
        $number = $this->monthlyPayments / ($this->monthlyPayments - ($this->loanValueRatio * $this->principleAmount) * ($this->percentageRate / 12));

        $base = 1 + ($this->percentageRate / 12);

        $this->numberOfRepayments = round(log($number, $base), 0);

        return $this;
    }

    public function inYears()
    {
        return round($this->numberOfRepayments / 12, 0);
    }

    public function inMonths()
    {
        return $this->numberOfRepayments;
    }

    public function inYearsMonths()
    {
        return $this->inYears()." Years and ".($this->numberOfRepayments % 12)." Months";
    }
}
