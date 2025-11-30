<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

class UploadController extends ApiController
{
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $maxFileSize = 5242880; // 5MB
    private $uploadPath = __DIR__ . '/../../../public/uploads/';

    /**
     * Upload file
     * POST /api/upload
     */
    public function upload()
    {
        try {
            if (!isset($_FILES['file'])) {
                return $this->error('Không có file được upload', 400);
            }

            $file = $_FILES['file'];
            
            // Kiểm tra lỗi upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return $this->error('Lỗi upload file: ' . $this->getUploadError($file['error']), 400);
            }

            // Kiểm tra loại file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $this->allowedTypes)) {
                return $this->error('Loại file không được phép. Chỉ chấp nhận: JPG, PNG, GIF, WEBP', 400);
            }

            // Kiểm tra kích thước
            if ($file['size'] > $this->maxFileSize) {
                return $this->error('File quá lớn. Kích thước tối đa: 5MB', 400);
            }

            // Tạo tên file unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $extension;
            $filePath = $this->uploadPath . $fileName;

            // Tạo thư mục nếu chưa có
            if (!is_dir($this->uploadPath)) {
                mkdir($this->uploadPath, 0755, true);
            }

            // Di chuyển file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                return $this->error('Không thể lưu file', 500);
            }

            return $this->success([
                'filename' => $fileName,
                'url' => '/uploads/' . $fileName,
                'size' => $file['size'],
                'type' => $mimeType
            ], 'Upload file thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Upload nhiều file
     * POST /api/upload/multiple
     */
    public function uploadMultiple()
    {
        try {
            if (!isset($_FILES['files'])) {
                return $this->error('Không có file được upload', 400);
            }

            $files = $_FILES['files'];
            $uploadedFiles = [];
            $errors = [];

            // Tạo thư mục nếu chưa có
            if (!is_dir($this->uploadPath)) {
                mkdir($this->uploadPath, 0755, true);
            }

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                    $errors[] = "File {$files['name'][$i]}: " . $this->getUploadError($files['error'][$i]);
                    continue;
                }

                // Kiểm tra loại file
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $files['tmp_name'][$i]);
                finfo_close($finfo);

                if (!in_array($mimeType, $this->allowedTypes)) {
                    $errors[] = "File {$files['name'][$i]}: Loại file không được phép";
                    continue;
                }

                // Kiểm tra kích thước
                if ($files['size'][$i] > $this->maxFileSize) {
                    $errors[] = "File {$files['name'][$i]}: File quá lớn";
                    continue;
                }

                // Tạo tên file
                $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '_' . $i . '.' . $extension;
                $filePath = $this->uploadPath . $fileName;

                if (move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                    $uploadedFiles[] = [
                        'filename' => $fileName,
                        'url' => '/uploads/' . $fileName,
                        'size' => $files['size'][$i],
                        'type' => $mimeType
                    ];
                } else {
                    $errors[] = "File {$files['name'][$i]}: Không thể lưu file";
                }
            }

            return $this->success([
                'files' => $uploadedFiles,
                'errors' => $errors,
                'success_count' => count($uploadedFiles),
                'error_count' => count($errors)
            ], 'Upload hoàn tất');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa file
     * DELETE /api/upload/{filename}
     */
    public function delete($filename)
    {
        try {
            $filePath = $this->uploadPath . basename($filename);
            
            if (!file_exists($filePath)) {
                return $this->error('File không tồn tại', 404);
            }

            if (unlink($filePath)) {
                return $this->success([], 'Xóa file thành công');
            } else {
                return $this->error('Không thể xóa file', 500);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy thông báo lỗi upload
     */
    private function getUploadError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File quá lớn';
            case UPLOAD_ERR_PARTIAL:
                return 'File chỉ upload một phần';
            case UPLOAD_ERR_NO_FILE:
                return 'Không có file được upload';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Thiếu thư mục tạm';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Không thể ghi file';
            case UPLOAD_ERR_EXTENSION:
                return 'Upload bị chặn bởi extension';
            default:
                return 'Lỗi không xác định';
        }
    }
}

