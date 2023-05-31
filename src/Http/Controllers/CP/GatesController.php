<?php


namespace State\Walls\Http\Controllers\CP;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use State\Walls\Wall;
use State\Walls\GateBlueprint;

class GatesController extends CpController
{

    public function index()
    {
        // todo: authorize User can view.

        // todo: get all Walls.

        return view('walls::cp.index', []);
    }

    public function create()
    {
        $blueprint = GateBlueprint::make();
        $fields    = $blueprint->fields()->preProcess();

        return view('walls::cp.create', [
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

        Wall::create($values->toArray());



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