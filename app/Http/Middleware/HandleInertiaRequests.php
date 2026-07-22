<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    
    protected $rootView = 'layouts.inertia';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
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
         $user = $request->user();
         $subscribed = $request->user()?->subscribed('default') ?? false;

        return [
            ...parent::share($request),
            'flash' => [
                'success' => fn() => $request->session()->get('success')
            ],
            'user' => [
                'user' => $user,
                'subscribed' => $subscribed
            ]
        ];
    }
}
