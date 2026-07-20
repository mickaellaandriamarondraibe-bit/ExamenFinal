<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\PrefixModel ;
use App\Models\CompteModel ;

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
    $compteModel = new CompteModel(); // <-- ajouté

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
                session()->set([
                    'client_id'  => $client['id'],
                    'telephone'  => $client['telephone'],
                    'isLoggedIn' => true,
                ]);
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
            $insertId = $model->createClient($prefix_id, $telephone);

            if (!$insertId) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de la création du compte');
            }

            
            session()->set([
                'client_id'  => $insertId,
                'telephone'  => $telephone,
                'isLoggedIn' => true,
            ]);

        $client = $model->getClient($telephone);
        if ($client) {
            session()->set([
                'client_id'  => $client['id'],
                'telephone'  => $client['telephone'],
                'isLoggedIn' => true,
            ]);
            return redirect()->to('/accueil');
        }

        $prefix = substr($telephone, 0, 3);
        $prefixData = $prefixModel->findByPrefixe($prefix);
        if (!$prefixData) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Préfixe non valide');
        }

        $prefix_id = $prefixData['id'];
        $insertId = $model->createClient($prefix_id, $telephone);

        if (!$insertId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du compte client');
        }

        // Créer automatiquement le compte associé
        $compteId = $compteModel->createCompte($insertId);

        if (!$compteId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du compte');
        }

        session()->set([
            'client_id'  => $insertId,
            'telephone'  => $telephone,
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/accueil');
    }

    return view('auth/login');
}

    public function solde()
{
    $client_id = session()->get('client_id');

    if (!$client_id) {
        return redirect()->to('/');
    }

    $compteModel = new CompteModel();
    $compte = $compteModel->getCompteByClientId($client_id);

    if (!$compte) {
        return redirect()->to('/accueil')
            ->with('error', 'Compte introuvable');
    }

    $data['compte'] = $compte;

    return view('clients/solde', $data);
}
}
