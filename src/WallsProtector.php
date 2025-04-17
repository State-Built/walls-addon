<?php


namespace State\Walls;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Statamic\Auth\Protect\Protectors\Protector;
use Statamic\Facades\Entry;

class WallsProtector extends Protector
{
    public function protect()
    {
        $allowedGates = Arr::get($this->config, 'allowed');
        $hasAccess = false;

        if (Auth::check()) {
            $hasAccess = $this->checkUsersAccess($allowedGates);
        }

        abort_if(!$hasAccess, redirect(
            Arr::get($this->config, 'redirect_url'),
        ));
    }

    protected function checkUsersAccess(array $allowedGates): bool
    {
        return (bool) collect($allowedGates)->first(function ($item) {
            $entry = Entry::query()
                ->where('collection', 'walls') // make configurable?
                ->where('slug', $item)
                ->first();

            if($entry === null) {
                return false;
            }

            return Wall::create($item, $entry->toArray())
                ->userCanPass(Auth::user());
        });
    }


}
