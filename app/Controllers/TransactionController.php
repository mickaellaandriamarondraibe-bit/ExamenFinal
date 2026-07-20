<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BaremeFraisModel;
use App\Models\CommissionInterOperateurModel;
use App\Models\CompteModel;
use App\Models\PrefixModel;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    public function index()
    {
        $transactionModel = new TransactionModel();

        $data = [
            'title' => 'Liste des transactions',
            'transactions' => $transactionModel->orderBy('date_transaction', 'DESC')->findAll(),
        ];

        return view('transaction/all', $data);
    }

    public function faireDepot()
    {
        return view('transaction/depot');
    }

    public function enregistrerDepot()
    {
        if (!$this->request->is('post')) {
            return view('transaction/depot');
        }

        $clientId = session()->get('client_id');
        $montant = (float) $this->request->getPost('montant');

        if (!$clientId) {
            return redirect()->to('/');
        }

        if ($montant <= 0) {
            return $this->retourErreur('Montant invalide');
        }

        $compteModel = new CompteModel();
        $transactionModel = new TransactionModel();
        $compte = $compteModel->getCompteByClientId($clientId);

        if (!$compte) {
            return redirect()->back()->with('error', 'Compte introuvable');
        }

        $frais = $this->getFrais(1, $montant);
        $insertedId = $transactionModel->enregistrerDepot($compte['id'], 1, $montant, $frais);

        if (!$insertedId) {
            return redirect()->back()->with('error', "Erreur lors de l'enregistrement de la transaction");
        }

        $this->crediterCompte($compteModel, $compte, $montant);

        return redirect()->to('/solde')->with('success', 'Dépôt de ' . $montant . ' Ar effectué avec succès');
    }

    public function faireRetrait()
    {
        return view('transaction/retrait');
    }

    public function enregistrerRetrait()
    {
        if (!$this->request->is('post')) {
            return view('transaction/retrait');
        }

        $clientId = session()->get('client_id');
        $montant = (float) $this->request->getPost('montant');

        if (!$clientId) {
            return redirect()->to('/');
        }

        if ($montant <= 0) {
            return $this->retourErreur('Montant invalide');
        }

        $compteModel = new CompteModel();
        $transactionModel = new TransactionModel();
        $compte = $compteModel->getCompteByClientId($clientId);

        if (!$compte) {
            return redirect()->back()->with('error', 'Compte introuvable');
        }

        $frais = $this->getFrais(2, $montant);
        $total = $montant + $frais;

        if ($compte['solde'] < $total) {
            return $this->retourErreur('Solde insuffisant (montant + frais requis : ' . $total . ' Ar)');
        }

        $insertedId = $transactionModel->enregistrerRetrait($compte['id'], 2, $montant, $frais);

        if (!$insertedId) {
            return redirect()->back()->with('error', "Erreur lors de l'enregistrement de la transaction");
        }

        $this->debiterCompte($compteModel, $compte, $total);

        return redirect()->to('/solde')->with('success', 'Retrait de ' . $montant . ' Ar effectué avec succès (frais : ' . $frais . ' Ar)');
    }

    public function faireTransfert()
    {
        return view('transaction/transfert');
    }

    public function enregistrerTransfert()
    {
        $clientId = session()->get('client_id');
        $montant = (float) $this->request->getPost('montant');
        $telephone = $this->nettoyerTelephone($this->request->getPost('telephone'));
        $inclureRetrait = $this->request->getPost('inclure_frais_retrait') ? 1 : 0;

        if (!$clientId) {
            return redirect()->to('/');
        }

        if ($montant <= 0 || strlen($telephone) !== 10) {
            return $this->retourErreur('Données invalides');
        }

        $compteModel = new CompteModel();
        $transactionModel = new TransactionModel();
        $source = $compteModel->getCompteByClientId($clientId);
        $destination = $this->getDestinataire($compteModel, new PrefixModel(), $telephone, $source);

        if (isset($destination['erreur'])) {
            return $this->retourErreur($destination['erreur']);
        }

        $couts = $this->calculerCoutsTransfert($montant, $destination['autre_operateur_id'], $inclureRetrait, true);

        if (isset($couts['erreur'])) {
            return $this->retourErreur($couts['erreur']);
        }

        if ($source['solde'] < $couts['total']) {
            return $this->retourErreur('Solde insuffisant');
        }

        $db = db_connect();
        $db->transStart();

        $transactionModel->enregistrerTransfert($source['id'], $destination['compte']['id'], 3, $montant, $couts['montant_recu'], $couts['frais_total'], $couts['commission'], 1, $destination['autre_operateur_id']);
        $this->debiterCompte($compteModel, $source, $couts['total']);
        $this->crediterCompte($compteModel, $destination['compte'], $couts['montant_recu']);

        $db->transComplete();

        if (!$db->transStatus()) {
            return $this->retourErreur('Erreur pendant le transfert');
        }

        return redirect()->to('/solde')->with('success', 'Transfert effectué avec succès');
    }

    public function historique()
    {
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        $compteModel = new CompteModel();
        $compte = $compteModel->getCompteByClientId($clientId);

        if (!$compte) {
            return redirect()->to('/')->with('error', 'Compte introuvable');
        }

        $data['transactions'] = $this->getHistoriqueAvecSens($compte);

        return view('transaction/historique', $data);
    }

    public function calculerFrais()
    {
        $montant = (float) $this->request->getPost('montant');
        $typeOperationId = (int) $this->request->getPost('type_operation_id');

        if ($montant <= 0) {
            return $this->jsonErreur('Montant invalide');
        }

        if ($typeOperationId == 1 || $typeOperationId == 2) {
            $frais = $this->getFrais($typeOperationId, $montant);

            return $this->response->setJSON([
                'frais' => $frais,
                'montant' => $montant,
                'recevra' => $montant,
                'total' => $montant + $frais,
            ]);
        }

        $telephone = $this->nettoyerTelephone($this->request->getPost('telephone'));
        $inclureRetrait = $this->request->getPost('inclure_frais_retrait') ? 1 : 0;

        if (strlen($telephone) < 3) {
            return $this->jsonErreur('Numéro invalide');
        }

        $prefixe = $this->getPrefixe(new PrefixModel(), $telephone, true);

        if (!$prefixe) {
            return $this->jsonErreur('Préfixe invalide');
        }

        $autreOperateurId = empty($prefixe['autre_operateur_id']) ? null : (int) $prefixe['autre_operateur_id'];
        $couts = $this->calculerCoutsTransfert($montant, $autreOperateurId, $inclureRetrait, false);

        if (isset($couts['erreur'])) {
            return $this->jsonErreur($couts['erreur']);
        }

        return $this->response->setJSON([
            'frais' => $couts['frais_transfert'],
            'frais_retrait' => $couts['frais_retrait'],
            'commission' => $couts['commission'],
            'montant_recu' => $couts['montant_recu'],
            'total' => $couts['total'],
        ]);
    }

    public function historique2($id)
    {
        $compteModel = new CompteModel();
        $compte = $compteModel->find($id);

        if (!$compte) {
            return redirect()->to('/operateur/compte')->with('error', 'Compte introuvable');
        }

        $data = [
            'title' => 'Historique du compte',
            'transactions' => $this->getHistoriqueAvecSens($compte),
            'compte' => $compte,
            'compte_id' => $compte['id'],
        ];

        return view('transaction/historique2', $data);
    }

    public function transfertMultiple()
    {
        return view('transaction/transfert_multiple');
    }

    public function calculerFraisTransfertMultiple()
    {
        $montantTotal = (int) $this->request->getPost('montant_total');
        $telephones = $this->nettoyerListeTelephones($this->request->getPost('telephones'));
        $inclureRetrait = $this->request->getPost('inclure_frais_retrait') ? 1 : 0;

        $erreur = $this->validerTransfertMultiple($montantTotal, $telephones);

        if ($erreur) {
            return $this->jsonErreur($erreur, ['success' => false]);
        }

        $preparation = $this->preparerTransfertMultiple($montantTotal, $telephones, $inclureRetrait, false);

        if (isset($preparation['erreur'])) {
            return $this->jsonErreur($preparation['erreur'], ['success' => false]);
        }

        $data = [
            'success' => true,
            'total_frais' => $preparation['total_frais'],
            'total_frais_retrait' => $preparation['total_frais_retrait'],
            'total_commission' => $preparation['total_commission'],
            'total_montant_recu' => $preparation['montant_total'],
            'total_a_debiter' => $preparation['total'],
            'details' => $preparation['details'],
        ];

        if (function_exists('csrf_hash')) {
            $data['csrf_hash'] = csrf_hash();
        }

        return $this->response->setJSON($data);
    }

    public function enregistrerTransfertMultiple()
    {
        $clientId = session()->get('client_id');
        $montantTotal = (int) $this->request->getPost('montant_total');
        $telephones = $this->nettoyerListeTelephones($this->request->getPost('telephones'));
        $inclureRetrait = $this->request->getPost('inclure_frais_retrait') ? 1 : 0;

        if (!$clientId) {
            return redirect()->to('/');
        }

        $erreur = $this->validerTransfertMultiple($montantTotal, $telephones);

        if ($erreur) {
            return $this->retourErreur($erreur);
        }

        $compteModel = new CompteModel();
        $transactionModel = new TransactionModel();
        $source = $compteModel->getCompteByClientId($clientId);

        if (!$source) {
            return redirect()->back()->with('error', 'Compte source introuvable');
        }

        $preparation = $this->preparerTransfertMultiple($montantTotal, $telephones, $inclureRetrait, true, $compteModel, $source);

        if (isset($preparation['erreur'])) {
            return $this->retourErreur($preparation['erreur']);
        }

        if ($source['solde'] < $preparation['total']) {
            return $this->retourErreur('Solde insuffisant');
        }

        $db = db_connect();
        $db->transStart();

        foreach ($preparation['details'] as $detail) {
            $fraisTotal = $detail['frais'] + $detail['frais_retrait'];
            $transactionModel->enregistrerTransfertMultiple($source['id'], $detail['destination']['id'], 3, $detail['montant'], $detail['montant_recu'], $fraisTotal, $detail['commission'], 1, $detail['autre_operateur_id']);
            $this->crediterCompte($compteModel, $detail['destination'], $detail['montant_recu']);
        }

        $this->debiterCompte($compteModel, $source, $preparation['total']);
        $db->transComplete();

        if (!$db->transStatus()) {
            return $this->retourErreur('Erreur pendant le transfert');
        }

        return redirect()->to('/accueil')->with('success', 'Transfert effectué vers ' . count($telephones) . ' destinataires');
    }

    private function nettoyerTelephone($telephone)
    {
        return preg_replace('/\D/', '', (string) $telephone);
    }

    private function nettoyerListeTelephones($telephones)
    {
        if (!is_array($telephones)) {
            return [];
        }

        $liste = [];
        foreach ($telephones as $telephone) {
            $liste[] = $this->nettoyerTelephone($telephone);
        }

        return $liste;
    }

    private function validerTransfertMultiple($montantTotal, $telephones)
    {
        if ($montantTotal <= 0 || count($telephones) < 2) {
            return 'Données invalides';
        }

        if (count(array_unique($telephones)) !== count($telephones)) {
            return 'Numéros invalides ou en double';
        }

        foreach ($telephones as $telephone) {
            if (strlen($telephone) !== 10) {
                return 'Numéros invalides ou en double';
            }
        }

        return null;
    }

    private function getDestinataire($compteModel, $prefixModel, $telephone, $source)
    {
        $destination = $compteModel->getCompteByTelephone($telephone);
        $prefixe = $this->getPrefixe($prefixModel, $telephone);

        if (!$source || !$destination || !$prefixe || $source['id'] == $destination['id']) {
            return ['erreur' => 'Destinataire invalide'];
        }

        $autreOperateurId = empty($prefixe['autre_operateur_id']) ? null : (int) $prefixe['autre_operateur_id'];

        return [
            'compte' => $destination,
            'autre_operateur_id' => $autreOperateurId,
        ];
    }

    private function getPrefixe($prefixModel, $telephone, $actifSeulement = false)
    {
        $query = $prefixModel->where('prefixe', substr($telephone, 0, 3));

        if ($actifSeulement) {
            $query->where('actif', 1);
        }

        return $query->first();
    }

    private function getFrais($typeOperationId, $montant, $autreOperateurId = null)
    {
        $baremeModel = new BaremeFraisModel();
        $bareme = $baremeModel->getFraisByMontant($typeOperationId, $montant, $autreOperateurId);

        if (!$bareme) {
            return 0;
        }

        return (float) $bareme['frais'];
    }

    private function calculerCoutsTransfert($montant, $autreOperateurId, $inclureRetrait, $commissionObligatoire)
    {
        $baremeModel = new BaremeFraisModel();
        $commissionModel = new CommissionInterOperateurModel();
        $baremeTransfert = $baremeModel->getFraisByMontant(3, $montant, null);

        if (!$baremeTransfert) {
            return ['erreur' => 'Barème de transfert introuvable'];
        }

        $fraisTransfert = (float) $baremeTransfert['frais'];
        $fraisRetrait = $this->getFraisRetraitSiPossible($montant, $autreOperateurId, $inclureRetrait);
        $commission = $this->getCommission($commissionModel, $montant, $autreOperateurId, $commissionObligatoire);

        if (isset($fraisRetrait['erreur'])) {
            return $fraisRetrait;
        }

        if (isset($commission['erreur'])) {
            return $commission;
        }

        return [
            'frais_transfert' => $fraisTransfert,
            'frais_retrait' => $fraisRetrait,
            'frais_total' => $fraisTransfert + $fraisRetrait,
            'commission' => $commission,
            'montant_recu' => $montant,
            'total' => $montant + $fraisTransfert + $fraisRetrait + $commission,
        ];
    }

    private function getFraisRetraitSiPossible($montant, $autreOperateurId, $inclureRetrait)
    {
        if (!$inclureRetrait || $autreOperateurId !== null) {
            return 0;
        }

        $baremeModel = new BaremeFraisModel();
        $baremeRetrait = $baremeModel->getFraisByMontant(2, $montant, null);

        if (!$baremeRetrait) {
            return ['erreur' => 'Barème de retrait introuvable'];
        }

        return (float) $baremeRetrait['frais'];
    }

    private function getCommission($commissionModel, $montant, $autreOperateurId, $obligatoire)
    {
        if ($autreOperateurId === null) {
            return 0;
        }

        $commissionData = $commissionModel->where('autre_operateur_id', $autreOperateurId)->first();

        if (!$commissionData) {
            if ($obligatoire) {
                return ['erreur' => 'Commission introuvable'];
            }

            return 0;
        }

        return round($montant * (float) $commissionData['pourcentage'] / 100);
    }

    private function preparerTransfertMultiple($montantTotal, $telephones, $inclureRetrait, $verifierCompte, $compteModel = null, $source = null)
    {
        $details = [];
        $operateurCommun = null;
        $totalFrais = 0;
        $totalFraisRetrait = 0;
        $totalCommission = 0;
        $nombre = count($telephones);
        $prefixModel = new PrefixModel();

        foreach ($telephones as $index => $telephone) {
            $montant = $this->getMontantPourDestinataire($montantTotal, $nombre, $index);
            $prefixe = $this->getPrefixe($prefixModel, $telephone, true);

            if (!$prefixe) {
                return ['erreur' => 'Préfixe invalide : ' . $telephone];
            }

            $autreOperateurId = empty($prefixe['autre_operateur_id']) ? null : (int) $prefixe['autre_operateur_id'];
            $destination = null;

            if ($verifierCompte) {
                $destination = $compteModel->getCompteByTelephone($telephone);

                if (!$destination || $destination['id'] == $source['id']) {
                    return ['erreur' => 'Destinataire invalide : ' . $telephone];
                }
            }

            if ($index == 0) {
                $operateurCommun = $autreOperateurId;
            } elseif ($autreOperateurId !== $operateurCommun) {
                return ['erreur' => 'Tous les numéros doivent être du même opérateur'];
            }

            $couts = $this->calculerCoutsTransfert($montant, $autreOperateurId, $inclureRetrait, true);

            if (isset($couts['erreur'])) {
                return $couts;
            }

            $totalFrais += $couts['frais_transfert'];
            $totalFraisRetrait += $couts['frais_retrait'];
            $totalCommission += $couts['commission'];

            $details[] = [
                'telephone' => $telephone,
                'destination' => $destination,
                'montant' => $montant,
                'montant_recu' => $couts['montant_recu'],
                'frais' => $couts['frais_transfert'],
                'frais_retrait' => $couts['frais_retrait'],
                'commission' => $couts['commission'],
                'autre_operateur_id' => $autreOperateurId,
            ];
        }

        return [
            'details' => $details,
            'montant_total' => $montantTotal,
            'total_frais' => $totalFrais,
            'total_frais_retrait' => $totalFraisRetrait,
            'total_commission' => $totalCommission,
            'total' => $montantTotal + $totalFrais + $totalFraisRetrait + $totalCommission,
        ];
    }

    private function getMontantPourDestinataire($montantTotal, $nombre, $index)
    {
        $montantBase = intdiv($montantTotal, $nombre);
        $reste = $montantTotal % $nombre;

        if ($index == 0) {
            return $montantBase + $reste;
        }

        return $montantBase;
    }

    private function crediterCompte($compteModel, $compte, $montant)
    {
        return $compteModel->update($compte['id'], ['solde' => $compte['solde'] + $montant]);
    }

    private function debiterCompte($compteModel, $compte, $montant)
    {
        return $compteModel->update($compte['id'], ['solde' => $compte['solde'] - $montant]);
    }

    private function getHistoriqueAvecSens($compte)
    {
        $transactionModel = new TransactionModel();
        $transactions = $transactionModel->getHistorique($compte['id']);

        foreach ($transactions as $key => $transaction) {
            if ($transaction['compte_destination_id'] == $compte['id']) {
                $transactions[$key]['sens'] = 'in';
            } else {
                $transactions[$key]['sens'] = 'out';
            }
        }

        return $transactions;
    }

    private function retourErreur($message)
    {
        return redirect()->back()->withInput()->with('error', $message);
    }

    private function jsonErreur($message, $extra = [])
    {
        $data = array_merge($extra, ['error' => $message]);

        if (function_exists('csrf_hash')) {
            $data['csrf_hash'] = csrf_hash();
        }

        return $this->response->setJSON($data);
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
