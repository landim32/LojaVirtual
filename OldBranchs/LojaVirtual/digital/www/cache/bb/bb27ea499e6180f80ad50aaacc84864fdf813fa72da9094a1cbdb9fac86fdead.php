<?php

/* home.html */
class __TwigTemplate_f254656b546922eef3694bfc5a17d6a2b301905424f05d6426d255b07dc4b92a extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<?php

namespace Emagine\\Loja;

use Emagine\\Produto\\Model\\LojaInfo;
use Emagine\\Produto\\Model\\ProdutoInfo;
/**
 * @var LojaInfo \$loja
 * @var ProdutoInfo[] \$produtos
 */
require \"produtos.php\";
";
    }

    public function getTemplateName()
    {
        return "home.html";
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home.html", "F:\\xampp\\htdocs\\emagine-loja\\templates\\exemplo_twig\\home.html");
    }
}
