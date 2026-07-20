<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class GainController extends BaseController
{
    public function index()
    {
        $transactionModel = new TransactionModel();

        $data = [
            'title' => 'Situation des gains',
            'gainsInternes' => $transactionModel->getGainsInternes(),
            'gainsInterOperateurs' => $transactionModel->getGainsInterOperateurs()
        ];

        return view('gains/index', $data);
    }
}