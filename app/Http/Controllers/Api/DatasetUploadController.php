<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatasetUploadController extends Controller
{
    public function uploadSingleImage(Request $request)
    {
        $this->validateApiKey($request);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $label = $request->input('product_label', 'unknown');
            $cameraId = $request->input('camera_id', '1');

            $cleanLabel = Str::slug($label);
            $fileName = 'cap_' . $cleanLabel . '_' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('datasets', $fileName, 'public');

            return response()->json([
                'status' => 'success',
                'message' => 'Gambar satuan berhasil diterima server Laravel',
                'file_name' => $fileName,
                'path' => Storage::disk('public')->url($path),
                'camera_id' => $cameraId,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal sinkronisasi, file [image_file] tidak terdeteksi',
        ], 400);
    }

    /**
     * Menangkap file ZIP untuk dataset massal (50 foto hasil capture)
     * Tidak butuh product_id, cukup file zip + label
     */
    public function upload(Request $request)
    {
        $this->validateApiKey($request);

        $validated = $request->validate([
            'label' => ['nullable', 'string'],
            'file'  => ['required', 'file', 'mimes:zip', 'max:524288'],
        ]);

        $label = $validated['label'] ?? 'dataset';
        $filename = Str::slug($label) . '_' . time() . '.zip';

        $path = $request->file('file')->storeAs('datasets', $filename, 'public');

        return response()->json([
            'status' => 'success',
            'path'   => Storage::disk('public')->url($path),
        ], 201);
    }

    private function validateApiKey(Request $request)
    {
        $apiKey = $request->bearerToken();
        $expectedKey = config('services.raspi.api_key');

        abort_unless($apiKey === $expectedKey, 401, 'Invalid API key');
    }
}