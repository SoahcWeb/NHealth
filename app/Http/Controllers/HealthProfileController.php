<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateHealthProfileRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HealthProfileController extends Controller
{
    /**
     * Show the authenticated user's health profile form.
     */
    public function edit(Request $request): View
    {
        return view('health-profile.edit', [
            'healthProfile' => $request->user()->healthProfile,
        ]);
    }

    /**
     * Update or create the authenticated user's health profile.
     */
    public function update(UpdateHealthProfileRequest $request): RedirectResponse
    {
        $healthProfile = $request->user()->healthProfile()->updateOrCreate(
            [],
            $request->validated(),
        );

        return redirect()
            ->route('health-profile.edit')
            ->with('status', $healthProfile->wasRecentlyCreated ? 'Health profile created.' : 'Health profile updated.');
    }
}
