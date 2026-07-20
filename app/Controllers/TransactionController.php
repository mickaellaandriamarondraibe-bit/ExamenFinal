<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\CompteModel;
use App\Models\BaremeFraisModel;

class TransactionController extends BaseController
{

     public function index()
    {
        $transactionModel = new TransactionModel();

        $data = [
            'title' => 'Liste des transactions',
            'transactions' => $transactionModel
                ->orderBy('date_transaction', 'DESC')
                ->findAll()
        ];

        return view('transaction/all', $data);
    }

    // DEPOT


    public function faireDepot()
    {
        return view('transaction/depot');
    }

    public function enregistrerDepot()
    {
        $transactionModel = new TransactionModel();
        $compteModel = new CompteModel();
        $baremeFraisModel = new BaremeFraisModel();

        if ($this->request->is('post')) {

            $montant = $this->request->getPost('montant');
            $client_id = session()->get('client_id');

            if (!$client_id) {
                return redirect()->to('/');
            }

            if (!$montant || $montant <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Montant invalide');
            }

            $compte = $compteModel->getCompteByClientId($client_id);

            if (!$compte) {
                return redirect()->back()
                    ->with('error', 'Compte introuvable');
            }

            $type_operation_id = 1; // DEPOT

            $bareme = $baremeFraisModel->getFraisByMontant($type_operation_id, $montant);
            $frais = $bareme ? $bareme['frais'] : 0;

            $insertedId = $transactionModel->enregistrerDepot(
                $compte['id'],
                $type_operation_id,
                $montant,
                $frais
            );

            if (!$insertedId) {
                return redirect()->back()
                    ->with('error', "Erreur lors de l'enregistrement de la transaction");
            }

            
            $nouveauSolde = $compte['solde'] + $montant;
            $compteModel->update($compte['id'], ['solde' => $nouveauSolde]);

            return redirect()->to('/solde')
                ->with('success', 'Dépôt de ' . $montant . ' Ar effectué avec succès');
        }

        return view('transaction/depot');
    }

 
    // RETRAIT


    public function faireRetrait()
    {
        return view('transaction/retrait');
    }

    public function enregistrerRetrait()
    {
        $transactionModel = new TransactionModel();
        $compteModel = new CompteModel();
        $baremeFraisModel = new BaremeFraisModel();

        if ($this->request->is('post')) {

            $montant = $this->request->getPost('montant');
            $client_id = session()->get('client_id');

            if (!$client_id) {
                return redirect()->to('/');
            }

            if (!$montant || $montant <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Montant invalide');
            }

            $compte = $compteModel->getCompteByClientId($client_id);

            if (!$compte) {
                return redirect()->back()
                    ->with('error', 'Compte introuvable');
            }

            $type_operation_id = 2; // RETRAIT

            $bareme = $baremeFraisModel->getFraisByMontant($type_operation_id, $montant);
            $frais = $bareme ? $bareme['frais'] : 0;

            $totalADebiter = $montant + $frais;

            // Vérifier que le solde est suffisant (montant + frais)
            if ($compte['solde'] < $totalADebiter) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Solde insuffisant (montant + frais requis : ' . $totalADebiter . ' Ar)');
            }

            $insertedId = $transactionModel->enregistrerRetrait(
                $compte['id'],
                $type_operation_id,
                $montant,
                $frais
            );

            if (!$insertedId) {
                return redirect()->back()
                    ->with('error', "Erreur lors de l'enregistrement de la transaction");
            }

            // Débiter le compte (montant + frais)
            $nouveauSolde = $compte['solde'] - $totalADebiter;
            $compteModel->update($compte['id'], ['solde' => $nouveauSolde]);

            return redirect()->to('/solde')
                ->with('success', 'Retrait de ' . $montant . ' Ar effectué avec succès (frais : ' . $frais . ' Ar)');
        }

        return view('transaction/retrait');
    }


    // TRANSFERT


    public function faireTransfert()
    {
        return view('transaction/transfert');
    }

   public function enregistrerTransfert()
{
    $compteModel = new CompteModel();
    $prefixModel = new PrefixModel();
    $transactionModel = new TransactionModel();
    $baremeFraisModel = new BaremeFraisModel();
    $commissionModel = new CommissionInterOperateurModel();

    if ($this->request->is('post')) {

        $montant = (float) $this->request->getPost('montant');
        $telephoneDestinataire = $this->request->getPost('telephone');
        $telephoneDestinataire = preg_replace('/\D/', '', $telephoneDestinataire);
        $client_id = session()->get('client_id');

        if (!$client_id) {
            return redirect()->to('/');
        }

        if (!$montant || $montant <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Montant invalide');
        }

        if (strlen($telephoneDestinataire) != 10) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Numéro du destinataire invalide');
        }

        // Compte source
        $compteSource = $compteModel->getCompteByClientId($client_id);

        if (!$compteSource) {
            return redirect()->back()
                ->with('error', 'Compte source introuvable');
        }

        // Vérifier le préfixe du numéro destinataire
        $prefixe = substr($telephoneDestinataire, 0, 3);

        $prefixData = $prefixModel->findByPrefixe($prefixe);

        if (!$prefixData) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Préfixe non valide ou inactif');
        }

        // Compte destination
        $compteDestination = $compteModel->getCompteByTelephone(
            $telephoneDestinataire
        );

        if (!$compteDestination) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Destinataire introuvable');
        }

        if ($compteSource['id'] == $compteDestination['id']) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Vous ne pouvez pas transférer vers votre propre compte'
                );
        }

        $type_operation_id = 3; // TRANSFERT

        // Frais normaux du transfert selon le barème
        $bareme = $baremeFraisModel->getFraisByMontant(
            $type_operation_id,
            $montant
        );

        $fraisTransfert = $bareme ? (float) $bareme['frais'] : 0;

        // Commission appliquée seulement pour un autre opérateur
        $commission = 0;
        $pourcentageCommission = 0;

        if (!empty($prefixData['autre_operateur_id'])) {

            $commissionData = $commissionModel->getByAutreOperateurId(
                $prefixData['autre_operateur_id']
            );

            if (!$commissionData) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Commission de cet opérateur non configurée'
                    );
            }

            $pourcentageCommission = (float) $commissionData['pourcentage'];

            $commission = round(
                $montant * $pourcentageCommission / 100
            );
        }

        // frais du barème + commission inter-opérateur
        $fraisTotal = $fraisTransfert + $commission;

        // Montant total retiré du compte source
        $totalADebiter = $montant + $fraisTotal;

        if ($compteSource['solde'] < $totalADebiter) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Solde insuffisant. Total requis : '
                    . number_format($totalADebiter, 0, ',', ' ')
                    . ' Ar'
                );
        }

        $insertedId = $transactionModel->enregistrerTransfert(
            $compteSource['id'],
            $compteDestination['id'],
            $type_operation_id,
            $montant,
            $fraisTotal
        );

        if (!$insertedId) {
            return redirect()->back()
                ->with(
                    'error',
                    "Erreur lors de l'enregistrement de la transaction"
                );
        }

        // Débiter la source :
        // montant + frais de transfert + commission
        $compteModel->update($compteSource['id'], [
            'solde' => $compteSource['solde'] - $totalADebiter
        ]);

        // Créditer la destination avec le montant envoyé uniquement
        $compteModel->update($compteDestination['id'], [
            'solde' => $compteDestination['solde'] + $montant
        ]);

        return redirect()->to('/solde')
            ->with(
                'success',
                'Transfert effectué avec succès. '
                . 'Montant : '
                . number_format($montant, 0, ',', ' ')
                . ' Ar, frais de transfert : '
                . number_format($fraisTransfert, 0, ',', ' ')
                . ' Ar, commission : '
                . number_format($commission, 0, ',', ' ')
                . ' Ar, total débité : '
                . number_format($totalADebiter, 0, ',', ' ')
                . ' Ar'
            );
    }

    return view('transaction/transfert');
}

    // HISTORIQUE


    public function historique()
    {
        $transactionModel = new TransactionModel();
        $compteModel = new CompteModel();

        $client_id = session()->get('client_id');

        if (!$client_id) {
            return redirect()->to('/');
        }

        $compte = $compteModel->getCompteByClientId($client_id);

        if (!$compte) {
            return redirect()->to('/')
                ->with('error', 'Compte introuvable');
        }

        $transactionsBrutes = $transactionModel->getHistorique($compte['id']);

        // Ajouter le champ "sens" (in/out) pour chaque transaction
        $transactions = array_map(function ($t) use ($compte) {
            $t['sens'] = ($t['compte_destination_id'] == $compte['id']) ? 'in' : 'out';
            return $t;
        }, $transactionsBrutes);

        $data['transactions'] = $transactions;

        return view('transaction/historique', $data);
    }



    public function calculerFrais()
    {
        $baremeFraisModel = new BaremeFraisModel();

        $montant = (float) $this->request->getPost('montant');
        $type_operation_id = (int) $this->request->getPost('type_operation_id');

        if ($montant <= 0) {
            return $this->response->setJSON([
                'frais' => 0,
                'total' => 0,
            ]);
        }

        $bareme = $baremeFraisModel->getFraisByMontant($type_operation_id, $montant);
        $frais = $bareme ? (float) $bareme['frais'] : 0;

        return $this->response->setJSON([
            'frais'    => $frais,
            'montant'  => $montant,
            'total'    => $montant + $frais,   // pour retrait/transfert (débit)
            'recevra'  => $montant,            // ce que le destinataire/le client reçoit
        ]);
    }

    public function historique2($id)
{
    $transactionModel = new TransactionModel();
    $compteModel = new CompteModel();

    $compte = $compteModel->find($id);

    if (!$compte) {
        return redirect()->to('/operateur/compte')
            ->with('error', 'Compte introuvable');
    }

    $transactionsBrutes = $transactionModel->getHistorique($compte['id']);

    // Ajouter le champ "sens" (in/out) pour chaque transaction
    $transactions = array_map(function ($t) use ($compte) {
        $t['sens'] = ($t['compte_destination_id'] == $compte['id']) ? 'in' : 'out';

        return $t;
    }, $transactionsBrutes);

    $data = [
        'title' => 'Historique du compte',
        'transactions' => $transactions,
        'compte' => $compte,
        'compte_id' => $compte['id']
    ];

    return view('transaction/historique2', $data);
}
public function getHistorique($compte_id)
{
    $transactionModel = new TransactionModel();

    $data = [
        'title' => 'Historique du compte',
        'historique' => $transactionModel->getHistorique($compte_id),
        'compte_id' => $compte_id
    ];

    return view('transaction/index', $data);
}
}