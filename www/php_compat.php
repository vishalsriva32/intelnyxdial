<?php
/**
 * PHP 8.x Compatibility Shim for VICIdial
 * Restores functions removed in PHP 7.0+
 */

if (!function_exists('ereg')) {
    function ereg($pattern, $string, &$regs = null) {
        $r = preg_match('/' . str_replace('/', '\/', $pattern) . '/', $string, $regs);
        return ($r && isset($regs[0])) ? strlen($regs[0]) : $r;
    }
}

if (!function_exists('eregi')) {
    function eregi($pattern, $string, &$regs = null) {
        $r = preg_match('/' . str_replace('/', '\/', $pattern) . '/i', $string, $regs);
        return ($r && isset($regs[0])) ? strlen($regs[0]) : $r;
    }
}

if (!function_exists('ereg_replace')) {
    function ereg_replace($pattern, $replacement, $string) {
        return preg_replace('/' . str_replace('/', '\/', $pattern) . '/', $replacement, $string);
    }
}

if (!function_exists('eregi_replace')) {
    function eregi_replace($pattern, $replacement, $string) {
        return preg_replace('/' . str_replace('/', '\/', $pattern) . '/i', $replacement, $string);
    }
}

if (!function_exists('split')) {
    function split($pattern, $string, $limit = -1) {
        return preg_split('/' . str_replace('/', '\/', $pattern) . '/', $string, $limit);
    }
}
