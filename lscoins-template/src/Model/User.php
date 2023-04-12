<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model;

use DateTime;

final class User
{
    private int $id;
    private string $email;
    private string $password;
    private int $coins;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        string $email,
        string $password,
        int $coins,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->coins = $coins;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function coins(): int
    {
        return $this->coins;
    }

}