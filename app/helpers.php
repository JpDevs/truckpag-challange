<?php
if (!function_exists('sanitizeInt')) {
    function sanitizeInt($value)
    {
        $code = str_replace('"', '', $value);
        return intval($code);
    }
}
