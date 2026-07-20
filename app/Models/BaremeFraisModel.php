<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table = 'baremes_frais';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type_operation_id',
        'autre_operateur_id',
        'montant_min',
        'montant_max',
        'frais'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getAllWithTypeOperation()
    {
        return $this
            ->select('baremes_frais.*, types_operations.libelle AS type_operation')
            ->join('types_operations', 'types_operations.id = baremes_frais.type_operation_id')
            ->orderBy('types_operations.libelle', 'ASC')
            ->orderBy('baremes_frais.montant_min', 'ASC')
            ->findAll();
    }

    public function findWithTypeOperation($id)
    {
        return $this
            ->select('baremes_frais.*, types_operations.libelle AS type_operation')
            ->join('types_operations', 'types_operations.id = baremes_frais.type_operation_id')
            ->where('baremes_frais.id', $id)
            ->first();
    }

     public function getFraisByMontant(
        $typeOperationId,
        $montant,
        $autreOperateurId = null
    ) {
        $builder = $this
            ->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant);

        if ($autreOperateurId === null) {
            $builder->where('autre_operateur_id', null);
        } else {
            $builder->where('autre_operateur_id', $autreOperateurId);
        }

        return $builder->first();
    }
}