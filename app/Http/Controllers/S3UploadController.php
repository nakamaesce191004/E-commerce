<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;

class S3UploadController extends Controller
{
    /**
     * Upload KTP directly to S3 and return the public URL.
     */
    public function uploadKtp(Request $request)
    {
        $request->validate([
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file = $request->file('ktp');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . time() . '.' . $file->getClientOriginalExtension();

        // Store directly to the S3 disk configured in env
        $path = $file->storeAs('ktp', $filename, 's3');

        $url = Storage::disk('s3')->url($path);

        return response()->json(['path' => $path, 'url' => $url], 201);
    }

    /**
     * Generate a presigned PUT URL for direct browser upload to S3.
     */
    public function presign(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'content_type' => 'required|string'
        ]);

        $s3 = config('filesystems.disks.s3');
        $bucket = $s3['bucket'];

        $user = $request->user();
        $ext = pathinfo($request->filename, PATHINFO_EXTENSION);
        $key = 'ktp/' . ($user ? $user->id : 'guest') . '/' . time() . '-' . Str::slug(pathinfo($request->filename, PATHINFO_FILENAME)) . '.' . $ext;

        $client = new S3Client([
            'version' => 'latest',
            'region' => $s3['region'],
            'credentials' => [
                'key' => $s3['key'],
                'secret' => $s3['secret'],
            ],
            'endpoint' => $s3['endpoint'] ?? null,
        ]);

        $cmd = $client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $key,
            'ContentType' => $request->content_type,
            'ACL' => 'public-read'
        ]);

        $req = $client->createPresignedRequest($cmd, '+5 minutes');
        $presignedUrl = (string) $req->getUri();

        $publicUrl = Storage::disk('s3')->url($key);

        return response()->json(['url' => $presignedUrl, 'key' => $key, 'public_url' => $publicUrl]);
    }
}
