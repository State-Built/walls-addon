<?php


namespace State\Walls;

use Illuminate\Contracts\Support\Arrayable;
use Statamic\Facades\Entry;
use Statamic\Contracts\Auth\User;

abstract class Wall implements Arrayable
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

    public function setHandle(string $handle): Wall
    {
        $this->handle = $handle;

        return $this;
    }

    public function setConfig(array $config): Wall
    {
        $this->config = $config;

        return $this;
    }

    public function addToUser(User $user)
    {
        $gates = $user->get('walls', []);
        $user->set('walls', array_merge([$this->userGateArray()], $gates));
        $user->save();
    }

    public function userHasGate(User $user): bool
    {
        $handles = array_map(function ($gate) {
            return $gate['handle'];
        }, $user->get('walls', []));

        return in_array($this->handle, $handles);
    }

    abstract public function userCanPass(User $user): bool;

    public static function create(string $handle, array $config): Wall
    {
        $type = $config['type']['value'] ?? $config['type'];

        $gate = match ($type) {
            'payment' => new PaymentWall,
            default => new NullWall,
        };

        return $gate->setHandle($handle)->setConfig($config);
    }

    public static function findBySlug(string $slug)
    {
        $entry = Entry::query()
            ->where('collection', 'walls')
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
        $gates = $user->get('walls', []);

        return array_first($gates, function ($gate) {
            return $gate['handle'] === $this->handle;
        }, false);
    }

    public function toArray(): array
    {
        if (isset($this->config['wall_home']) && isset($this->config['wall_home'][0])) {
            $this->config['wall_home'] = Entry::find($this->config['wall_home'][0])->toArray();
        }

        return $this->config;
    }

}