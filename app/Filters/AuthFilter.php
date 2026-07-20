<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (!empty($arguments)) {
            $roleAutorise = $arguments[0];
            $roleConnecte = session()->get('role');

            if ($roleConnecte !== $roleAutorise) {
                if ($roleConnecte === 'client') {
                    return redirect()->to('/client/dashboard');
                }

                if ($roleConnecte === 'operateur') {
                    return redirect()->to('//operateur/client');
                }

                return redirect()->to('/');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}