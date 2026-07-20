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
    protected $allowedFields    = ['type_operation_id','compte_source_id','compte_destination_id','montant','frais','statut','date_transaction'];

    
    public function enregistrerDepot($compte_destination_id, $type_operation_id,$montant,$frais){

         return $this->insert([
            'type_operation_id'      => $type_operation_id,
            'compte_source_id'       => null,
            'compte_destination_id'  => $compte_destination_id,
            'montant'                => $montant,
            'frais'                  => $frais,
            'statut'                 => 'SUCCES',
            'date_transaction' => date('Y-m-d H:i:s'),
        ]);
    }

    public function enregistrerRetrait($compte_source_id, $type_operation_id,$montant,$frais){

         return $this->insert([
            'type_operation_id'      => $type_operation_id,
            'compte_source_id'       => $compte_source_id,
            'compte_destination_id'  => null,
            'montant'                => $montant,
            'frais'                  => $frais,
            'statut'                 => 'SUCCES',
            'date_transaction' => date('Y-m-d H:i:s'),
        ]);
    }


    public function enregistrerTransfert($compte_source_id, $compte_destination_id, $type_operation_id,$montant,$frais){

         return $this->insert([
            'type_operation_id'      => $type_operation_id,
            'compte_source_id'       => $compte_source_id,
            'compte_destination_id'  => $compte_destination_id,
            'montant'                => $montant,
            'frais'                  => $frais,
            'statut'                 => 'SUCCES',
            'date_transaction' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getHistorique($compte_id){
        return $this->groupStart()
                    ->where('compte_source_id',$compte_id)
                    ->orWhere('compte_destination_id',$compte_id)
                    ->groupEnd()
                    ->orderBy('date_transaction','DESC')
                    ->findAll() ;
    }
}
