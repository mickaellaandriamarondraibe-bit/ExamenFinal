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
        $prefixes = $this->prefixModel
            ->select('prefixes.*, autre_operateur.nom AS nom_autre_operateur')
            ->join('autre_operateur', 'autre_operateur.id = prefixes.autre_operateur_id', 'left')
            ->findAll();

        $data = [
            'title' => 'Préfixes',
            'prefixes' => $prefixes
        ];

        return view('prefixes/index', $data);
    }

    public function create()
    {
        return view('prefixes/create', [
            'title' => 'Nouveau préfixe',
            'autresOperateurs' => $this->getAutresOperateurs()
        ]);
    }

    public function store()
    {
        $this->prefixModel->insert([
            'prefixe' => $this->request->getPost('prefixe'),
            'autre_operateur_id' => $this->getAutreOperateurId(),
            'actif'   => $this->request->getPost('actif')
        ]);

        return redirect()->to('/operateur/prefixes');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Modifier un préfixe',
            'prefix' => $this->prefixModel->find($id),
            'autresOperateurs' => $this->getAutresOperateurs()
        ];

        return view('prefixes/edit', $data);
    }

    public function update($id)
    {
        $this->prefixModel->update($id, [
            'prefixe' => $this->request->getPost('prefixe'),
            'autre_operateur_id' => $this->getAutreOperateurId(),
            'actif'   => $this->request->getPost('actif')
        ]);

        return redirect()->to('/operateur/prefixes');
    }

    public function delete($id)
    {
        $this->prefixModel->delete($id);

        return redirect()->to('/operateur/prefixes');
    }

    public function findBylibelle($libelle)
    {
        return $this->prefixModel->findBylibelle($libelle);
    }

    private function getAutresOperateurs()
    {
        return db_connect()->table('autre_operateur')->orderBy('nom', 'ASC')->get()->getResultArray();
    }

    private function getAutreOperateurId()
    {
        $autreOperateurId = $this->request->getPost('autre_operateur_id');

        if ($autreOperateurId == '') {
            return null;
        }

        return $autreOperateurId;
    }
}
