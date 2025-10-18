<?php
class Loan
{
    private int $loan = 0, $debt = 0, $interest = 0;

    public function __construct(array $data = []) {
        if (!empty($data) && $this->loanDataValidate($data)) {
            $this->setLoanData($data);
        }
        if (!$this->loan && isset($data['loan'])) {

        }
    }

    public function loadSessionData(\Public\Important\Session $session) {
        if ($session->judge('loan')) {
            $loanData = $session->read('loan');
            
            if ($this->loanDataValidate($loanData)) {
            $this->loan = $loanData['loan'];
            $this->debt = $loanData['debt'];
            $this->interest = $loanData['interest'];
            }
        }
    }

    public function loanDataValidate(array $loanData): bool
    {
        if (
            searchData('loan', $loanData)
            && searchData('debt', $loanData)
            && searchData('interest', $loanData)
        ) {
            return true;
        }

        return false;
    }
    
    public function setSessionData(\Public\Important\Session $session): void {
        $session->write('loan', $this->loan);
        $session->write('debt', $this->debt);
        $session->write('interest', $this->interest);
    }

    private function setLoan(int $loan)
    {
        $this->loan = $loan;
    }

    private function setDebt(int $debt)
    {
        $this->debt = $debt;
    }

    private function setInterest(int $interest)
    {
        $this->interest = $interest;
    }

    public function setLoanData(array $loanData)
    {
        $this->setLoan($loanData['loan']);
        $this->setDebt($loanData['debt']);
        $this->setInterest($loanData['interest']);
    }
}