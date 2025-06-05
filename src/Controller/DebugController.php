<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DebugController
{
    #[Route('/debug', name: 'debug_test')]
    public function index(): Response
    {
        return new Response('<h1>Route publique accessible</h1>');
    }
}
