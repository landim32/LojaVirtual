<?php

/* banner.html */
class __TwigTemplate_3653bbaf6ec439cfbd2b391f9045b2759491ff6e0de550cb1dc3d55d7b98d223 extends Twig_Template
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
        if ((twig_length_filter($this->env, ($context["pecas"] ?? null)) > 0)) {
            // line 2
            echo "<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\">
    <ol class=\"carousel-indicators\">
        ";
            // line 4
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["pecas"] ?? null));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["peca"]) {
                // line 5
                echo "            ";
                if ((twig_get_attribute($this->env, $this->source, $context["loop"], "index0", array()) == 0)) {
                    // line 6
                    echo "                <li data-target=\"#myCarousel\" data-slide-to=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loop"], "index0", array()), "html", null, true);
                    echo "\" class=\"active\"></li>
            ";
                } else {
                    // line 8
                    echo "                <li data-target=\"#myCarousel\" data-slide-to=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loop"], "index0", array()), "html", null, true);
                    echo "\"></li>
            ";
                }
                // line 10
                echo "        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['peca'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 11
            echo "    </ol>
    <div class=\"carousel-inner\">
        ";
            // line 13
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["pecas"] ?? null));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["peca"]) {
                // line 14
                echo "            <div class=\"";
                if ((twig_get_attribute($this->env, $this->source, $context["loop"], "index0", array()) == 0)) {
                    echo "item active";
                } else {
                    echo "item";
                }
                echo "\">
                <a href=\"#\">
                    <img src=\"";
                // line 16
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["peca"], "imagem_url", array()), "html", null, true);
                echo "\" alt=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["peca"], "nome", array()), "html", null, true);
                echo "\"
                         style=\"width:";
                // line 17
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["peca"], "largura", array()), "html", null, true);
                echo "px;height:";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["peca"], "altura", array()), "html", null, true);
                echo "px; margin: 0 auto;\" />
                </a>
            </div>
        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['peca'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 21
            echo "    </div>
</div>
";
        }
    }

    public function getTemplateName()
    {
        return "banner.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  132 => 21,  112 => 17,  106 => 16,  96 => 14,  79 => 13,  75 => 11,  61 => 10,  55 => 8,  49 => 6,  46 => 5,  29 => 4,  25 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "banner.html", "F:\\xampp\\htdocs\\emagine-loja\\templates\\teste_twig\\banner.html");
    }
}
