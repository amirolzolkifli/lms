<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerSettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure user is a trainer
        if (!in_array('trainer', $user->roles ?? [])) {
            abort(403, 'Unauthorized. Only trainers can access this page.');
        }

        return view('trainer-settings.index', [
            'settings' => $user->trainer_settings ?? [],
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Ensure user is a trainer
        if (!in_array('trainer', $user->roles ?? [])) {
            abort(403, 'Unauthorized. Only trainers can access this page.');
        }

        $request->validate([
            'chip_brand_id' => 'nullable|string|max:255',
            'chip_api_key' => 'nullable|string|max:255',
            'manual_payment_enabled' => 'nullable|boolean',
            'bank_name' => 'nullable|required_if:manual_payment_enabled,1|string|max:255',
            'bank_account_name' => 'nullable|required_if:manual_payment_enabled,1|string|max:255',
            'bank_account_number' => 'nullable|required_if:manual_payment_enabled,1|string|max:255',
        ]);

        $user->update([
            'trainer_settings' => [
                'chip_brand_id' => $request->chip_brand_id,
                'chip_api_key' => $request->chip_api_key,
                'manual_payment_enabled' => $request->boolean('manual_payment_enabled'),
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
            ],
        ]);

        return redirect()->route('app.trainer.settings.index')
            ->with('success', 'Payment settings updated successfully!');
    }
}
