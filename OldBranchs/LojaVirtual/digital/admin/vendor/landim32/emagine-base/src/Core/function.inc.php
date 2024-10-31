<?php
/*
namespace Emagine\Base;

use Emagine\Base\Utils\ArrayUtils;
use Emagine\Base\Utils\DateTimeUtils;
use Emagine\Base\Utils\HtmlUtils;
use Emagine\Base\Utils\NumberUtils;
use Emagine\Base\Utils\StringUtils;
*/

//if (!function_exists("get_tema_path")) :
/**
 * @deprecated Não usar mais
 * @return string
 */
function get_tema_path() {
    return TEMA_PATH;
}
//endif;

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @deprecated Use HtmlUtils
 * @param string $email The email address
 * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param bool $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    return \Emagine\Base\Utils\HtmlUtils::get_gravatar($email, $s, $d, $r, $img, $atts);
}

/**
 * @deprecated Use StringUtils
 * @param string|null $texto
 * @return bool
 */
function isNullOrEmpty($texto) {
    return \Emagine\Base\Utils\StringUtils::isNullOrEmpty($texto);
}

/**
 * @deprecated Use StringUtils
 * @param string $str
 * @param int $tamanho
 * @return string
 */
function cortarTexto($str, $tamanho) {
    return \Emagine\Base\Utils\StringUtils::cortarTexto($str, $tamanho);
}

/**
 * @deprecated Use StringUtils
 * @param string $search
 * @param string $replace
 * @param string $subject
 * @return string
 */
function str_lreplace($search, $replace, $subject)
{
    return \Emagine\Base\Utils\StringUtils::str_lreplace($search, $replace, $subject);
}

/**
 * @deprecated Use StringUtils
 * @param string $str
 * @return bool
 */
function seems_utf8($str) {
    return \Emagine\Base\Utils\StringUtils::seems_utf8($str);
}

/**
 * @deprecated Use StringUtils
 * @param string $texto
 * @return string
 */
function sanitize_slug($texto) {
    return \Emagine\Base\Utils\StringUtils::sanitize_slug($texto);
}

/**
 * @deprecated Use StringUtils
 * @param string $string
 * @return string
 */
function remove_accents($string) {
    return \Emagine\Base\Utils\StringUtils::remove_accents($string);
}

/**
 * @deprecated Use HtmlUtils
 * @param int $fetchNumberPages
 * @param string $formatPage
 * @param int|null $currentPage
 * @return string
 */
function admin_pagination($fetchNumberPages, $formatPage, $currentPage = null) {
    return \Emagine\Base\Utils\HtmlUtils::admin_pagination($fetchNumberPages, $formatPage, $currentPage);
}

/**
 * @deprecated Use ArrayUtils
 * @param array $list
 * @param int $p
 * @return array
 */
function array_partition( $list, $p ) {
    return \Emagine\Base\Utils\ArrayUtils::array_partition($list, $p);
}

/**
 * @deprecated Use StringUtils
 * @param $haystack
 * @param $needle
 * @return bool
 */
function startsWith($haystack, $needle)
{
    return \Emagine\Base\Utils\StringUtils::startsWith($haystack, $needle);
}

/**
 * @deprecated Use StringUtils
 * @param $haystack
 * @param $needle
 * @return bool
 */
function endsWith($haystack, $needle)
{
    return \Emagine\Base\Utils\StringUtils::endsWith($haystack, $needle);
}

/**
 * @deprecated Use DateTimeUtils
 * @param int $now
 * @param int|null $otherDate
 * @param int|null $offset
 * @return string
 */
function humanizeDateDiff($now, $otherDate = null, $offset = null) {
    return \Emagine\Base\Utils\DateTimeUtils::humanizeDateDiff($now, $otherDate, $offset);
}

/**
 * @deprecated Use HtmlUtils
 * @param string $texto
 * @param int $size
 * @param string $tag
 */
function truncate_str($texto, $size, $tag = 'span') {
    \Emagine\Base\Utils\HtmlUtils::truncate_str($texto, $size, $tag);
}

/**
 * @param string $texto
 * @param int $size
 * @param string $url
 * @param string|null $class
 */
function truncate_link($texto, $size, $url, $class = null) {
    \Emagine\Base\Utils\HtmlUtils::truncate_link($texto, $size, $url, $class);
}

/**
 * @deprecated Use NumberUtils
 * @param mixed $value
 * @param int $default
 * @return int
 */
function intvalx($value, $default = 0) {
    return \Emagine\Base\Utils\NumberUtils::intvalx($value, $default);
}

/**
 * @deprecated Use NumberUtils
 * @param mixed $value
 * @param float $default
 * @return float
 */
function floatvalx($value, $default = 0.0) {
    return \Emagine\Base\Utils\NumberUtils::floatvalx($value, $default);
}

/**
 * @deprecated Use NumberUtils
 * @param mixed $value
 * @param bool $default
 * @return bool
 */
function boolvalx($value, $default = false) {
    return \Emagine\Base\Utils\NumberUtils::boolvalx($value, $default);
}

/**
 * @deprecated Use HtmlUtils
 * @return bool
 */
function eSSL() {
    return \Emagine\Base\Utils\HtmlUtils::eSSL();
}