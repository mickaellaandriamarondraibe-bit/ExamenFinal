<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel ;
use App\Models\CompteModel ;

class TransactionController extends BaseController
{

    public function faireDepot(){
        return view ('transaction/depot') ;
    }

    public function enregistrerDepot()
    {
        $transaction = new TransactionModel ; 
        $compteModel = new CompteModel ;
        if($this->request->is('post')){
            $montant = $this->request->getPost('montant');
            $telephone = session()->get('telephone'); 
            $type_operation_id = 1 ;
            $compte_destination_id = $compteModel->
        }
    }
}
