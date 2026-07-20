<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompteModel;

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

        return view('dashboard/index', $data);
    }
}