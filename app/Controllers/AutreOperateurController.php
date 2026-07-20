<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AutreOperateurModel;

class AutreOperateurController extends BaseController
{
    protected $autreOperateurModel;

    public function __construct()
    {
        $this->autreOperateurModel = new AutreOperateurModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Autres opérateurs',
            'operateurs' => $this->autreOperateurModel->findAll()
        ];

        return view('operateurs/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Ajouter un opérateur'
        ];

        return view('operateurs/create', $data);
    }

    public function store()
    {
        $this->autreOperateurModel->insert([
            'nom' => $this->request->getPost('nom')
        ]);

        return redirect()->to('/operateur/autres_operateurs');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Modifier un opérateur',
            'operateur' => $this->autreOperateurModel->find($id)
        ];

        return view('operateurs/edit', $data);
    }

    public function update($id)
    {
        $this->autreOperateurModel->update($id, [
            'nom' => $this->request->getPost('nom')
        ]);

        return redirect()->to('/operateur/autres_operateurs');
    }

    public function delete($id)
    {
        $this->autreOperateurModel->delete($id);

        return redirect()->to('/operateur/autres_operateurs');
    }
}