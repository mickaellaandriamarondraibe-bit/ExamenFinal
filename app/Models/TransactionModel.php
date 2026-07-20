<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'type_operation_id',
        'compte_source_id',
        'compte_destination_id',
        'montant',
        'montant_recu',
        'frais',
        'commission',
        'prise_en_charge_commission',
        'autre_operateur_id',
        'statut',
        'date_transaction'
    ];

    
    public function enregistrerDepot($compte_destination_id, $type_operation_id, $montant, $frais)
    {
        return $this->insert([
            'type_operation_id'      => $type_operation_id,
            'compte_source_id'       => null,
            'compte_destination_id'  => $compte_destination_id,
            'montant'                => $montant,
            'frais'                  => $frais,
            'statut'                 => 'SUCCES',
            'date_transaction'       => date('Y-m-d H:i:s'),
        ]);
    }

    public function enregistrerRetrait($compte_source_id, $type_operation_id, $montant, $frais)
    {
        return $this->insert([
            'type_operation_id'      => $type_operation_id,
            'compte_source_id'       => $compte_source_id,
            'compte_destination_id'  => null,
            'montant'                => $montant,
            'frais'                  => $frais,
            'statut'                 => 'SUCCES',
            'date_transaction'       => date('Y-m-d H:i:s'),
        ]);
    }


    public function enregistrerTransfert(
        $compte_source_id,
        $compte_destination_id,
        $type_operation_id,
        $montant,
        $montant_recu,
        $frais,
        $commission,
        $prise_en_charge_commission,
        $autre_operateur_id = null
    ) {
        return $this->insert([
            'type_operation_id'          => $type_operation_id,
            'compte_source_id'           => $compte_source_id,
            'compte_destination_id'      => $compte_destination_id,
            'montant'                    => $montant,
            'montant_recu'               => $montant_recu,
            'frais'                      => $frais,
            'commission'                 => $commission,
            'prise_en_charge_commission' => $prise_en_charge_commission,
            'autre_operateur_id'         => $autre_operateur_id,
            'statut'                     => 'SUCCES',
            'date_transaction'           => date('Y-m-d H:i:s'),
        ]);
    }

    public function getHistorique($compte_id)
{
    return $this->select('transactions.*, types_operations.libelle AS type_operation')
                ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
                ->groupStart()
                    ->where('compte_source_id', $compte_id)
                    ->orWhere('compte_destination_id', $compte_id)
                ->groupEnd()
                ->orderBy('date_transaction', 'DESC')
                ->findAll();
}

 public function getGainsInternes()
    {
        return $this
            ->select('SUM(frais) AS total')
            ->where('autre_operateur_id', null)
            ->first();
    }

    /**
     * Gains des transactions inter-opérateurs
     * groupés par opérateur
     */
    public function getGainsInterOperateurs()
    {
        return $this
            ->select('
                autre_operateur.id,
                autre_operateur.nom,
                SUM(transactions.frais) AS total_frais,
                SUM(transactions.commission) AS total_commission,
                SUM(transactions.frais + transactions.commission) AS gain_total
            ')
            ->join(
                'autre_operateur',
                'autre_operateur.id = transactions.autre_operateur_id'
            )
            ->where('transactions.autre_operateur_id IS NOT NULL')
            ->groupBy('autre_operateur.id')
            ->findAll();
    }


    public function getMontantsParOperateur()
    {
        return $this
            ->select('
                autre_operateur.id,
                autre_operateur.nom,
                SUM(transactions.montant_recu) AS montant_total
            ')
            ->join(
                'autre_operateur',
                'autre_operateur.id = transactions.autre_operateur_id'
            )
            ->where('transactions.autre_operateur_id IS NOT NULL')
            ->where('transactions.statut', 'SUCCES')
            ->groupBy('autre_operateur.id')
            ->findAll();
    }
}
