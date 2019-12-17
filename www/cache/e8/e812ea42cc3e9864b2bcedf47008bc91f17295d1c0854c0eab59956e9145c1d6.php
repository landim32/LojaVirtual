<?php

/* base-simples.html */
class __TwigTemplate_e29d12b25a352c5d76b01bb569493419b6d51cccac401f4e3d78ae7f528c5ba2 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'conteudo' => array($this, 'block_conteudo'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"pt_BR\">
<head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"Rodrigo Landim\">
    <link rel=\"shortcut icon\" type=\"image/vnd.microsoft.icon\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, ($context["tema_url"] ?? null), "html", null, true);
        echo "/favicon.ico\">
    <link rel=\"icon\" type=\"image/vnd.microsoft.icon\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, ($context["tema_url"] ?? null), "html", null, true);
        echo "/favicon.ico\">
    <title>";
        // line 11
        echo twig_escape_filter($this->env, ($context["titulo"] ?? null), "html", null, true);
        echo "</title>
    ";
        // line 12
        echo twig_escape_filter($this->env, ($context["css"] ?? null), "html", null, true);
        echo "
</head>
<body>
";
        // line 15
        $this->displayBlock('conteudo', $context, $blocks);
        // line 17
        echo twig_escape_filter($this->env, ($context["javascript"] ?? null), "html", null, true);
        echo "
</body>
</html>
";
    }

    // line 15
    public function block_conteudo($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base-simples.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  62 => 15,  54 => 17,  52 => 15,  46 => 12,  42 => 11,  38 => 10,  34 => 9,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base-simples.html", "F:\\xampp\\htdocs\\emagine-loja\\templates\\teste_twig\\base-simples.html");
    }
}
