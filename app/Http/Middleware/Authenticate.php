<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            parent::unauthenticated($request, $guards);
        }

        // Determine the login route based on the guard
        $loginRoute = 'siswa.login'; // default

        if (in_array('admin', $guards)) {
            $loginRoute = 'admin.login';
        } elseif (in_array('guru', $guards)) {
            $loginRoute = 'guru.login';
        } elseif (in_array('siswa', $guards)) {
            $loginRoute = 'siswa.login';
        }

        // Throw AuthenticationException with redirectTo set
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            route($loginRoute)
        );
    }
}
