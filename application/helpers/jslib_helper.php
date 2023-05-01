<?php

/**
 * This file contains various javascript related functions,
 * all functions here are self contained and can be used in ABORT_AFTER_CONFIG scripts.
 *
 * @package   core_lib
 * @copyright 2012 Petr Skoda (skodak)  {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

#defined('MOODLE_INTERNAL') || die();

/**
 * Send javascript file content with as much caching as possible
 * @param string $jspath
 * @param string $etag
 * @param string $filename
 */
function js_send_cached($jspath, $etag, $filename = 'Javascript') // javascript.php
{
    require(__DIR__ . '/xsendfilelib.php');

    $lifetime = 60 * 60 * 24 * 60; // 60 days only - the revision may get incremented quite often

    header('Etag: "' . $etag . '"');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($jspath)) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: application/javascript; charset=utf-8');

    if (xsendfile($jspath)) {
        die;
    }

    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . filesize($jspath));
    }

    readfile($jspath);
    die;
}

/**
 * Send javascript without any caching
 * @param string $js
 * @param string $filename
 */
function js_send_uncached($js, $filename = 'Javascript') //javascript.php
{
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 2) . ' GMT');
    header('Pragma: ');
    header('Accept-Ranges: none');
    header('Content-Type: application/javascript; charset=utf-8');
    header('Content-Length: ' . strlen($js));

    echo $js;
    die;
}

/**
 * Send file not modified headers
 * @param int $lastmodified
 * @param string $etag
 */
function js_send_unmodified($lastmodified, $etag)
{
    $lifetime = 60 * 60 * 24 * 60; // 60 days only - the revision may get incremented quite often
    header('HTTP/1.1 304 Not Modified');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Content-Type: application/javascript; charset=utf-8');
    header('Etag: "' . $etag . '"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    die;
}

/**
 * Create cache file for JS content
 * @param string $file full file path to cache file
 * @param string $content JS code
 */
function js_write_cache_file_content($file, $content)
{
    global $CFG;

    clearstatcache();
    if (!file_exists(dirname($file))) {
        @mkdir(dirname($file), $CFG->directorypermissions, true);
    }
debugBreak();
    // Prevent serving of incomplete file from concurrent request,
    // the rename() should be more atomic than fwrite().
    ignore_user_abort(true);
    if ($fp = fopen($file . '.tmp', 'xb')) {
        fwrite($fp, $content);
        fclose($fp);
        rename($file . '.tmp', $file);
        @chmod($file, $CFG->filepermissions);
        @unlink($file . '.tmp'); // just in case anything fails
    }
    ignore_user_abort(false);
    if (connection_aborted()) {
        die;
    }
}

/**
 * Sends a 404 message about CSS not being found.
 */
function js_send_css_not_found()
{
    header('HTTP/1.0 404 not found');
    die('JS was not found, sorry.');
}
