<?php
/**
 * PHP 8.x Compatibility Shim for VICIdial
 * Restores ereg* and mysql_* functions removed in PHP 7.0+
 */

// ── ereg* ────────────────────────────────────────────────────────────────────

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

// ── mysql_* shim ─────────────────────────────────────────────────────────────

if (!function_exists('mysql_connect')) {

    if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', MYSQLI_ASSOC);
    if (!defined('MYSQL_NUM'))   define('MYSQL_NUM',   MYSQLI_NUM);
    if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH',  MYSQLI_BOTH);

    $GLOBALS['__mysql_shim_conn'] = null;

    function mysql_connect($host, $user, $pass, $new_link = false) {
        $conn = mysqli_connect($host, $user, $pass);
        if ($conn) $GLOBALS['__mysql_shim_conn'] = $conn;
        return $conn ?: false;
    }
    function mysql_pconnect($host, $user, $pass) {
        return mysql_connect($host, $user, $pass);
    }
    function _mysql_conn($c) {
        return $c ?: $GLOBALS['__mysql_shim_conn'];
    }
    function mysql_select_db($db, $c = null) {
        return mysqli_select_db(_mysql_conn($c), $db);
    }
    function mysql_query($sql, $c = null) {
        return mysqli_query(_mysql_conn($c), $sql);
    }
    function mysql_unbuffered_query($sql, $c = null) {
        return mysqli_query(_mysql_conn($c), $sql, MYSQLI_USE_RESULT);
    }
    function mysql_fetch_array($r, $type = MYSQLI_BOTH) {
        return mysqli_fetch_array($r, $type);
    }
    function mysql_fetch_assoc($r) {
        return mysqli_fetch_assoc($r);
    }
    function mysql_fetch_row($r) {
        return mysqli_fetch_row($r);
    }
    function mysql_fetch_object($r) {
        return mysqli_fetch_object($r);
    }
    function mysql_num_rows($r) {
        return mysqli_num_rows($r);
    }
    function mysql_affected_rows($c = null) {
        return mysqli_affected_rows(_mysql_conn($c));
    }
    function mysql_insert_id($c = null) {
        return mysqli_insert_id(_mysql_conn($c));
    }
    function mysql_error($c = null) {
        return mysqli_error(_mysql_conn($c));
    }
    function mysql_errno($c = null) {
        return mysqli_errno(_mysql_conn($c));
    }
    function mysql_real_escape_string($s, $c = null) {
        return mysqli_real_escape_string(_mysql_conn($c), $s);
    }
    function mysql_escape_string($s) {
        return mysql_real_escape_string($s);
    }
    function mysql_close($c = null) {
        return mysqli_close(_mysql_conn($c));
    }
    function mysql_free_result($r) {
        return mysqli_free_result($r);
    }
    function mysql_num_fields($r) {
        return mysqli_num_fields($r);
    }
    function mysql_field_name($r, $i) {
        mysqli_field_seek($r, $i);
        $f = mysqli_fetch_field($r);
        return $f ? $f->name : false;
    }
    function mysql_fetch_field($r, $i = null) {
        if ($i !== null) mysqli_field_seek($r, $i);
        return mysqli_fetch_field($r);
    }
    function mysql_data_seek($r, $offset) {
        return mysqli_data_seek($r, $offset);
    }
    function mysql_result($r, $row, $field = 0) {
        mysqli_data_seek($r, $row);
        $data = mysqli_fetch_array($r);
        return $data[$field] ?? false;
    }
    function mysql_list_fields($db, $table, $c = null) {
        $r = mysqli_query(_mysql_conn($c), "SHOW COLUMNS FROM `$table`");
        return $r ?: false;
    }
    function mysql_ping($c = null) {
        return mysqli_ping(_mysql_conn($c));
    }
    function mysql_get_server_info($c = null) {
        return mysqli_get_server_info(_mysql_conn($c));
    }
}
