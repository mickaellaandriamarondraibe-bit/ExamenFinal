<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompteModel;
use App\Models\TransactionModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $client_id = session()->get('client_id');

        if (!$client_id) {
            return redirect()->to('/');
        }

        $compteModel = new CompteModel();
        $compte = $compteModel->getCompteByClientId($client_id);

        if (!$compte) {
            return redirect()->to('/')
                ->with('error', 'Compte introuvable');
        }

        $data['compte'] = $compte;
        $data['derniereTransaction'] = null;

        $transactionModel = new TransactionModel();
        $transactions = $transactionModel->getHistorique($compte['id']);

        if (!empty($transactions)) {
            $data['derniereTransaction'] = $transactions[0];

            if ($transactions[0]['compte_destination_id'] == $compte['id']) {
                $data['derniereTransaction']['sens'] = 'in';
            } else {
                $data['derniereTransaction']['sens'] = 'out';
            }
        }

        return view('dashboard/index', $data);
    }
}
