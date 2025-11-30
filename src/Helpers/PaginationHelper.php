<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class PaginationHelper
{
    /**
     * Tạo pagination links
     */
    public static function links($currentPage, $totalPages, $baseUrl, $queryParams = [])
    {
        $links = [];
        
        // Previous
        if ($currentPage > 1) {
            $links['prev'] = $baseUrl . '?' . http_build_query(array_merge($queryParams, ['page' => $currentPage - 1]));
        }
        
        // Next
        if ($currentPage < $totalPages) {
            $links['next'] = $baseUrl . '?' . http_build_query(array_merge($queryParams, ['page' => $currentPage + 1]));
        }
        
        // Pages
        $links['pages'] = [];
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $links['pages'][] = [
                'page' => $i,
                'url' => $baseUrl . '?' . http_build_query(array_merge($queryParams, ['page' => $i])),
                'active' => $i == $currentPage
            ];
        }
        
        return $links;
    }

    /**
     * Format pagination response
     */
    public static function format($data, $pagination, $baseUrl = null, $queryParams = [])
    {
        $result = [
            'data' => $data,
            'pagination' => $pagination
        ];
        
        if ($baseUrl) {
            $result['links'] = self::links(
                $pagination['current_page'],
                $pagination['total_pages'],
                $baseUrl,
                $queryParams
            );
        }
        
        return $result;
    }
}

