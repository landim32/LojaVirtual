<?php
namespace Emagine\Produto;

use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\BLL\ValidaTelefone;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Emagine\Produto\Report\ReportControl;
use Emagine\Produto\Report\ReportField;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\ProdutoBLL;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoRoute() == true) {

    $app->group("/relatorio", function () use ($app) {

        $app->get('/estoque', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $report = new ReportControl();
            $report->setTitle("<i class='fa fa-print'></i> Relatório de Estoque Diário");
            $report->setPageCount(25);
            $report->setGroupFieldName("loja");
            $report->setQuery("
                SELECT SQL_CALC_FOUND_ROWS
                    produto.slug,
                    produto.nome,
                    loja.slug AS 'loja_slug',
                    loja.nome AS 'loja',
                    produto.quantidade
                FROM produto
                INNER JOIN loja ON loja.id_loja = produto.id_loja
            ");
            //$report->setGroupBy("GROUP BY produto.nome, loja.nome");
            $report->setOrderBy("ORDER BY loja.nome, quantidade");
            $report->addField(
                (new ReportField())
                    ->setFieldName("loja")
                    ->setName("Loja")
                    ->setUrl($app->getBaseUrl() . "/%s/relatorio/estoque")
                    ->setUrlFieldName("loja_slug")
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("nome")
                    ->setName("Produto")
                    ->setOrderAsc("ORDER BY loja.nome, produto.nome")
                    ->setOrderDesc("ORDER BY loja.nome DESC, produto.nome DESC")
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("quantidade")
                    ->setName("Quantidade")
                    ->setFieldType(ReportField::INT)
                    ->setOrderAsc("ORDER BY loja.nome, quantidade")
                    ->setOrderDesc("ORDER BY loja.nome DESC, quantidade DESC")
            );
            $report->setQueryParam($queryParam);

            $args['app'] = $app;
            $args['report'] = $report;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/valor-venda', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $report = (new ReportControl())
                ->setTitle("<i class='fa fa-print'></i> Relatório de Valores de Venda")
                ->setPageCount(25)
                ->setQuery("
                SELECT SQL_CALC_FOUND_ROWS
                    pedido.id_pedido,
                    loja.slug AS 'loja_slug', 
                    loja.nome AS 'loja',
                    usuario.nome AS 'cliente',
                    pedido.ultima_alteracao,
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) * 0.08 AS 'comissao',
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) * 0.92 AS 'valor_loja',
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) AS 'total'
                    FROM pedido
                    INNER JOIN pedido_item ON pedido_item.id_pedido = pedido.id_pedido
                    INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
                    INNER JOIN loja ON loja.id_loja = produto.id_loja
                    INNER JOIN usuario ON usuario.id_usuario = pedido.id_usuario
              ")
                ->setQuerySum("
                    SELECT
                        SUM(
                            IF(
                              produto.valor_promocao < produto.valor, 
                              produto.valor_promocao, produto.valor
                            ) * pedido_item.quantidade
                        ) * 0.08 AS 'comissao',
                        SUM(
                            IF(
                              produto.valor_promocao < produto.valor, 
                              produto.valor_promocao, produto.valor
                            ) * pedido_item.quantidade
                        ) * 0.92 AS 'valor_loja',
                        SUM(
                            IF(
                              produto.valor_promocao < produto.valor, 
                              produto.valor_promocao, produto.valor
                            ) * pedido_item.quantidade
                        ) AS 'total'
                    FROM pedido
                    INNER JOIN pedido_item ON pedido_item.id_pedido = pedido.id_pedido
                    INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
                    INNER JOIN usuario ON usuario.id_usuario = pedido.id_usuario
            ")
                ->setGroupBy("
                    GROUP BY 
	                    pedido.id_pedido,
	                    loja.slug,
	                    loja.nome,
	                    usuario.nome,
	                    pedido.ultima_alteracao
                ")
                ->setGroupFieldName("loja")
                ->setOrderBy("ORDER BY loja.nome, usuario.nome")
                ->addField(
                    (new ReportField())
                        ->setFieldName("loja")
                        ->setUrlFieldName("loja_slug")
                        ->setUrl($app->getBaseUrl() . "/%s/relatorio/valor-venda")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("cliente")
                        ->setName("Cliente")
                        ->setOrderAsc("ORDER BY loja.nome, usuario.nome")
                        ->setOrderDesc("ORDER BY loja.nome DESC, usuario.nome DESC")
                        ->setUrlFieldName(array("loja_slug", "id_pedido"))
                        ->setUrl($app->getBaseUrl() . "/%s/pedido/id/%s")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("ultima_alteracao")
                        ->setFieldType(ReportField::DATE)
                        ->setName("Data")
                        ->setWhere("pedido.ultima_alteracao BETWEEN :ultima_alteracao_ini AND :ultima_alteracao_fim")
                        ->setOrderAsc("ORDER BY loja.nome, pedido.ultima_alteracao")
                        ->setOrderDesc("ORDER BY loja.nome DESC, pedido.ultima_alteracao DESC")
                        ->setFilter(true)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("comissao")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("SmartApp")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("valor_loja")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Vlr Loja")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("total")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Total")
                        ->setOrderAsc("ORDER BY total")
                        ->setOrderDesc("ORDER BY total DESC")
                )
                ->setQueryParam($queryParam);

            $args['app'] = $app;
            $args['report'] = $report;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/venda-por-produto', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $report = (new ReportControl())
                ->setTitle("<i class='fa fa-print'></i> Relatório de Vendas por Produto")
                ->setPageCount(25)
                ->setQuery("
                SELECT SQL_CALC_FOUND_ROWS
                    produto.slug,
                    loja.slug AS 'loja_slug', 
                    loja.nome AS 'loja',
                    produto.nome,
                    IF(
                        produto.valor_promocao < produto.valor, 
                        produto.valor_promocao, produto.valor
                    ) AS 'preco',
                    COUNT(pedido.id_pedido) AS 'venda',
                    SUM(pedido_item.quantidade) AS 'produto_vendido',
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) AS 'valor_total'
                    FROM pedido
                    INNER JOIN pedido_item ON pedido_item.id_pedido = pedido.id_pedido
                    INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
                    INNER JOIN loja ON loja.id_loja = produto.id_loja
                ")
                ->setGroupBy("
                    GROUP BY 
                        produto.slug,
                        loja.slug, 
                        loja.nome,
                        produto.nome,
                        IF(
                            produto.valor_promocao < produto.valor, 
                            produto.valor_promocao, produto.valor
                        )
                ")
                ->setGroupFieldName("loja")
                ->setOrderBy("ORDER BY loja.nome, produto.nome")
                ->addField(
                    (new ReportField())
                        ->setFieldName("loja")
                        ->setUrlFieldName("loja_slug")
                        ->setUrl($app->getBaseUrl() . "/%s/relatorio/venda-por-produto")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("nome")
                        ->setName("Produto")
                        ->setWhere("produto.nome LIKE :nome")
                        ->setOrderAsc("ORDER BY loja.nome, produto.nome")
                        ->setOrderDesc("ORDER BY loja.nome DESC, produto.nome DESC")
                        ->setUrlFieldName(array("loja_slug", "slug"))
                        ->setUrl($app->getBaseUrl() . "/%s/produto/%s")
                        ->setFilter(true)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("preco")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Preço Un.")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("venda")
                        ->setFieldType(ReportField::INT)
                        ->setName("Vendas")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("produto_vendido")
                        ->setFieldType(ReportField::INT)
                        ->setName("Prod. Vendidos")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("valor_total")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Total")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("ultima_alteracao")
                        ->setFieldType(ReportField::DATE)
                        ->setName("Data")
                        ->setWhere("pedido.ultima_alteracao BETWEEN :ultima_alteracao_ini AND :ultima_alteracao_fim")
                        ->setOrderAsc("ORDER BY loja.nome, pedido.ultima_alteracao")
                        ->setOrderDesc("ORDER BY loja.nome DESC, pedido.ultima_alteracao DESC")
                        ->setFilter(true)
                        ->setVisible(false)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("comissao")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("SmartApp")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("valor_loja")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Vlr Loja")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("total")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Total")
                        ->setOrderAsc("ORDER BY total")
                        ->setOrderDesc("ORDER BY total DESC")
                )
                ->setQueryParam($queryParam);

            $args['app'] = $app;
            $args['report'] = $report;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/clientes', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $report = new ReportControl();
            $report->setTitle("<i class='fa fa-user-circle'></i> Relatório de Clientes");
            $report->setPageCount(25);
            $report->setGroupFieldName("loja");
            $report->setQuery("
                SELECT DISTINCT SQL_CALC_FOUND_ROWS
                    usuario.nome,
                    usuario.cpf_cnpj,
                    usuario.email,
                    usuario.telefone,
                    loja.slug AS 'loja_slug',
                    loja.nome AS 'loja'
                FROM usuario
                INNER JOIN pedido ON pedido.id_usuario = usuario.id_usuario
                INNER JOIN loja ON loja.id_loja = pedido.id_loja
            ");
            $report->setOrderBy("ORDER BY loja.nome, usuario.nome");
            $report->addField(
                (new ReportField())
                    ->setFieldName("loja")
                    ->setName("Loja")
                    ->setUrl($app->getBaseUrl() . "/%s/relatorio/clientes")
                    ->setUrlFieldName("loja_slug")
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("nome")
                    ->setName("Nome")
                    ->setWhere("(usuario.nome LIKE :nome OR usuario.cpf_cnpj LIKE :nome)")
                    ->setOrderAsc("ORDER BY loja.nome, usuario.nome")
                    ->setOrderDesc("ORDER BY loja.nome DESC, usuario.nome DESC")
                    ->setFilter(true)
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("cpf_cnpj")
                    ->setName("CPF/CNPJ")
                    ->setOrderAsc("ORDER BY loja.nome, usuario.cpf_cnpj")
                    ->setOrderDesc("ORDER BY loja.nome DESC, usuario.cpf_cnpj DESC")
                    ->setOnShow(function($texto) {
                        return (new ValidaCpfCnpj($texto))->formatar();
                    })
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("email")
                    ->setName("Email")
                    ->setOrderAsc("ORDER BY loja.nome, usuario.email")
                    ->setOrderDesc("ORDER BY loja.nome DESC, usuario.email DESC")
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("telefone")
                    ->setName("Telefone")
                    ->setOrderAsc("ORDER BY loja.nome, usuario.telefone")
                    ->setOrderDesc("ORDER BY loja.nome DESC, usuario.telefone DESC")
                    ->setOnShow(function($texto) {
                        return (new ValidaTelefone($texto))->formatar();
                    })
            );
            $report->setQueryParam($queryParam);

            $args['app'] = $app;
            $args['report'] = $report;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });
    });

    $app->group("/{slug_loja}/relatorio", function () use ($app) {

        $app->get('/estoque', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $regraLoja->gravarAtual($loja);

            $report = new ReportControl();
            $report->setTitle("<i class='fa fa-print'></i> Relatório de Estoque Diário");
            $report->setPageCount(25);
            $report->setQuery("
                SELECT SQL_CALC_FOUND_ROWS
                    produto.nome,
                    SUM(produto.quantidade) AS 'quantidade'
                FROM produto
            ");
            $report->setGroupBy("GROUP BY produto.nome");
            $report->setOrderBy("ORDER BY quantidade");
            $report->addField(
                (new ReportField())
                    ->setFieldName("id_loja")
                    ->setWhere("produto.id_loja = :id_loja")
                    ->setValue($loja->getId())
                    ->setVisible(false)
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("nome")
                    ->setName("Produto")
                    ->setOrderAsc("ORDER BY produto.nome")
                    ->setOrderDesc("ORDER BY produto.nome DESC")
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("quantidade")
                    ->setName("Quantidade")
                    ->setFieldType(ReportField::INT)
                    ->setOrderAsc("ORDER BY quantidade")
                    ->setOrderDesc("ORDER BY quantidade DESC")
            );
            $report->setQueryParam($queryParam);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/relatorio/estoque");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['report'] = $report;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/valor-venda', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $report = (new ReportControl())
                ->setTitle("<i class='fa fa-print'></i> Relatório de Valores de Venda")
                ->setPageCount(25)
                ->setQuery("
                SELECT SQL_CALC_FOUND_ROWS
                    pedido.id_pedido,
                    loja.slug AS 'loja_slug', 
                    usuario.nome AS 'cliente',
                    pedido.ultima_alteracao,
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) * 0.08 AS 'comissao',
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) * 0.92 AS 'valor_loja',
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) AS 'total'
                    FROM pedido
                    INNER JOIN pedido_item ON pedido_item.id_pedido = pedido.id_pedido
                    INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
                    INNER JOIN loja ON loja.id_loja = produto.id_loja
                    INNER JOIN usuario ON usuario.id_usuario = pedido.id_usuario
              ")
                ->setQuerySum("
                    SELECT
                        SUM(
                            IF(
                              produto.valor_promocao < produto.valor, 
                              produto.valor_promocao, produto.valor
                            ) * pedido_item.quantidade
                        ) * 0.08 AS 'comissao',
                        SUM(
                            IF(
                              produto.valor_promocao < produto.valor, 
                              produto.valor_promocao, produto.valor
                            ) * pedido_item.quantidade
                        ) * 0.92 AS 'valor_loja',
                        SUM(
                            IF(
                              produto.valor_promocao < produto.valor, 
                              produto.valor_promocao, produto.valor
                            ) * pedido_item.quantidade
                        ) AS 'total'
                    FROM pedido
                    INNER JOIN pedido_item ON pedido_item.id_pedido = pedido.id_pedido
                    INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
                    INNER JOIN usuario ON usuario.id_usuario = pedido.id_usuario
            ")
                ->setGroupBy("
                    GROUP BY 
	                    pedido.id_pedido,
	                    loja.slug,
	                    usuario.nome,
	                    pedido.ultima_alteracao
                ")
                ->setOrderBy("ORDER BY usuario.nome")
                ->addField(
                    (new ReportField())
                        ->setFieldName("id_loja")
                        ->setWhere("pedido.id_loja = :id_loja")
                        ->setValue($loja->getId())
                        ->setVisible(false)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("cliente")
                        ->setName("Cliente")
                        ->setOrderAsc("ORDER BY usuario.nome")
                        ->setOrderDesc("ORDER BY usuario.nome DESC")
                        ->setUrlFieldName(array("loja_slug", "id_pedido"))
                        ->setUrl($app->getBaseUrl() . "/%s/pedido/id/%s")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("ultima_alteracao")
                        ->setFieldType(ReportField::DATE)
                        ->setName("Data")
                        ->setWhere("pedido.ultima_alteracao BETWEEN :ultima_alteracao_ini AND :ultima_alteracao_fim")
                        ->setOrderAsc("ORDER BY pedido.ultima_alteracao")
                        ->setOrderDesc("ORDER BY pedido.ultima_alteracao DESC")
                        ->setFilter(true)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("comissao")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("SmartApp")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("valor_loja")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Vlr Loja")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("total")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Total")
                        ->setOrderAsc("ORDER BY total")
                        ->setOrderDesc("ORDER BY total DESC")
                )
                ->setQueryParam($queryParam);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/relatorio/valor-venda");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['report'] = $report;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/venda-por-produto', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $report = (new ReportControl())
                ->setTitle("<i class='fa fa-print'></i> Relatório de Vendas por Produto")
                ->setPageCount(25)
                ->setQuery("
                SELECT SQL_CALC_FOUND_ROWS
                    produto.slug,
                    loja.slug AS 'loja_slug', 
                    produto.nome,
                    IF(
                        produto.valor_promocao < produto.valor, 
                        produto.valor_promocao, produto.valor
                    ) AS 'preco',
                    COUNT(pedido.id_pedido) AS 'venda',
                    SUM(pedido_item.quantidade) AS 'produto_vendido',
                    SUM(
                        IF(
                          produto.valor_promocao < produto.valor, 
                          produto.valor_promocao, produto.valor
                        ) * pedido_item.quantidade
                    ) AS 'valor_total'
                    FROM pedido
                    INNER JOIN pedido_item ON pedido_item.id_pedido = pedido.id_pedido
                    INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
                    INNER JOIN loja ON loja.id_loja = produto.id_loja
                ")
                ->setGroupBy("
                    GROUP BY 
                        produto.slug,
                        loja.slug, 
                        produto.nome,
                        IF(
                            produto.valor_promocao < produto.valor, 
                            produto.valor_promocao, produto.valor
                        )
                ")
                ->setGroupFieldName("loja")
                ->setOrderBy("ORDER BY loja.nome, produto.nome")
                ->addField(
                    (new ReportField())
                        ->setFieldName("id_loja")
                        ->setWhere("pedido.id_loja = :id_loja")
                        ->setValue($loja->getId())
                        ->setVisible(false)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("nome")
                        ->setName("Produto")
                        ->setWhere("produto.nome LIKE :nome")
                        ->setOrderAsc("ORDER BY loja.nome, produto.nome")
                        ->setOrderDesc("ORDER BY loja.nome DESC, produto.nome DESC")
                        ->setUrlFieldName(array("loja_slug", "slug"))
                        ->setUrl($app->getBaseUrl() . "/%s/produto/%s")
                        ->setFilter(true)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("preco")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Preço Un.")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("venda")
                        ->setFieldType(ReportField::INT)
                        ->setName("Vendas")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("produto_vendido")
                        ->setFieldType(ReportField::INT)
                        ->setName("Prod. Vendidos")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("valor_total")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Total")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("ultima_alteracao")
                        ->setFieldType(ReportField::DATE)
                        ->setName("Data")
                        ->setWhere("pedido.ultima_alteracao BETWEEN :ultima_alteracao_ini AND :ultima_alteracao_fim")
                        ->setOrderAsc("ORDER BY loja.nome, pedido.ultima_alteracao")
                        ->setOrderDesc("ORDER BY loja.nome DESC, pedido.ultima_alteracao DESC")
                        ->setFilter(true)
                        ->setVisible(false)
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("comissao")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("SmartApp")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("valor_loja")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Vlr Loja")
                )
                ->addField(
                    (new ReportField())
                        ->setFieldName("total")
                        ->setFieldType(ReportField::DOUBLE)
                        ->setName("Total")
                        ->setOrderAsc("ORDER BY total")
                        ->setOrderDesc("ORDER BY total DESC")
                )
                ->setQueryParam($queryParam);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/relatorio/venda-por-produto");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['report'] = $report;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/clientes', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $report = new ReportControl();
            $report->setTitle("<i class='fa fa-user-circle'></i> Relatório de Clientes");
            $report->setPageCount(25);
            $report->setGroupFieldName("loja");
            $report->setQuery("
                SELECT DISTINCT SQL_CALC_FOUND_ROWS
                    usuario.nome,
                    usuario.cpf_cnpj,
                    usuario.email,
                    usuario.telefone
                FROM usuario
                INNER JOIN pedido ON pedido.id_usuario = usuario.id_usuario
            ");
            $report->setOrderBy("ORDER BY usuario.nome");
            $report->addField(
                (new ReportField())
                    ->setFieldName("id_loja")
                    ->setWhere("pedido.id_loja = :id_loja")
                    ->setValue($loja->getId())
                    ->setVisible(false)
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("nome")
                    ->setName("Nome")
                    ->setWhere("(usuario.nome LIKE :nome OR usuario.cpf_cnpj LIKE :nome)")
                    ->setOrderAsc("ORDER BY usuario.nome")
                    ->setOrderDesc("ORDER BY usuario.nome DESC")
                    ->setFilter(true)
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("cpf_cnpj")
                    ->setName("CPF/CNPJ")
                    ->setOrderAsc("ORDER BY usuario.cpf_cnpj")
                    ->setOrderDesc("ORDER BY usuario.cpf_cnpj DESC")
                    ->setOnShow(function($texto) {
                        return (new ValidaCpfCnpj($texto))->formatar();
                    })
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("email")
                    ->setName("Email")
                    ->setOrderAsc("ORDER BY usuario.email")
                    ->setOrderDesc("ORDER BY usuario.email DESC")
            );
            $report->addField(
                (new ReportField())
                    ->setFieldName("telefone")
                    ->setName("Telefone")
                    ->setOrderAsc("ORDER BY usuario.telefone")
                    ->setOrderDesc("ORDER BY usuario.telefone DESC")
                    ->setOnShow(function($texto) {
                        return (new ValidaTelefone($texto))->formatar();
                    })
            );
            $report->setQueryParam($queryParam);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/relatorio/clientes");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['report'] = $report;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'relatorio.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });
    });
}