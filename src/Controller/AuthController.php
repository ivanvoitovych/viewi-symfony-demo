<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login(Request $request): Response
    {
        // get username and password

        $body = $request->getContent();
        $data = json_decode($body, true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        if ($username === 'admin' && $password === '123qwe') {
            // Authorize
            $token = 'my-token-jQY7e9gwVXqJIOTs61AerNa1EUeLZAwR';
            $expires = time() + 36000;
            $cookie = Cookie::create('AUTH', $token,  $expires);
            $response = new Response(json_encode(['token' => $token]));
            $response->headers->setCookie($cookie);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $response = new Response('Invalid username or password!', Response::HTTP_BAD_REQUEST);
        return $response;
    }

    public function logout()
    {
        $response = new Response('You are logged out successfully');
        $response->headers->clearCookie('AUTH');
        return $response;
    }

    public function me(Request $request)
    {
        $token = $request->cookies->get('AUTH', null);
        $user = $token ? ['name' => 'Symfony', 'role' => 'admin'] : null;
        $response = new Response(json_encode(['token' => $token, 'user' => $user]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
