<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationSettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        
        $settings = NotificationSetting::where('user_id', $user->id)->get();
        
        if ($settings->isEmpty()) {
            $types = array_keys(NotificationSetting::availableTypes());
            foreach ($types as $type) {
                NotificationSetting::create([
                    'user_id' => $user->id,
                    'notification_type' => $type,
                    'email_enabled' => true,
                    'in_app_enabled' => true,
                    'push_enabled' => false,
                ]);
            }
            $settings = NotificationSetting::where('user_id', $user->id)->get();
        }

        return response()->json([
            'settings' => $settings,
            'available_types' => NotificationSetting::availableTypes(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.notification_type' => [
                'required',
                'string',
                Rule::in(array_keys(NotificationSetting::availableTypes()))
            ],
            'settings.*.email_enabled' => 'required|boolean',
            'settings.*.in_app_enabled' => 'required|boolean',
            'settings.*.push_enabled' => 'required|boolean',
        ]);

        foreach ($validated['settings'] as $settingData) {
            NotificationSetting::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'notification_type' => $settingData['notification_type'],
                ],
                [
                    'email_enabled' => $settingData['email_enabled'],
                    'in_app_enabled' => $settingData['in_app_enabled'],
                    'push_enabled' => $settingData['push_enabled'],
                ]
            );
        }

        return response()->json([
            'message' => 'Notification settings updated successfully',
        ]);
    }

    public function updateSingle(Request $request, $type): JsonResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_enabled' => 'sometimes|boolean',
            'in_app_enabled' => 'sometimes|boolean',
            'push_enabled' => 'sometimes|boolean',
        ]);

        $setting = NotificationSetting::updateOrCreate(
            [
                'user_id' => $user->id,
                'notification_type' => $type,
            ],
            $validated
        );

        return response()->json([
            'message' => 'Setting updated successfully',
            'setting' => $setting,
        ]);
    }
}