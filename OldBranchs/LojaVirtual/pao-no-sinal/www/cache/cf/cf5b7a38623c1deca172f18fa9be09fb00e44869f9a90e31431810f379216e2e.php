<?php

/* seguimentos.html */
class __TwigTemplate_1afdb2004b8b821c291ecff099272228ab6483aac441fd4fd5e45f14a96ae800 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base-simples.html", "seguimentos.html", 1);
        $_trait_0 = $this->loadTemplate("banner.html", "seguimentos.html", 19);
        // line 19
        if (!$_trait_0->isTraitable()) {
            throw new Twig_Error_Runtime('Template "'."banner.html".'" cannot be used as a trait.', 19, $this->source);
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $this->traits = $_trait_0_blocks;

        $this->blocks = array_merge(
            $this->traits,
            array(
                'conteudo' => array($this, 'block_conteudo'),
            )
        );
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base-simples.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_conteudo($context, array $blocks = array())
    {
        // line 3
        echo "<div class=\"container\">
    <div class=\"row\">
        <div class=\"col-md-6 col-md-offset-3 text-center\">
            <a href=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "\">
                <img src=\"";
        // line 7
        echo twig_escape_filter($this->env, ($context["tema_url"] ?? null), "html", null, true);
        echo "/images/logo.png\"
                    alt=\"";
        // line 8
        echo twig_escape_filter($this->env, ($context["titulo"] ?? null), "html", null, true);
        echo "\" class=\"img-responsive pull-left\"
                    style=\"max-height: 120px; margin: 5px 10px 5px 0px;\" />
            </a>
            <h1 style=\"margin-bottom: 1px\">
                Faça suas compras de
            </h1>
            <h4 style=\"margin-top: 1px\">
                supermercado no conforto da sua casa.
            </h4>
        </div>
    </div>
    ";
        // line 20
        echo "    <form id=\"raioForm\" method=\"POST\" class=\"form-horizontal margin-top-25px\">
        <div class=\"row\">
            <div class=\"col-md-4 col-md-offset-4 text-center\">
                <div class=\"form-group form-group-lg\">
                    <div class=\"input-group input-group-lg\">
                        <input type=\"number\" class=\"form-control\" id=\"raio\" name=\"raio\" value=\"";
        // line 25
        echo twig_escape_filter($this->env, ($context["raio"] ?? null), "html", null, true);
        echo "\" placeholder=\"Raio de busca (ex: 100km)\" maxlength=\"4\" />
                        <span class=\"input-group-addon\">Km</span>
                        <span class=\"input-group-btn\">
                            <button class=\"btn btn-primary\" type=\"submit\"><i class=\"fa fa-search\"></i></button>
                        </span>
                    </div>
                </div>
                <!--span class=\"text-muted\">Informa o raio de busca e encontre o q procura.</span-->
            </div>
        </div>
    </form>
    <div class=\"row margin-top-25px\">
        <div class=\"col-md-8 col-md-offset-2\">
            ";
        // line 38
        if ((twig_length_filter($this->env, ($context["seguimentos"] ?? null)) > 0)) {
            // line 39
            echo "            <div class=\"row\">
                <?php foreach (\$seguimentos as \$seguimento) : ?>
                <?php
                    if (!is_null(\$urlSeguimento)){
                        \$url = sprintf(\$urlSeguimento, \$seguimento->getSlug());
                    }
                    else {
                        \$url = \$app->getBaseUrl() . \"/s/\" . \$seguimento->getSlug();
                    }
                    ?>
                    <div class=\"col-md-3 text-center\">
                        <a href=\"<?php echo \$url; ?>\" class=\"btn btn-primary btn-xl btn-circle\">
                            <img src=\"<?php echo \$seguimento->getIconeUrl(35, 35); ?>\"
                                 style=\"width: 35px; height: 35px; margin: 5px 0px;\"
                                 alt=\"<?php echo \$seguimento->getNome(); ?>\">
                        </a><br />
                        <a href=\"<?php echo \$url; ?>\"><?php echo \$seguimento->getNome(); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
            ";
        } else {
            // line 60
            echo "                <div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                    <i class=\"fa fa-warning\"></i> Nenhum seguimento encontrado dentro do raio de ";
            // line 62
            echo twig_escape_filter($this->env, ($context["raio"] ?? null), "html", null, true);
            echo "Km.
                </div>
            ";
        }
        // line 65
        echo "        </div>
    </div>
</div>
<?php if (!is_null(\$endereco)) : ?>
<div class=\"container margin-top-30px\">
    <div class=\"row\">
        <div class=\"col-md-6 col-md-offset-3 text-center\">
            <i class=\"fa fa-map-marker\"></i>
            <span class=\"completo\">";
        // line 73
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["endereco"] ?? null), "endereco_completo", array()), "html", null, true);
        echo "</span>
            <a href=\"";
        // line 74
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "/endereco/seleciona\"><small>(mudar)</small></a>
        </div>
    </div>
</div>
<?php endif; ?>
";
    }

    public function getTemplateName()
    {
        return "seguimentos.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 74,  143 => 73,  133 => 65,  127 => 62,  123 => 60,  100 => 39,  98 => 38,  82 => 25,  75 => 20,  61 => 8,  57 => 7,  53 => 6,  48 => 3,  45 => 2,  36 => 1,  18 => 19,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "seguimentos.html", "F:\\xampp\\htdocs\\emagine-loja\\templates\\teste_twig\\seguimentos.html");
    }
}
