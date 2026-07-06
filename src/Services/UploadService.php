<?php

namespace Rinnsan\RinnSanWeb\Services;

class UploadService
{
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $maxFileSize = 5242880; // 5MB
    private $uploadPath;

    public function __construct()
    {
        // Đi từ src/Services ra root -> vào public/uploads
        // dirname(__DIR__, 2) trả về thư mục gốc của project
        $this->uploadPath = dirname(__DIR__, 2) . '/public/uploads/';
        
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    public function upload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Lỗi PHP Upload Code: ' . $file['error']];
        }

        // Kiểm tra loại file (MIME type)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            return ['error' => 'Chỉ chấp nhận file ảnh (jpg, png, gif, webp)'];
        }

        if ($file['size'] > $this->maxFileSize) {
            return ['error' => 'File quá lớn (> 5MB)'];
        }

        // Tạo tên file ngẫu nhiên
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $destination = $this->uploadPath . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'filename' => $fileName,
                // [QUAN TRỌNG] Thêm filepath để khớp với Frontend
                'filepath' => 'uploads/' . $fileName, 
            'url' => '/uploads/' . $fileName,
            'size' => $file['size'],
            'type' => $mimeType
        ];
    }

        return ['error' => 'Không thể ghi file vào thư mục public/uploads'];
    }

    public function uploadMultiple($files)
    {
        $result = ['files' => [], 'errors' => []];
        $count = count($files['name']);
        
        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            
            $uploaded = $this->upload($file);
            
            if (isset($uploaded['error'])) {
                $result['errors'][] = $file['name'] . ': ' . $uploaded['error'];
            } else {
                $result['files'][] = $uploaded;
            }
        }
        
        $result['success_count'] = count($result['files']);
        $result['error_count'] = count($result['errors']);
        
        return $result;
    }

    public function delete($filename)
    {
        // Chỉ lấy tên file, bỏ đường dẫn để an toàn
        $filePath = $this->uploadPath . basename($filename);
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}

