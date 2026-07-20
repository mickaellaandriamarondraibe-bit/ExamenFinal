<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'prefixe',
        'actif'
    ];

    protected $returnType = 'array';

    protected $useTimestamps = false;

    public function findByPrefixe($prefixe)
    {
        return $this->where('prefixe', $prefixe)->first();
    }
}