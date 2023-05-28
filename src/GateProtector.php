<?php


namespace State\Gated;


use Illuminate\Support\Facades\Auth;
use Statamic\Auth\Protect\Protectors\Protector;
use Statamic\Facades\Entry;

class GateProtector extends Protector
{
    public function protect()
    {
        $allowedGates = array_get($this->config, 'allowed');
        $hasAccess = false;

        if (Auth::check()) {
            $hasAccess = $this->checkUsersAccess($allowedGates);
        }

        abort_if(!$hasAccess, redirect(
            array_get($this->config, 'redirect_url'),
        ));
    }

    protected function checkUsersAccess(array $allowedGates): bool
    {
        return (bool)array_first($allowedGates, function ($item) {
            $entry = Entry::query()
                ->where('collection', 'gates') // make configurable?
                ->where('slug', $item)
                ->first();

            if($entry === null) {
                return false;
            }

            return Gate::create($item, $entry->toArray())
                ->userCanPass(Auth::user());
        });
    }


}