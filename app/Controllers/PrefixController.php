<?php

namespace App\Controllers;

use App\Models\PrefixModel;

class PrefixController extends BaseController
{
    protected $prefixModel;

    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Préfixes',
            'prefixes' => $this->prefixModel->findAll()
        ];

        return view('prefixes/index', $data);
    }

    public function create()
    {
        return view('prefixes/create', [
            'title' => 'Nouveau préfixe'
        ]);
    }

    public function store()
    {
        $this->prefixModel->insert([
            'prefixe' => $this->request->getPost('prefixe'),
            'actif'   => $this->request->getPost('actif')
        ]);

        return redirect()->to('/prefixes');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Modifier un préfixe',
            'prefix' => $this->prefixModel->find($id)
        ];

        return view('prefixes/edit', $data);
    }

    public function update($id)
    {
        $this->prefixModel->update($id, [
            'prefixe' => $this->request->getPost('prefixe'),
            'actif'   => $this->request->getPost('actif')
        ]);

        return redirect()->to('/prefixes');
    }

    public function delete($id)
    {
        $this->prefixModel->delete($id);

        return redirect()->to('/prefixes');
    }

    public function findBylibelle($libelle)
    {
        return $this->prefixModel->findBylibelle($libelle);
    }
}