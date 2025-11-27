<?php

namespace Rinnsan\RinnSanWeb\Controllers\Web;

class HomeController
{
    /**
     * Hàm render view
     */
    protected function render($view, $data = [])
    {
        extract($data);
        
        ob_start();
        include __DIR__ . '/../../Views/pages/' . $view . '.php';
        $content = ob_get_clean();
        
        include __DIR__ . '/../../Views/layouts/app.php';
    }

    /**
     * Hàm này xử lý trang chủ
     * Chúng ta thêm ($params = []) để nhận tham số từ Router
     */
    public function index($params = [])
    {
        $this->render('home', [
            'title' => 'Trang Chủ - RinnSan Web'
        ]);
    }
    
    /**
     * Hàm này xử lý trang giới thiệu
     */
    public function about($params = [])
    {
        $this->render('about', [
            'title' => 'Giới Thiệu - RinnSan Web'
        ]);
    }
}