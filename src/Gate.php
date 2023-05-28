<?php


namespace State\Gated;

use Illuminate\Contracts\Support\Arrayable;
use Statamic\Facades\Entry;
use Statamic\Contracts\Auth\User;

abstract class Gate implements Arrayable
{
    protected $handle;
    protected $config;

    public function getId(): string
    {
        return $this->config['id'];
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setHandle(string $handle): Gate
    {
        $this->handle = $handle;

        return $this;
    }

    public function setConfig(array $config): Gate
    {
        $this->config = $config;

        return $this;
    }

    public function addToUser(User $user)
    {
        $gates = $user->get('gates', []);
        $user->set('gates', array_merge([$this->userGateArray()], $gates));
        $user->save();
    }

    public function userHasGate(User $user): bool
    {
        $handles = array_map(function ($gate) {
            return $gate['handle'];
        }, $user->get('gates', []));

        return in_array($this->handle, $handles);
    }

    abstract public function userCanPass(User $user): bool;

    public static function create(string $handle, array $config): Gate
    {
        $gate = match ($config['type']['value']) {
            'payment' => new PaymentGate,
            default => new NullGate,
        };

        return $gate->setHandle($handle)->setConfig($config);
    }

    public static function findBySlug(string $slug)
    {

        $entry = Entry::query()
            ->where('collection', 'gates')
            ->where('slug', $slug)
            ->first();

        abort_if($entry == null, 404);

        return self::create($slug, $entry->toArray());
    }

    protected function userGateArray(): array
    {
        return [
            'handle' => $this->getHandle(),
            'added_at' => now()->toIso8601String(),
        ];
    }

    public function getGateDataFromUser(User $user): array|false
    {
        $gates = $user->get('gates', []);

        return array_first($gates, function ($gate) {
            return $gate['handle'] === $this->handle;
        }, false);
    }

    public function toArray(): array
    {
        if (isset($this->config['gate_home'][0])) {
            $this->config['gate_home'] = Entry::find($this->config['gate_home'][0])->toArray();
        }

        return $this->config;
    }

}