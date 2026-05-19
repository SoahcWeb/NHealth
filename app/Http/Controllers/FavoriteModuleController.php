<?php

namespace App\Http\Controllers;

use App\Models\FavoriteModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteModuleController extends Controller
{
    /**
     * Toggle the authenticated user's NHealth module without touching health data.
     */
    public function toggleNhealth(Request $request): RedirectResponse
    {
        $favoriteModule = $request->user()->favoriteModules()
            ->where('module_key', FavoriteModule::NHEALTH_KEY)
            ->first();

        if ($favoriteModule) {
            $favoriteModule->fill([
                'module_name' => FavoriteModule::NHEALTH_NAME,
                'is_active' => ! $favoriteModule->is_active,
                'last_toggled_at' => now(),
            ])->save();
        } else {
            $favoriteModule = $request->user()->favoriteModules()->create([
                'module_key' => FavoriteModule::NHEALTH_KEY,
                'module_name' => FavoriteModule::NHEALTH_NAME,
                'is_active' => true,
                'last_toggled_at' => now(),
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'status',
                $favoriteModule->is_active
                    ? FavoriteModule::NHEALTH_NAME . ' activé. Vos données de santé restent intactes.'
                    : FavoriteModule::NHEALTH_NAME . ' désactivé. Vos données de santé restent intactes.',
            );
    }
}
