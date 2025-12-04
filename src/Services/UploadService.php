<?php

namespace Rinnsan\RinnSanWeb\Services;

class UploadService extends Service
{
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $maxFileSize = 5242880;
    private $uploadPath;

    public function __construct()
    {
        $this->uploadPath = __DIR__ . '/../../public/uploads/';
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function upload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Lỗi upload'];
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mimeType, $this->allowedTypes)) {
            return ['error' => 'Loại file không được phép'];
        }
        if ($file['size'] > $this->maxFileSize) {
            return ['error' => 'File quá lớn'];
        }
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        $filePath = $this->uploadPath . $fileName;
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return ['error' => 'Không thể lưu file'];
        }
        return [
            'filename' => $fileName,
            'url' => '/uploads/' . $fileName,
            'size' => $file['size'],
            'type' => $mimeType
        ];
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
                $result['errors'][] = 'File ' . $file['name'] . ': ' . $uploaded['error'];
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
        $filePath = $this->uploadPath . basename($filename);
        if (!file_exists($filePath)) {
            return false;
        }
        return unlink($filePath);
    }
}

