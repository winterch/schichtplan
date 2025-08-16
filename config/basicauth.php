<?php

return( function () {
    $usersString = env('BASIC_AUTH_USERS', '');
    $usersArray = [];

    foreach (explode(',', $usersString) as $namePasswordPair) {
        $namePasswordPair = trim($namePasswordPair);
        list($name, $password) = explode(':', $namePasswordPair, 2);
        $usersArray[] = [$name => $password];
    }
    
    return [
        'users' => collect($usersArray),
    ];
})();
