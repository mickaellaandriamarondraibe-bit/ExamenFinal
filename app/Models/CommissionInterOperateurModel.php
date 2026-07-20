<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionInterOperateurModel extends Model
{
    protected $table = 'commissions_inter_operateurs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'autre_operateur_id',
        'pourcentage'
    ];

    public function getByAutreOperateurId($autreOperateurId)
    {
        return $this
            ->where('autre_operateur_id', $autreOperateurId)
            ->first();
    }
}