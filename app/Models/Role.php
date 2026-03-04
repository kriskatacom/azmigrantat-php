<?php

namespace App\Models;

class Role extends Model
{
    protected string $table = 'roles';

    public function getAllRoles(): array
    {
        return $this->all(['order' => 'id ASC']);
    }
}
