<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Services\UploadService;
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
            $service = new UploadService();
            $result = $service->upload($_FILES['file']);
            if (isset($result['error'])) {
                return $this->error($result['error'], 400);
            }
            return $this->success($result, 'Upload file thành công');
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
            $service = new UploadService();
            $result = $service->uploadMultiple($_FILES['files']);
            return $this->success($result, 'Upload hoàn tất');
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
            $service = new UploadService();
            $deleted = $service->delete($filename);
            if (!$deleted) {
                return $this->error('File không tồn tại', 404);
            }
            return $this->success([], 'Xóa file thành công');
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

