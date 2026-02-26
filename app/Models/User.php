<?php

namespace App\Models;

class User
{
    private $users = [
        ['name' => 'Ivan', 'age' => 28],
        ['name' => 'Maria', 'age' => 25],
        ['name' => 'Petar', 'age' => 32],
    ];

    public function all()
    {
        return $this->users;
    }
}