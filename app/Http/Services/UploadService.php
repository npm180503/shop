<?php


namespace App\Http\Services;


class UploadService
{
    public function store($request, string $fileInput = "file")
    {
        if ($request->hasFile($fileInput)) {
            try {
                // Validate file type
                $file = $request->file($fileInput);
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return [
                        'error' => true,
                        'message' => 'Chỉ chấp nhận file hình ảnh (JPG, PNG, GIF)'
                    ];
                }

                $name = $file->getClientOriginalName();
                $pathFull = 'uploads/' . date("Y/m/d");

                $file->storeAs(
                    'public/' . $pathFull,
                    $name
                );

                return [
                    'error' => false,
                    'url' => '/storage/' . $pathFull . '/' . $name
                ];
            } catch (\Exception $error) {
                return [
                    'error' => true,
                    'message' => 'Có lỗi khi upload file'
                ];
            }
        }
        
        return [
            'error' => true,
            'message' => 'Vui lòng chọn file'
        ];
    }
}
?>