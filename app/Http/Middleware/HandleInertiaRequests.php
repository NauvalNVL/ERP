<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'layouts/app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'csrf' => function () {
                return csrf_token();
            },
            'appName' => config('app.name'),

            'auth' => function () use ($request) {
                return [
                    'user' => $request->user(),
                    'permissions' => $request->user() ? $request->user()->getPermissionsArray() : [],
                ];
            },
            'flash' => function () {
                return [
                    'message' => session('message'),
                    'error' => session('error'),
                    'success' => session('success'),
                ];
            },
        ]);
    }
}
