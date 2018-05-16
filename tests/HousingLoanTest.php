<?php

use App\HousingLoan;
use PHPUnit\Framework\TestCase;

class HousingLoanTest extends TestCase
{
    /** @test */
    function test_for_housing_loan_monthly_repayment()
    {
        $loanValueRatio = 90;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.45;
        $numberInMonths = 30 * 12;
        $monthlyPayments = null;

        $monthlyRepayment = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )->evalMonthlyRepayment();

        $this->assertEquals('2266.73', $monthlyRepayment);
    }

    /** @test */
    function test_for_housing_loan_monthly_repayment_with_increased_rate_per_annum()
    {
        $loanValueRatio = 90;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.65;
        $numberInMonths = 30 * 12;
        $monthlyPayments = null;

        $monthlyRepayment = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )->evalMonthlyRepayment();
        
        $this->assertEquals('2320.37', $monthlyRepayment);
    }

    /** @test */
    function test_for_housing_loan_amount_of_repayment_month()
    {
        $loanValueRatio = 90;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.65;
        $numberInMonths = null;
        $monthlyPayments = 2000;

        $repaymentDuration = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )
        ->evalRepayments();

        $this->assertEquals(531, $repaymentDuration->inMonths());
        $this->assertEquals(44, $repaymentDuration->inYears());
        $this->assertEquals("44 Years and 3 Months", $repaymentDuration->inYearsMonths());
    }

    /** @test */
    function test_for_housing_loan_principle_amount_for_monthly_repayment_of_2000()
    {
        $loanValueRatio = 90;
        $principleAmountBorrowed = null;
        $percentageRate = 4.65;
        $numberInMonths = 30 * 12;
        $monthlyPayments = 2000;

        $principleAmount = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )
        ->evalPrincipleAmount();

        $this->assertEquals(387869.91, $principleAmount);
    }

    /** @test */
    function test_for_loan_value_ratio_value_must_not_below_0()
    {
        $loanValueRatio = -1;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.45;
        $numberInMonths = 30 * 12;
        $monthlyPayments = null;

        $this->expectException('App\InvalidArgumentException');
        $this->expectExceptionMessage('Loan value ratio must not below 0 percent');

        $monthlyRepayment = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )->evalMonthlyRepayment();
    }

    /** @test */
    function test_for_loan_value_ratio_value_must_not_more_than_100()
    {
        $loanValueRatio = 100.1;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.45;
        $numberInMonths = 30 * 12;
        $monthlyPayments = null;

        $this->expectException('App\InvalidArgumentException');
        $this->expectExceptionMessage('Loan value ratio must not above 100 percent');

        $monthlyRepayment = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )->evalMonthlyRepayment();
    }

    /** @test */
    function test_for_percentage_rate_per_period_cannot_be_negative()
    {
        $loanValueRatio = 90;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = -1;
        $numberInMonths = 30 * 12;
        $monthlyPayments = null;

        $this->expectException('App\InvalidArgumentException');
        $this->expectExceptionMessage('Percentage rate per period cannot be negative');

        $monthlyRepayment = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )->evalMonthlyRepayment();
    }

    /** @test */
    function message_output()
    {
        $loanValueRatio = 90;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.65;
        $numberInMonths = 30 * 12;
        $monthlyPayments = null;

        $monthlyRepayment = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )->evalMonthlyRepayment();

        $loanValueRatio = 90;
        $principleAmountBorrowed = 500000.00;
        $percentageRate = 4.65;
        $numberInMonths = null;
        $monthlyPayments = 2000;

        $repaymentDuration = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )
        ->evalRepayments()
        ->inYears();

        $loanValueRatio = 90;
        $principleAmountBorrowed = null;
        $percentageRate = 4.65;
        $numberInMonths = 30 * 12;
        $monthlyPayments = 2000;

        $principleAmount = ( 
            new HousingLoan(
                $loanValueRatio,
                $principleAmountBorrowed,
                $percentageRate,
                $numberInMonths,
                $monthlyPayments
            ) 
        )
        ->evalPrincipleAmount();

        $output = "\n\nThe monthly repayment is: ".$monthlyRepayment;
        $output2 = "\n\n";
        $output3 = "The loan period in years to achieve is: ".$repaymentDuration;
        $output4 = "\n\n";
        $output5 = "The loan amount capable of borrowing is: ".$principleAmount;
        $output6 = "\n\n";

        $outputMsg = $output.$output2.$output3.$output4.$output5.$output6;

        $this->assertEquals(1, 1);
        fwrite(STDERR, print_r($outputMsg, TRUE));
    }
}