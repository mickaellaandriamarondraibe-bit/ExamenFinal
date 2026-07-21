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
        'epargne',
        'taux_epargne'
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

    public function createCompte($client_id)
    {
        $numero_compte = $this->genererNumeroCompte();

        $data = [
            'client_id'     => $client_id,
            'numero_compte' => $numero_compte,
            'solde'         => 0,
            'statut'        => 'ACTIF',
            'epargne'       => 0 ,
            'taux_epargne'  =>0
        ];

        return $this->insert($data);
    }

    private function genererNumeroCompte()
    {
        // Génère un numéro de compte unique, ex: CPT + timestamp + aléatoire
        do {
            $numero = 'CPT' . date('ymd') . rand(1000, 9999);
        } while ($this->where('numero_compte', $numero)->first());

        return $numero;
    }

 


    public function getCompteByTelephone($telephone)
    {
        return $this->select('comptes.*')
                    ->join('clients', 'clients.id = comptes.client_id')
                    ->where('clients.telephone', $telephone)
                    ->first();
    }
}