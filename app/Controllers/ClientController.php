<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\PrefixModel ;

class ClientController extends BaseController
{
    public function index()
    {
        $model = new ClientModel();
        $data['clients'] = $model->getClient();
        return view('clients/index', $data);
    }

    public function loginPage(){
        return view('auth/login') ;
    }

    public function login()
{
    $model = new ClientModel();
    $prefixModel = new PrefixModel();

    if ($this->request->is('post')) {
        $telephone = $this->request->getPost('telephone');
        $telephone = preg_replace('/\D/', '', $telephone);

        if (strlen($telephone) != 10) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Numéro de téléphone non valide');
        }

        $client = $model->getClient($telephone);
        if ($client) {
            return redirect()->to('/clients');
        }

        $prefix = substr($telephone, 0, 3);
        $prefixData = $prefixModel->findByPrefixe($prefix);
        if (!$prefixData) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Préfixe non valide');
        }

        $prefix_id = $prefixData['id'];
        $model->createClient($prefix_id, $telephone);
        return redirect()->to('/clients');
    }

    return view('auth/welcom');
}

}
