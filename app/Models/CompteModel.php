<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ClientModel;

class CompteModel extends Model
{
    protected $table = 'comptes';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'client_id',
        'numero_compte',
        'solde',
        'actif'
    ];

    public function getAllWithClient()
    {
        return $this
            ->select('comptes.*')
            ->join('clients', 'clients.id = comptes.client_id')
            ->findAll();
    }
}