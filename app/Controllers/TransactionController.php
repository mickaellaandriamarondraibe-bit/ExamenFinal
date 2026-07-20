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

    if (!$this->request->is('post')) {
        return view('transaction/transfert');
    }

    $montant = (float) $this->request->getPost('montant');

    $telephoneDestinataire = preg_replace(
        '/\D/',
        '',
        (string) $this->request->getPost('telephone')
    );

    $priseEnChargeCommission = $this->request
        ->getPost('prise_en_charge_commission') ? 1 : 0;

    $clientId = session()->get('client_id');

    if (!$clientId) {
        return redirect()->to('/');
    }

    if ($montant <= 0) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Montant invalide');
    }

    if (strlen($telephoneDestinataire) !== 10) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Numéro du destinataire invalide');
    }

    // Compte source
    $compteSource = $compteModel->getCompteByClientId($clientId);

    if (!$compteSource) {
        return redirect()->back()
            ->with('error', 'Compte source introuvable');
    }

    // Vérification du préfixe
    $prefixe = substr($telephoneDestinataire, 0, 3);

    $prefixData = $prefixModel->findByPrefixe($prefixe);

    if (!$prefixData) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Préfixe non valide ou inactif');
    }

    /*
     * NULL signifie que le numéro appartient à notre opérateur.
     * Une valeur signifie que le numéro appartient à un autre opérateur.
     */
    $autreOperateurId = !empty($prefixData['autre_operateur_id'])
        ? (int) $prefixData['autre_operateur_id']
        : null;

    // Compte destination
    $compteDestination = $compteModel->getCompteByTelephone(
        $telephoneDestinataire
    );

    if (!$compteDestination) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Destinataire introuvable');
    }

    $typeOperationId = 3; // TRANSFERT

    // Recherche des frais selon le montant et l’opérateur
    $bareme = $baremeFraisModel->getFraisByMontant(
        $typeOperationId,
        $montant,
        $autreOperateurId
    );

    if (!$bareme) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'Aucun barème de transfert ne correspond à ce montant'
            );
    }

    $frais = (float) $bareme['frais'];

    // Commission appliquée uniquement vers un autre opérateur
    $commission = 0;
    $pourcentageCommission = 0;

    if ($autreOperateurId !== null) {
        $commissionData = $commissionModel->getByAutreOperateurId(
            $autreOperateurId
        );

        if (!$commissionData) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Commission inter-opérateur non configurée'
                );
        }

        $pourcentageCommission = (float) $commissionData['pourcentage'];

        $commission = round(
            $montant * $pourcentageCommission / 100
        );
    }

    /*
     * Case cochée :
     * le client paie la commission en supplément.
     *
     * Case non cochée :
     * la commission est retirée du montant reçu.
     */
    if ($priseEnChargeCommission === 1) {
        $montantRecu = $montant;
        $totalADebiter = $montant + $frais + $commission;
    } else {
        $montantRecu = $montant - $commission;
        $totalADebiter = $montant + $frais;
    }

    if ($montantRecu <= 0) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'La commission est supérieure ou égale au montant envoyé'
            );
    }

    if ((float) $compteSource['solde'] < $totalADebiter) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'Solde insuffisant. Total requis : '
                . number_format($totalADebiter, 0, ',', ' ')
                . ' Ar'
            );
    }

    /*
     * Transaction SQL :
     * soit toutes les opérations réussissent,
     * soit aucune modification n’est conservée.
     */
    $db = db_connect();
    $db->transStart();

    $insertedId = $transactionModel->enregistrerTransfert(
        $compteSource['id'],
        $compteDestination['id'],
        $typeOperationId,
        $montant,
        $montantRecu,
        $frais,
        $commission,
        $priseEnChargeCommission,
        $autreOperateurId
    );

    if (!$insertedId) {
        $db->transRollback();

        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                "Erreur lors de l'enregistrement de la transaction"
            );
    }

    // Débiter le compte source
    $compteModel->update($compteSource['id'], [
        'solde' => (float) $compteSource['solde'] - $totalADebiter
    ]);

    // Créditer le montant réellement reçu
    $compteModel->update($compteDestination['id'], [
        'solde' => (float) $compteDestination['solde'] + $montantRecu
    ]);

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                "Erreur lors de l'exécution du transfert"
            );
    }

    return redirect()->to('/solde')
        ->with(
            'success',
            'Transfert effectué avec succès. '
            . 'Montant saisi : '
            . number_format($montant, 0, ',', ' ')
            . ' Ar, montant reçu : '
            . number_format($montantRecu, 0, ',', ' ')
            . ' Ar, frais : '
            . number_format($frais, 0, ',', ' ')
            . ' Ar, commission : '
            . number_format($commission, 0, ',', ' ')
            . ' Ar, total débité : '
            . number_format($totalADebiter, 0, ',', ' ')
            . ' Ar'
        );
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
    $compteModel = new \App\Models\CompteModel();
    $prefixModel = new \App\Models\PrefixModel();
    $baremeModel = new \App\Models\BaremeFraisModel();
    $commissionModel = new \App\Models\CommissionInterOperateurModel();
    $transactionModel = new \App\Models\TransactionModel();

    $clientId = session()->get('client_id');

    if (!$clientId) {
        return redirect()->to('/');
    }

    $montantTotal = (int) $this->request->getPost('montant_total');

    $telephones = $this->request->getPost('telephones');

    $priseEnChargeCommission =
        $this->request->getPost('prise_en_charge_commission') ? 1 : 0;

    if ($montantTotal <= 0) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Le montant total est invalide');
    }

    if (!is_array($telephones) || count($telephones) < 2) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'Vous devez saisir au moins deux destinataires'
            );
    }

    $telephonesNettoyes = [];

    foreach ($telephones as $telephone) {
        $telephone = preg_replace('/\D/', '', (string) $telephone);

        if (strlen($telephone) !== 10) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Tous les numéros doivent contenir 10 chiffres'
                );
        }

        $telephonesNettoyes[] = $telephone;
    }

    /*
     * Éviter les numéros en double.
     */
    if (count($telephonesNettoyes) !== count(array_unique($telephonesNettoyes))) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'Un même numéro ne peut pas être ajouté plusieurs fois'
            );
    }

    $compteSource = $compteModel->getCompteByClientId($clientId);

    if (!$compteSource) {
        return redirect()->back()
            ->with('error', 'Compte source introuvable');
    }

    $nombreDestinataires = count($telephonesNettoyes);

    /*
     * Division du montant total.
     */
    $montantBase = intdiv($montantTotal, $nombreDestinataires);
    $reste = $montantTotal % $nombreDestinataires;

    if ($montantBase <= 0) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'Le montant total est trop faible pour ce nombre de destinataires'
            );
    }

    $typeOperationId = 3;

    $detailsTransferts = [];

    $autreOperateurCommun = null;
    $premierOperateurDefini = false;

    $totalFrais = 0;
    $totalCommission = 0;
    $totalMontantRecu = 0;

    foreach ($telephonesNettoyes as $index => $telephone) {
        /*
         * Le reste est ajouté au premier destinataire.
         */
        $montantIndividuel = $montantBase;

        if ($index === 0) {
            $montantIndividuel += $reste;
        }

        $prefixe = substr($telephone, 0, 3);

        $prefixData = $prefixModel
            ->where('prefixe', $prefixe)
            ->where('actif', 1)
            ->first();

        if (!$prefixData) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Le préfixe du numéro ' . $telephone . ' est invalide'
                );
        }

        $autreOperateurId = !empty($prefixData['autre_operateur_id'])
            ? (int) $prefixData['autre_operateur_id']
            : null;

        /*
         * Tous les numéros doivent appartenir au même opérateur.
         */
        if (!$premierOperateurDefini) {
            $autreOperateurCommun = $autreOperateurId;
            $premierOperateurDefini = true;
        } elseif ($autreOperateurId !== $autreOperateurCommun) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Tous les destinataires doivent appartenir au même opérateur'
                );
        }

        $compteDestination = $compteModel
            ->getCompteByTelephone($telephone);

        if (!$compteDestination) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Le compte correspondant au numéro '
                    . $telephone
                    . ' est introuvable'
                );
        }

        if ((int) $compteDestination['id'] === (int) $compteSource['id']) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Votre propre numéro ne peut pas être destinataire'
                );
        }

        /*
         * Recherche du barème avec le montant individuel.
         */
        $baremeQuery = $baremeModel
            ->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montantIndividuel)
            ->where('montant_max >=', $montantIndividuel);

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
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Aucun barème ne correspond au montant '
                    . number_format($montantIndividuel, 0, ',', ' ')
                    . ' Ar'
                );
        }

        $frais = (int) $bareme['frais'];

        $commission = 0;

        if ($autreOperateurId !== null) {
            $commissionData = $commissionModel
                ->where(
                    'autre_operateur_id',
                    $autreOperateurId
                )
                ->first();

            if (!$commissionData) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Commission inter-opérateur non configurée'
                    );
            }

            $pourcentage = (float) $commissionData['pourcentage'];

            $commission = (int) round(
                $montantIndividuel * $pourcentage / 100
            );
        }

        if ($priseEnChargeCommission === 1) {
            $montantRecu = $montantIndividuel;
        } else {
            $montantRecu = $montantIndividuel - $commission;
        }

        if ($montantRecu <= 0) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'La commission est trop élevée pour le numéro '
                    . $telephone
                );
        }

        $totalFrais += $frais;
        $totalCommission += $commission;
        $totalMontantRecu += $montantRecu;

        $detailsTransferts[] = [
            'telephone'          => $telephone,
            'compte_destination' => $compteDestination,
            'montant'            => $montantIndividuel,
            'montant_recu'       => $montantRecu,
            'frais'              => $frais,
            'commission'         => $commission,
            'autre_operateur_id' => $autreOperateurId
        ];
    }

    /*
     * Calcul du total à débiter.
     */
    if ($priseEnChargeCommission === 1) {
        $totalADebiter =
            $montantTotal
            + $totalFrais
            + $totalCommission;
    } else {
        $totalADebiter =
            $montantTotal
            + $totalFrais;
    }

    if ((int) $compteSource['solde'] < $totalADebiter) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                'Solde insuffisant. Total requis : '
                . number_format($totalADebiter, 0, ',', ' ')
                . ' Ar'
            );
    }

    /*
     * Toutes les opérations doivent réussir ensemble.
     */
    $db = db_connect();
    $db->transStart();

    foreach ($detailsTransferts as $detail) {
        $transactionModel->enregistrerTransfertMultiple(
            $compteSource['id'],
            $detail['compte_destination']['id'],
            $typeOperationId,
            $detail['montant'],
            $detail['montant_recu'],
            $detail['frais'],
            $detail['commission'],
            $priseEnChargeCommission,
            $detail['autre_operateur_id']
        );

        $nouveauSoldeDestination =
            (int) $detail['compte_destination']['solde']
            + $detail['montant_recu'];

        $compteModel->update(
            $detail['compte_destination']['id'],
            [
                'solde' => $nouveauSoldeDestination
            ]
        );
    }

    /*
     * Le compte source est débité une seule fois.
     */
    $compteModel->update(
        $compteSource['id'],
        [
            'solde' => (int) $compteSource['solde'] - $totalADebiter
        ]
    );

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()
            ->withInput()
            ->with(
                'error',
                "Une erreur est survenue pendant l'envoi multiple"
            );
    }

    return redirect()->to('/accueil')
        ->with(
            'success',
            'Transfert multiple effectué vers '
            . $nombreDestinataires
            . ' destinataires. Total débité : '
            . number_format($totalADebiter, 0, ',', ' ')
            . ' Ar'
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
}