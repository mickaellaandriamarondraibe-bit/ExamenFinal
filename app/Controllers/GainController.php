<?php

namespace App\Controllers;

use App\Models\GainModel;

class GainController extends BaseController
{
    public function index()
    {
        $model = new GainModel();

        $data = [
            'title' => 'Situation des gains',
            'totalGains' => $model->getTotalGains(),
            'gainsParType' => $model->getGainsByType()
        ];

        return view('gains/index', $data);
    }
}