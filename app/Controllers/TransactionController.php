<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\CompteModel;
use App\Models\BaremeFraisModel;

class TransactionController extends BaseController
{
    // ==========================================
    // DEPOT
    // ==========================================

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

            // Créditer le compte
            $nouveauSolde = $compte['solde'] + $montant;
            $compteModel->update($compte['id'], ['solde' => $nouveauSolde]);

            return redirect()->to('/solde')
                ->with('success', 'Dépôt de ' . $montant . ' Ar effectué avec succès');
        }

        return view('transaction/depot');
    }

    // ==========================================
    // RETRAIT
    // ==========================================

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

    // ==========================================
    // TRANSFERT
    // ==========================================

    public function faireTransfert()
    {
        return view('transaction/transfert');
    }

    public function enregistrerTransfert()
    {
        $transactionModel = new TransactionModel();
        $compteModel = new CompteModel();
        $baremeFraisModel = new BaremeFraisModel();

        if ($this->request->is('post')) {

            $montant = $this->request->getPost('montant');
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

            // Compte source (celui qui envoie)
            $compteSource = $compteModel->getCompteByClientId($client_id);

            if (!$compteSource) {
                return redirect()->back()
                    ->with('error', 'Compte introuvable');
            }

            // Compte destination (via le numéro de téléphone du destinataire)
            $compteDestination = $compteModel->getCompteByTelephone($telephoneDestinataire);

            if (!$compteDestination) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Destinataire introuvable');
            }

            // Empêcher de se transférer à soi-même
            if ($compteSource['id'] === $compteDestination['id']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Vous ne pouvez pas transférer vers votre propre compte');
            }

            $type_operation_id = 3; // TRANSFERT

            $bareme = $baremeFraisModel->getFraisByMontant($type_operation_id, $montant);
            $frais = $bareme ? $bareme['frais'] : 0;

            $totalADebiter = $montant + $frais;

            if ($compteSource['solde'] < $totalADebiter) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Solde insuffisant (montant + frais requis : ' . $totalADebiter . ' Ar)');
            }

            $insertedId = $transactionModel->enregistrerTransfert(
                $compteSource['id'],
                $compteDestination['id'],
                $type_operation_id,
                $montant,
                $frais
            );

            if (!$insertedId) {
                return redirect()->back()
                    ->with('error', "Erreur lors de l'enregistrement de la transaction");
            }

            // Débiter la source (montant + frais)
            $compteModel->update($compteSource['id'], [
                'solde' => $compteSource['solde'] - $totalADebiter
            ]);

            // Créditer la destination (montant seul, sans les frais)
            $compteModel->update($compteDestination['id'], [
                'solde' => $compteDestination['solde'] + $montant
            ]);

            return redirect()->to('/solde')
                ->with('success', 'Transfert de ' . $montant . ' Ar effectué avec succès');
        }

        return view('transaction/transfert');
    }


    // ==========================================
    // HISTORIQUE
    // ==========================================

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
}