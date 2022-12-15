<?php

namespace App\Http\Controllers;

class FileController extends Controller
{
    public function serve($path)
    {
        try {
            if (request("disk") === "s3") {
                return storage_disk("s3")->response($path);
            }

            if (request("disk") === "public") {
                return response()->file(storage_path("app/public/$path"));
            }

            return response()->file(storage_path("app/$path"));
        } catch (\Throwable $e) {
            abort(404);
        }
    }
}
