<?php

namespace NormanHuth\NovaPerspectives\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NormanHuth\NovaPerspectives\PerspectiveHelper;
use ReflectionException;

class PerspectiveController extends Controller
{
    /**
     * @param Request $request
     * @throws ReflectionException
     * @return void
     */
    public function switch(Request $request): void
    {
        $perspective = $request->input('perspective');
        $perspectives = PerspectiveHelper::getPerspectives($request);

        if ($perspectives->where('slug', $perspective)->count()) {
            PerspectiveHelper::setSelectedPerspective($perspective);
        }
    }
}
