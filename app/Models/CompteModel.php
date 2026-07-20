<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteModel extends Model
{
    protected $table         = 'comptes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'client_id',
        'numero_compte',
        'solde',
        'statut',
    ];

    public function getAllWithClient()
    {
        return $this
            ->select('comptes.*, clients.telephone')
            ->join('clients', 'clients.id = comptes.client_id')
            ->findAll();
    }

    public function getCompteByClientId($client_id)
    {
        return $this->where('client_id', $client_id)->first();
    }

    
}