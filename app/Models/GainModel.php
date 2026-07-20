<?php

namespace App\Models;

use CodeIgniter\Model;

class GainModel extends Model
{
    protected $table = 'transactions';

    public function getTotalGains()
    {
        return $this
            ->selectSum('frais', 'total_gains')
            ->first();
    }

    public function getGainsByType()
    {
        return $this
            ->select('types_operations.libelle, SUM(transactions.frais) AS total')
            ->join(
                'types_operations',
                'types_operations.id = transactions.type_operation_id'
            )
            ->groupBy('types_operations.libelle')
            ->findAll();
    }
}