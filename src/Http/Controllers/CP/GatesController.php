<?php


namespace State\Gated\Http\Controllers\CP;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use State\Gated\Gate;
use State\Gated\GateBlueprint;

class GatesController extends CpController
{

    public function index()
    {
        // todo: authorize User can view.

        // todo: get all Gates.

        return view('gated::cp.index', []);
    }

    public function create()
    {
        $blueprint = GateBlueprint::make();
        $fields    = $blueprint->fields()->preProcess();

        return view('gated::cp.create', [
            'blueprint' => $blueprint->toPublishArray(),
            'meta'      => $fields->meta(),
            'values'    => $fields->values(),
        ]);
    }

    public function store(Request $request)
    {
        $blueprint = GateBlueprint::make();
        $fields = $blueprint->fields()->addValues($request->all());
        $fields->validate();
        $values = $fields->process()->values();

        Gate::create($values->toArray());



    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

}