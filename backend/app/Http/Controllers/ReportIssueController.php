<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportIssueMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ReportIssueController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('report-issues', 'public');

                // Ensure the path is a string
                if (is_array($imagePath)) {
                    $imagePath = reset($imagePath);
                }
            }

            $data = [
                'name' => (string)$request->input('name'),
                'email' => (string)$request->input('email'),
                'message' => (string)$request->input('message'),
                'image_path' => $imagePath ? (string)$imagePath : null,
            ];

            // Debug log with more details
            Log::info('Report data prepared for email', [
                'data' => $data,
                'image_path_type' => gettype($data['image_path']),
                'image_path_value' => $data['image_path'],
            ]);

            Mail::to('info@universaltechnologies.com')->send(new ReportIssueMail($data));

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json(['message' => 'Report submitted successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up image if error occurred
            if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json(['error' => 'Failed to submit report. Please try again later.'], 500);
        }
    }
}
