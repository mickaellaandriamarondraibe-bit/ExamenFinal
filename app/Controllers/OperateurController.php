<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class OperateurController extends BaseController
{
    public function loginPage()
    {
        return view('auth/login_operateur');
    }

    public function login()
    {
        $model = new OperateurModel();

        $email = $this->request->getPost('email');
        $mot_de_passe = $this->request->getPost('mot_de_passe');

        $operateur = $model->where('email', $email)->first();

        if (!$operateur) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Opérateur introuvable');
        }

        if (!password_verify($mot_de_passe, $operateur['mot_de_passe'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mot de passe incorrect');
        }

        session()->set([
            'operateur_id' => $operateur['id'],
            'nom' => $operateur['nom'],
            'email' => $operateur['email'],
            'role' => 'operateur',
            'isLoggedIn' => true
        ]);

        return redirect()->to('/operateur/client');
    }

    public function dashboard()
    {
        return view('/operateur/client');
    }

    public function logout()
    {
        $estOperateur = session()->get('role') === 'operateur';

        session()->destroy();

        if ($estOperateur) {
            return redirect()->to('/operateur/login');
        }

        return redirect()->to('/');
    }
}
