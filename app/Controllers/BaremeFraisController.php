<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use App\Models\AutreOperateurModel;

class BaremeFraisController extends BaseController
{
    public function index()
    {
        $model = new BaremeFraisModel();

        $data = [
            'title' => 'Barèmes de frais',
            'baremes' => $model->getAllWithTypeOperation()
        ];

        return view('baremes_frais/index', $data);
    }

    public function create()
    {
        $typeModel = new TypeOperationModel();
        $operateurModel = new AutreOperateurModel();

        $data = [
            'title' => 'Ajouter un barème',
            'types' => $typeModel->findAll(),
            'operateurs' => $operateurModel->findAll()
        ];

        return view('baremes_frais/create', $data);
    }

    public function store()
{
    $model = new BaremeFraisModel();

    $autreOperateurId = $this->request->getPost('autre_operateur_id');

    $model->insert([
        'type_operation_id' => $this->request->getPost('type_operation_id'),

        'autre_operateur_id' => 
            $autreOperateurId == '' ? null : $autreOperateurId,

        'montant_min' => $this->request->getPost('montant_min'),

        'montant_max' => $this->request->getPost('montant_max'),

        'frais' => $this->request->getPost('frais')
    ]);

    return redirect()->to('/operateur/baremes-frais');
}

    public function edit($id)
{
    $model = new BaremeFraisModel();
    $typeModel = new TypeOperationModel();
    $operateurModel = new AutreOperateurModel();

    $data = [
        'title' => 'Modifier un barème',
        'bareme' => $model->find($id),
        'types' => $typeModel->findAll(),
        'operateurs' => $operateurModel->findAll()
    ];

    return view('baremes_frais/edit', $data);
}

    public function update($id)
{
    $model = new BaremeFraisModel();

    $autreOperateurId = $this->request->getPost('autre_operateur_id');

    $model->update($id, [

        'type_operation_id' => $this->request->getPost('type_operation_id'),

        'autre_operateur_id' =>
            $autreOperateurId == '' ? null : $autreOperateurId,

        'montant_min' => $this->request->getPost('montant_min'),

        'montant_max' => $this->request->getPost('montant_max'),

        'frais' => $this->request->getPost('frais')
    ]);

    return redirect()->to('/operateur/baremes-frais');
}

    public function delete($id)
    {
        $model = new BaremeFraisModel();

        $model->delete($id);

        return redirect()->to('/operateur/baremes-frais');
    }
}