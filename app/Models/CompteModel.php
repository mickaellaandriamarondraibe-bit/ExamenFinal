<?php

namespace App\Models;

use CodeIgniter\Model;

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
            ->select('comptes.*, clients.nom, clients.prenom')
            ->join('clients', 'clients.id = comptes.client_id')
            ->findAll();
    }
}