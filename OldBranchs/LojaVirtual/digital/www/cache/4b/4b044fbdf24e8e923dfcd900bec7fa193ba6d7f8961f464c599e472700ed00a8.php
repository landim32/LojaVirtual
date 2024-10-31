<?php

/* base-simples.html */
class __TwigTemplate_2d1adb96227e2c8e74ce3c50a9b300433a81b9be8323d19b5936a5366a0e1816 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
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
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/favicon.ico\">
    <link rel=\"icon\" type=\"image/vnd.microsoft.icon\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/favicon.ico\">
    <title>";
        // line 11
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
    ";
        // line 12
        echo ($context["css"] ?? null);
        echo "
</head>
<body>
";
        // line 15
        $this->displayBlock('content', $context, $blocks);
        // line 16
        echo ($context["javascript"] ?? null);
        echo "
</body>
</html>
";
    }

    // line 15
    public function block_content($context, array $blocks = array())
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
        return array (  62 => 15,  54 => 16,  52 => 15,  46 => 12,  42 => 11,  38 => 10,  34 => 9,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base-simples.html", "F:\\xampp\\htdocs\\emagine-loja\\templates\\exemplo_twig\\base-simples.html");
    }
}
