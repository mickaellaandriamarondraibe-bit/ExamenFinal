<?php

namespace App\Controllers;

use App\Models\CompteModel;
use App\Models\ClientModel;

class CompteController extends BaseController
{
    public function index()
    {
        $model = new CompteModel();

        $data = [
            'title' => 'Comptes clients',
            'comptes' => $model->getAllWithClient()
        ];

        return view('compte/index', $data);
    }

    public function create()
    {
        $clientModel = new ClientModel();

        $data = [
            'title' => 'Ajouter un compte',
            'clients' => $clientModel->findAll()
        ];

        return view('compte/create', $data);
    }

    public function store()
    {
        $model = new CompteModel();

        $model->insert([
            'client_id' => $this->request->getPost('client_id'),
            'numero_compte' => $this->request->getPost('numero_compte'),
            'solde' => $this->request->getPost('solde'),
            'actif' => 1
        ]);

        return redirect()->to('/compte');
    }

    public function edit($id)
    {
        $model = new CompteModel();
        $clientModel = new ClientModel();

        $data = [
            'title' => 'Modifier un compte',
            'compte' => $model->find($id),
            'clients' => $clientModel->findAll()
        ];

        return view('compte/edit', $data);
    }

    public function update($id)
    {
        $model = new CompteModel();

        $model->update($id, [
            'client_id' => $this->request->getPost('client_id'),
            'numero_compte' => $this->request->getPost('numero_compte'),
            'solde' => $this->request->getPost('solde'),
            'actif' => $this->request->getPost('actif'),
            'epargne' => $this->request->getPost('epargne'),
            'taux_epargne' =>$this->request->getPost('taux_epargne')
        ]);

        return redirect()->to('/compte');
    }

public function modifierEpargne()
{
    $client_id = session()->get('client_id');

    if (!$client_id) {
        return redirect()->to('/');
    }

    $compteModel = new CompteModel();
    $compte = $compteModel->getCompteByClientId($client_id);

    $taux = $this->request->getPost('tauxepargne');

    $compteModel->update($compte['id'], [
        'taux_epargne' => $taux
    ]);

    return redirect()->to('/compte');
}


    public function delete($id)
    {
        $model = new CompteModel();

        $model->delete($id);

        return redirect()->to('/compte');
    }

}