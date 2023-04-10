<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model;

use Salle\LSCoins\Model\User;

interface UserRepository
{
    public function save(User $user): void;
}
