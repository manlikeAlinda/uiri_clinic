<?php

if (!function_exists('getStatusColor')) {
    function getStatusColor(string $status): string
    {
        return match ($status) {
            'Scheduled'    => 'secondary',
            'In Progress'  => 'info',
            'Completed'    => 'success',
            'Cancelled'    => 'danger',
            default        => 'light',
        };
    }
}
