<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $plans = Plan::whereIn('name', ['Basic Plan', 'Pro Plan'])->get()->keyBy('name');

        return view('settings.index', [
            'siteName' => setting('site_name', 'Dreams LMS'),
            'registrationOpen' => setting('registration_open', '1'),
            'contactEmail' => setting('contact_email', ''),
            'chipBrandId' => setting('chip_brand_id', ''),
            'chipApiKey' => setting('chip_api_key', ''),
            'plans' => $plans
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'registration_open' => 'required|in:0,1',
            'contact_email' => 'required|email|max:255',
            'chip_brand_id' => 'nullable|string|max:255',
            'chip_api_key' => 'nullable|string|max:255'
        ]);

        set_setting('site_name', $request->site_name);
        set_setting('registration_open', $request->registration_open);
        set_setting('contact_email', $request->contact_email);
        set_setting('chip_brand_id', $request->chip_brand_id);
        set_setting('chip_api_key', $request->chip_api_key);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function updatePricing(Request $request)
    {
        $request->validate([
            'basic_price' => 'required|numeric|min:0',
            'basic_course_limit' => 'required|integer|min:0',
            'basic_content_limit' => 'required|integer|min:0',
            'basic_student_limit' => 'required|integer|min:0',
            'pro_price' => 'required|numeric|min:0',
            'pro_course_limit' => 'required|integer|min:0',
            'pro_content_limit' => 'required|integer|min:0',
            'pro_student_limit' => 'required|integer|min:0',
        ]);

        // Update Basic Plan
        Plan::where('name', 'Basic Plan')->update([
            'price_monthly' => $request->basic_price,
            'course_limit' => $request->basic_course_limit,
            'content_upload_limit' => $request->basic_content_limit,
            'student_limit' => $request->basic_student_limit,
        ]);

        // Update Pro Plan
        Plan::where('name', 'Pro Plan')->update([
            'price_monthly' => $request->pro_price,
            'course_limit' => $request->pro_course_limit,
            'content_upload_limit' => $request->pro_content_limit,
            'student_limit' => $request->pro_student_limit,
        ]);

        return redirect()->route('settings.index', ['tab' => 'pricing'])
            ->with('success', 'Pricing settings updated successfully!');
    }
}
