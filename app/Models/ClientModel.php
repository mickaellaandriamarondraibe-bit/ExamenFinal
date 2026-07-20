<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['prefix_id', 'telephone', 'created_at'];

    public function getClient($telephone = null)
    {
        if ($telephone) {
            return $this->asArray()
                        ->where(['telephone' => $telephone])
                        ->first();
        }
        return null;
    }


    public function createClient($prefix_id, $telephone)
    {
        $data = [
            'prefix_id' => $prefix_id,
            'telephone' => $telephone,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }

}