<?php

namespace Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use function App\Filters\auth;
use function App\Filters\redirect;
use function App\Filters\site_url;

class AuthFilter implements FilterInterface
{
    /**
     * Before filter method : redirect to login page if not logged in
     * @param RequestInterface $request
     * @param $arguments
     * @return RequestInterface|ResponseInterface|string|void|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Pas d'action après
    }
}