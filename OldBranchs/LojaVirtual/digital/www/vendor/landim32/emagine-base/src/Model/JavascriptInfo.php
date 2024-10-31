<?php

namespace Emagine\Base\Model;

/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 13/07/2017
 * Time: 10:00
 */
class JavascriptInfo
{
    private $url = "";
    private $conteudo = "";

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     * @return JavascriptInfo
     */
    public function setUrl($value) {
        $this->url = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getConteudo() {
        return $this->conteudo;
    }

    /**
     * @param string $value
     * @return JavascriptInfo
     */
    public function setConteudo($value) {
        $this->conteudo = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = "";
        if (!isNullOrEmpty($this->getUrl())) {
            $str = sprintf("<script type=\"text/javascript\" src=\"%s\"></script>\n", $this->getUrl());
        }
        elseif (!isNullOrEmpty($this->getConteudo())) {
            $str = sprintf("<script type=\"text/javascript\">\n%s\n</script>\n", $this->getConteudo());
        }
        return $str;
    }
}