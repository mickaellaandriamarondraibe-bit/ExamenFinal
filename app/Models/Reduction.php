<?php

namespace App\Models;

use CodeIgniter\Model;

class Reduction extends Model
{
    protected $table = 'reduction';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'reduction'
    ];

    protected $returnType = 'array';

    protected $useTimestamps = false;

}