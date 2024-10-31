<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 26/12/2018
 * Time: 11:34
 */

namespace Emagine\Base\Utils;


class HtmlUtils
{
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public static function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * @param int $fetchNumberPages
     * @param string $formatPage
     * @param int $currentPage
     * @return string
     */
    public static function admin_pagination($fetchNumberPages, $formatPage, $currentPage = null) {
        if ($currentPage < 1)
            $currentPage = 1;
        $isFirstPage = ($currentPage <= 1);
        $isLastPage = ($currentPage >= $fetchNumberPages);
        $str = "";

        $str .= '<div class="paging_bootstrap">';
        $str .= '<ul class="pagination">';

        if(!$isFirstPage) {
            $previousPage = $currentPage - 1;
            $str .= '<li class="prev"><a href="'. sprintf($formatPage, $previousPage) . '" data-pg="'.$previousPage.'"><i class="fa fa-chevron-left"></i> Anterior</a></li>';
        }

        for($i = $currentPage - 5; $i <= $currentPage + 5; $i++) {

            if($i < 1) continue;
            if($i > $fetchNumberPages) break;

            if($i == $currentPage)
                $str .= '<li class="active"><a href="#">'.$i.'</a></li>';
            else
                $str .= '<li><a href="'. sprintf($formatPage, $i) . '" data-pg="'.$i.'">'.$i.'</a></li>';
        }//end for

        if(!$isLastPage) {
            $nextPage = $currentPage + 1;
            $str .= '<li class="next"><a href="' . sprintf($formatPage, $nextPage) . '" data-pg="'.$nextPage.'">Proxima <i class="fa fa-chevron-right"></i></a></li>';
        }
        $str .= '</ul></div>';
        return $str;
    }

    /**
     * @param string $texto
     * @param int $size
     * @param string $tag
     */
    public static function truncate_str($texto, $size, $tag = 'span') {
        if (strlen($texto) > $size) {
            echo "<$tag title=\"$texto\">";
            echo substr($texto, 0, $size)."...";
            echo "</$tag>";
        }
        else
            echo "<$tag>$texto</$tag>";
    }

    /**
     * @param string $texto
     * @param int $size
     * @param string $url
     * @param string|null $class
     */
    public static function truncate_link($texto, $size, $url, $class = null) {
        if (strlen($texto) > $size) {
            echo "<a href=\"".$url."\" title=\"$texto\"";
            if (!is_null($class))
                echo " class=\"".$class."\"";
            echo ">".substr($texto, 0, $size)."...";
            echo "</a>";
        }
        else {
            echo "<a href=\"".$url."\"";
            if (!is_null($class))
                echo " class=\"".$class."\"";
            echo ">".$texto."</a>";
        }
    }

    /**
     * @return bool
     */
    public static function eSSL() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        return false;
    }
}