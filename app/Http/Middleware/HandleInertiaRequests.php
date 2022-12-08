<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed[]
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user("web"),
                "lead" => $request->user("lead")
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'successMessage' => fn () => $request->session()->get('successMessage'),
                'infoMessage' => fn () => $request->session()->get('infoMessage'),
                'warningMessage' => fn () => $request->session()->get('warningMessage'),
                'errorMessage' => fn () => $request->session()->get('errorMessage'),
                'enrollmentSuccessMessage' => fn () => $request->session()->get('enrollmentSuccessMessage'),
                "openCpfLink" => fn () => $request->session()->get("openCpfLink")
            ],
        ]);
    }
}
