<?php

/* loja-lista.html */
class __TwigTemplate_99901ad2627fd8c5d3fbc84e551fc7d94ddd2d5cd5cc82b7f33d23c1f8976a09 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base-simples.html", "loja-lista.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base-simples.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = array())
    {
        // line 3
        echo "<div class=\"container\">
    <div class=\"row\">
        <div class=\"col-md-4 col-md-offset-4\">
            <div class=\"text-center\">
                <img src=\"";
        // line 7
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/images/logo.png\" alt=\"";
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "\" class=\"img-responsive\" style=\"max-height: 120px; margin: 5px auto;\" />
            </div>
            <h3 class=\"text-center\">Selecione a loja onde deseja fazer suas compras:</h3>

            <div class=\"list-group\">
                ";
        // line 12
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["lojas"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["loja"]) {
            // line 13
            echo "                    <a href=\"";
            echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
            echo "/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loja"], "slug", array()), "html", null, true);
            echo "\" class=\"list-group-item\">
                        <h4 class=\"list-group-item-heading\">";
            // line 14
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loja"], "nome", array()), "html", null, true);
            echo "</h4>
                        <p class=\"list-group-item-text\">
                            ";
            // line 16
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loja"], "endereco_completo", array()), "html", null, true);
            echo "
                        </p>
                    </a>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['loja'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 20
        echo "            </div>
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "loja-lista.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 20,  67 => 16,  62 => 14,  55 => 13,  51 => 12,  41 => 7,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "loja-lista.html", "F:\\xampp\\htdocs\\emagine-loja\\templates\\exemplo_twig\\loja-lista.html");
    }
}
