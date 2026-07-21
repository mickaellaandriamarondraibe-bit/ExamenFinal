<?php

namespace App\Controllers;

use App\Models\Reduction;

class ReductionController extends BaseController
{
    protected $reductionModel;

    public function __construct()
    {
        $this->reductionModel = new Reduction();
    }

    public function index()
    {
        $data = [
            'title' => "reduction ",
            'types' => $this->reductionModel->findAll()
        ];

        return view('reduction/index', $data);
    }

    public function create()
    {
        return view('reduction/create', [
            'title' => "Nouveau reduction"
        ]);
    }

    public function store()
    {
        $libelle = strtoupper(
            trim((string) $this->request->getPost('libelle'))
        );

        $this->reductionModel->insert([
            'libelle' => $libelle
        ]);

        return redirect()
            ->to('/reduction')
            ->with('success', "reduction ajouté.");
    }

    public function update($id)
    {
        $libelle = strtoupper(
            trim((string) $this->request->getPost('libelle'))
        );

        $this->typeOperationModel->update($id, [
            'libelle' => $libelle
        ]);

        return redirect()
            ->to('/types-operations')
            ->with('success', "Type d'opération modifié.");
    }

    public function delete($id)
    {
        $this->typeOperationModel->delete($id);

        return redirect()
            ->to('/types-operations')
            ->with('success', "Type d'opération supprimé.");
    }
}