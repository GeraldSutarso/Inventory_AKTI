<?php

if (!function_exists('sortLink')) {
    function sortLink($label, $field) {
        $currentSort = request('sort_by');
        $currentOrder = request('sort_order', 'asc');

        $newOrder = ($currentSort === $field && $currentOrder === 'asc') ? 'desc' : 'asc';

        $arrow = '⇅';
        if ($currentSort === $field) {
            $arrow = $currentOrder === 'asc' ? '↑' : '↓';
        }

        $params = array_merge(request()->all(), [
            'sort_by' => $field,
            'sort_order' => $newOrder,
        ]);

        $url = url()->current() . '?' . http_build_query($params);

        $active = $currentSort === $field
            ? 'text-blue-600 font-bold'
            : 'text-black';

        return '<a href="' . $url . '" class="hover:underline ' . $active . '">' . $label . ' <span class="text-sm">' . $arrow . '</span></a>';
    }
}

