<?php

class Cookie
{
    public function setCookieAccunt( $user, $token, $role )
    {
        $cookies = [
            'username' => $user,
            'token' => $token,
            'role' => $role
        ];
        setcookie( 'accunt', $cookies, time()+ (86400 * 30 * 365) );
    }

    public function getCookieaccunt()
    {
        if( isset( $_COOKIE['accunt'] ))
        {
            $cookieAccunt = [
                'user' => $_COOKIE['accunt']['username'],
                'token' =>  $_COOKIE['accunt']['token'],
                'role' => $_COOKIE['accunt']['role']
            ];
            return $cookieAccunt;
        } else {
            $cookieAccunt = 'Anda belum Login';
            return $cookieAccunt;
        }
    }

    public function deleteCookieAccunt()
    {
        if( isset( $_COOKIE['accunt'] ) )
        {
            setcookie( 'accunt', '', time() -1 );
        }
    }
}