<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'email', 'password', 'role', 'prodi'];
    protected $useTimestamps    = false;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
