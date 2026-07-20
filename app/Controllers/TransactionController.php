<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\CompteModel;
use App\Models\BaremeFraisModel;
use App\Models\PrefixModel;
use App\Models\CommissionInterOperateurModel ;

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
    $prefixModel = new \App\Models\PrefixModel();
    $transactionModel = new TransactionModel();
    $baremeModel = new BaremeFraisModel();
    $commissionModel = new \App\Models\CommissionInterOperateurModel();

    $clientId = session()->get('client_id');
    $montant = (float) $this->request->getPost('montant');
    $telephone = preg_replace(
        '/\D/',
        '',
        (string) $this->request->getPost('telephone')
    );
    $inclureRetrait =
        $this->request->getPost('inclure_frais_retrait') ? 1 : 0;

    if (!$clientId) {
        return redirect()->to('/');
    }

    if ($montant <= 0 || strlen($telephone) !== 10) {
        return redirect()->back()->withInput()
            ->with('error', 'Données invalides');
    }

    $source = $compteModel->getCompteByClientId($clientId);
    $destination = $compteModel->getCompteByTelephone($telephone);
    $prefixe = $prefixModel->findByPrefixe(substr($telephone, 0, 3));

    if (
        !$source ||
        !$destination ||
        !$prefixe ||
        $source['id'] == $destination['id']
    ) {
        return redirect()->back()->withInput()
            ->with('error', 'Destinataire invalide');
    }

    $autreOperateurId = empty($prefixe['autre_operateur_id'])
        ? null
        : (int) $prefixe['autre_operateur_id'];

    $baremeTransfert = $baremeModel->getFraisByMontant(
        3,
        $montant,
        $autreOperateurId
    );

    if (!$baremeTransfert) {
        return redirect()->back()->withInput()
            ->with('error', 'Barème de transfert introuvable');
    }

    $frais = (float) $baremeTransfert['frais'];
    $fraisRetrait = 0;

    if ($inclureRetrait) {
        $baremeRetrait = $baremeModel->getFraisByMontant(
            2,
            $montant,
            $autreOperateurId
        );

        if (!$baremeRetrait) {
            return redirect()->back()->withInput()
                ->with('error', 'Barème de retrait introuvable');
        }

        $fraisRetrait = (float) $baremeRetrait['frais'];
    }

    $commission = 0;

    if ($autreOperateurId !== null) {
        $commissionData = $commissionModel
            ->where('autre_operateur_id', $autreOperateurId)
            ->first();

        if (!$commissionData) {
            return redirect()->back()->withInput()
                ->with('error', 'Commission introuvable');
        }

        $commission = round(
            $montant * (float) $commissionData['pourcentage'] / 100
        );
    }

    $totalADebiter =
        $montant +
        $frais +
        $fraisRetrait +
        $commission;

    if ($source['solde'] < $totalADebiter) {
        return redirect()->back()->withInput()
            ->with('error', 'Solde insuffisant');
    }

    $db = db_connect();
    $db->transStart();

    $transactionModel->enregistrerTransfert(
        $source['id'],
        $destination['id'],
        3,
        $montant,
        $montant,
        $frais,
        $fraisRetrait,
        $commission,
        $inclureRetrait,
        $autreOperateurId
    );

    $compteModel->update($source['id'], [
        'solde' => $source['solde'] - $totalADebiter
    ]);

    $compteModel->update($destination['id'], [
        'solde' => $destination['solde'] + $montant
    ]);

    $db->transComplete();

    if (!$db->transStatus()) {
        return redirect()->back()->withInput()
            ->with('error', 'Erreur pendant le transfert');
    }

    return redirect()->to('/solde')
        ->with('success', 'Transfert effectué avec succès');
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
    $baremeFraisModel = new \App\Models\BaremeFraisModel();
    $prefixModel = new \App\Models\PrefixModel();
    $commissionModel = new \App\Models\CommissionInterOperateurModel();

    $montant = (float) $this->request->getPost('montant');

    $telephone = preg_replace(
        '/\D/',
        '',
        (string) $this->request->getPost('telephone')
    );

    $typeOperationId = (int) $this->request->getPost(
        'type_operation_id'
    );

    $priseEnChargeCommission =
        $this->request->getPost('prise_en_charge_commission') ? 1 : 0;

    if ($montant <= 0) {
        return $this->response->setJSON([
            'error' => 'Montant invalide'
        ]);
    }

    if (strlen($telephone) < 3) {
        return $this->response->setJSON([
            'error' => 'Numéro invalide'
        ]);
    }

    $prefixe = substr($telephone, 0, 3);

    $prefixData = $prefixModel
        ->where('prefixe', $prefixe)
        ->where('actif', 1)
        ->first();

    if (!$prefixData) {
        return $this->response->setJSON([
            'error' => 'Préfixe invalide'
        ]);
    }

    $autreOperateurId = !empty(
        $prefixData['autre_operateur_id']
    )
        ? (int) $prefixData['autre_operateur_id']
        : null;

    $baremeQuery = $baremeFraisModel
        ->where('type_operation_id', $typeOperationId)
        ->where('montant_min <=', $montant)
        ->where('montant_max >=', $montant);

    if ($autreOperateurId === null) {
        $baremeQuery->where('autre_operateur_id', null);
    } else {
        $baremeQuery->where(
            'autre_operateur_id',
            $autreOperateurId
        );
    }

    $bareme = $baremeQuery->first();

    if (!$bareme) {
        return $this->response->setJSON([
            'error' => 'Aucun barème trouvé'
        ]);
    }

    $frais = (float) $bareme['frais'];
    $commission = 0;

    if ($autreOperateurId !== null) {
        $commissionData = $commissionModel
            ->where(
                'autre_operateur_id',
                $autreOperateurId
            )
            ->first();

        if ($commissionData) {
            $pourcentage = (float) $commissionData['pourcentage'];

            $commission = round(
                $montant * $pourcentage / 100
            );
        }
    }

    if ($priseEnChargeCommission === 1) {
        $montantRecu = $montant;
        $totalADebiter = $montant + $frais + $commission;
    } else {
        $montantRecu = $montant - $commission;
        $totalADebiter = $montant + $frais;
    }

    return $this->response->setJSON([
        'frais'        => $frais,
        'commission'   => $commission,
        'montant_recu' => $montantRecu,
        'total'        => $totalADebiter
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

public function transfertMultiple()
{
    return view('transaction/transfert_multiple');
}

public function enregistrerTransfertMultiple()
{
    $compteModel = new CompteModel();
    $prefixModel = new \App\Models\PrefixModel();
    $baremeModel = new BaremeFraisModel();
    $commissionModel = new \App\Models\CommissionInterOperateurModel();
    $transactionModel = new TransactionModel();

    $clientId = session()->get('client_id');
    $montantTotal = (int) $this->request->getPost('montant_total');
    $telephones = $this->request->getPost('telephones');
    $inclureRetrait = $this->request->getPost('inclure_frais_retrait') ? 1 : 0;

    if (!$clientId) return redirect()->to('/');
    if ($montantTotal <= 0 || !is_array($telephones) || count($telephones) < 2) {
        return redirect()->back()->withInput()->with('error', 'Données invalides');
    }

    $telephones = array_map(
        fn($tel) => preg_replace('/\D/', '', $tel),
        $telephones
    );

    if (
        count(array_unique($telephones)) !== count($telephones) ||
        array_filter($telephones, fn($tel) => strlen($tel) !== 10)
    ) {
        return redirect()->back()->withInput()->with('error', 'Numéros invalides ou en double');
    }

    $source = $compteModel->getCompteByClientId($clientId);

    if (!$source) {
        return redirect()->back()->with('error', 'Compte source introuvable');
    }

    $nombre = count($telephones);
    $montantBase = intdiv($montantTotal, $nombre);
    $reste = $montantTotal % $nombre;

    $details = [];
    $operateurCommun = null;
    $totalFrais = 0;
    $totalRetrait = 0;
    $totalCommission = 0;

    foreach ($telephones as $index => $telephone) {
        $montant = $montantBase + ($index === 0 ? $reste : 0);
        $prefixe = $prefixModel->findByPrefixe(substr($telephone, 0, 3));
        $destination = $compteModel->getCompteByTelephone($telephone);

        if (!$prefixe || !$destination || $destination['id'] == $source['id']) {
            return redirect()->back()->withInput()->with('error', "Destinataire invalide : $telephone");
        }

        $autreOperateurId = empty($prefixe['autre_operateur_id'])
            ? null
            : (int) $prefixe['autre_operateur_id'];

        if ($index === 0) {
            $operateurCommun = $autreOperateurId;
        } elseif ($autreOperateurId !== $operateurCommun) {
            return redirect()->back()->withInput()
                ->with('error', 'Tous les numéros doivent être du même opérateur');
        }

        $baremeTransfert = $baremeModel->getFraisByMontant(
            3,
            $montant,
            $autreOperateurId
        );

        if (!$baremeTransfert) {
            return redirect()->back()->withInput()
                ->with('error', 'Barème de transfert introuvable');
        }

        $frais = (float) $baremeTransfert['frais'];
        $fraisRetrait = 0;

        if ($inclureRetrait) {
            $baremeRetrait = $baremeModel->getFraisByMontant(
                2,
                $montant,
                $autreOperateurId
            );

            if (!$baremeRetrait) {
                return redirect()->back()->withInput()
                    ->with('error', 'Barème de retrait introuvable');
            }

            $fraisRetrait = (float) $baremeRetrait['frais'];
        }

        $commission = 0;

        if ($autreOperateurId !== null) {
            $commissionData = $commissionModel
                ->where('autre_operateur_id', $autreOperateurId)
                ->first();

            if (!$commissionData) {
                return redirect()->back()->withInput()
                    ->with('error', 'Commission introuvable');
            }

            $commission = round(
                $montant * (float) $commissionData['pourcentage'] / 100
            );
        }

        $totalFrais += $frais;
        $totalRetrait += $fraisRetrait;
        $totalCommission += $commission;

        $details[] = [
            'destination' => $destination,
            'montant' => $montant,
            'frais' => $frais,
            'frais_retrait' => $fraisRetrait,
            'commission' => $commission,
            'autre_operateur_id' => $autreOperateurId
        ];
    }

    $totalADebiter =
        $montantTotal +
        $totalFrais +
        $totalRetrait +
        $totalCommission;

    if ($source['solde'] < $totalADebiter) {
        return redirect()->back()->withInput()
            ->with('error', 'Solde insuffisant');
    }

    $db = db_connect();
    $db->transStart();

    foreach ($details as $detail) {
        $transactionModel->enregistrerTransfertMultiple(
            $source['id'],
            $detail['destination']['id'],
            3,
            $detail['montant'],
            $detail['montant'],
            $detail['frais'],
            $detail['frais_retrait'],
            $detail['commission'],
            $inclureRetrait,
            $detail['autre_operateur_id']
        );

        $compteModel->update($detail['destination']['id'], [
            'solde' => $detail['destination']['solde'] + $detail['montant']
        ]);
    }

    $compteModel->update($source['id'], [
        'solde' => $source['solde'] - $totalADebiter
    ]);

    $db->transComplete();

    if (!$db->transStatus()) {
        return redirect()->back()->withInput()
            ->with('error', 'Erreur pendant le transfert');
    }

    return redirect()->to('/accueil')->with(
        'success',
        "Transfert effectué vers $nombre destinataires"
    );
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

public function montantsOperateurs()
{
    $transactionModel = new TransactionModel();

    $data = [
        'title' => 'Montants à envoyer aux opérateurs',
        'montants' => $transactionModel->getMontantsParOperateur()
    ];

    return view('transaction/montants_operateurs', $data);
}
}