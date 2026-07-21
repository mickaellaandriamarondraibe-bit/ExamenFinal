<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CommissionInterOperateurModel;
use App\Models\AutreOperateurModel;

class CommissionController extends BaseController
{
    protected $commissionModel;
    protected $autreOperateurModel;

     public function __construct()
    {
        $this->commissionModel = new CommissionInterOperateurModel();
        $this->autreOperateurModel = new AutreOperateurModel();
    }


    public function index()
    {
        $data = [
            'title'       => 'Commission Opérateur',
            'commissions' => $this->commissionModel->findAll(),
        ];

        return view('commission/index', $data);
    }

    public function create()
    {
        $data = [
            'operateurs' => $this->autreOperateurModel->findAll(),
        ];

        return view('commission/create', $data);
    }

   

    public function store()
    {
        $autre_operateur_id = $this->request->getPost('operateur_id');
        $pourcentage = $this->request->getPost('pourcentage');

        if (!$autre_operateur_id || $pourcentage === null || $pourcentage === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tous les champs sont obligatoires');
        }

        $inserted = $this->commissionModel->insert([
            'autre_operateur_id' => $autre_operateur_id,
            'pourcentage'        => $pourcentage,
        ]);

        if (!$inserted) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de la commission');
        }

        return redirect()->to('/operateur/commissions')
            ->with('success', 'Commission créée avec succès');
    }

    public function edit($id)
    {
        $commission = $this->commissionModel->find($id);

        if (!$commission) {
            return redirect()->to('/operateur/commissions')
                ->with('error', 'Commission introuvable');
        }

        $data = [
            'commission' => $commission,
            'operateurs' => $this->autreOperateurModel->findAll(),
        ];

        return view('commission/edit', $data);
    }

    public function update($id)
    {
        $commission = $this->commissionModel->find($id);

        if (!$commission) {
            return redirect()->to('/operateur/commissions')
                ->with('error', 'Commission introuvable');
        }

        $autre_operateur_id = $this->request->getPost('operateur_id');
        $pourcentage = $this->request->getPost('pourcentage');

        if (!$autre_operateur_id || $pourcentage === null || $pourcentage === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tous les champs sont obligatoires');
        }

        $updated = $this->commissionModel->update($id, [
            'autre_operateur_id' => $autre_operateur_id,
            'pourcentage'        => $pourcentage,
        ]);

        if (!$updated) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour');
        }

        return redirect()->to('/operateur/commissions')
            ->with('success', 'Commission mise à jour avec succès');
    }

    public function delete($id)
    {
        $commission = $this->commissionModel->find($id);

        if (!$commission) {
            return redirect()->to('/operateur/commissions')
                ->with('error', 'Commission introuvable');
        }

        $this->commissionModel->delete($id);

        return redirect()->to('/operateur/commissions')
            ->with('success', 'Commission supprimée avec succès');
    }
}