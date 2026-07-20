<?php

namespace App\Controllers;

use App\Models\TypeOperationModel;

class TypeOperationController extends BaseController
{
    protected $typeOperationModel;

    public function __construct()
    {
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $data = [
            'title' => "Types d'opérations",
            'types' => $this->typeOperationModel->findAll()
        ];

        return view('types_operations/index', $data);
    }

    public function create()
    {
        return view('types_operations/create', [
            'title' => "Nouveau type d'opération"
        ]);
    }

    public function store()
    {
        $libelle = strtoupper(
            trim((string) $this->request->getPost('libelle'))
        );

        $this->typeOperationModel->insert([
            'libelle' => $libelle
        ]);

        return redirect()
            ->to('/types-operations')
            ->with('success', "Type d'opération ajouté.");
    }

    public function edit($id)
    {
        $type = $this->typeOperationModel->find($id);

        if (!$type) {
            return redirect()
                ->to('/types-operations')
                ->with('error', "Type d'opération introuvable.");
        }

        return view('types_operations/edit', [
            'title' => "Modifier un type d'opération",
            'type'  => $type
        ]);
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