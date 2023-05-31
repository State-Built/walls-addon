<?php


namespace State\Walls;



use Statamic\Contracts\Auth\User;

class PaymentWall extends Wall
{

    public function getPrice() : int
    {
        return $this->config['price'] * 100;
    }


    public function accessDuration() : int|null
    {
        return $this->config['expires_after'] ?? null;
    }

    public function toArray() : array
    {
        return array_merge(parent::toArray(), [
            'handle' => $this->getHandle(),
            'price' => $this->getPrice() / 100
        ]);
    }

    protected function userGateArray() : array
    {
        $duration = $this->accessDuration();

        return array_merge(parent::userGateArray(), [
            'expires_at' => $duration ? now()->addDays($duration)->toIso8601String() : null,
            'type' => 'payment',
        ]);
    }

    public function userCanPass(User $user): bool
    {
        $userGate = $this->getGateDataFromUser($user);

        if(!$userGate) {
            return false;
        }

        if(isset($userGate['expires_at']) && now()->isAfter($userGate['expires_at'])) {
            return false;
        }

        return true;
    }
}