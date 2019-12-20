
# Loja Virtual / App Android & iOS
> Solução completa de e-commerce em PHP e Xamarin.NET

Solução completa e totalmente personalizada para para **lojas virtuais** e **e-commerce** em geral usando PHP e Xamarin.NET.

## Estrutura do Projeto

* _www_ - Website da loja virtual em PHP e API Restful;
	* _core_ - Contem as configurações;
	* _templates_ - Templates da loja virtual;
	* _test_ - Testes unitários;
	* _vendor/landim32_ - Módulos personalizados reutilizáveis;
		* _btmenu_ - https://github.com/landim32/btmenu
		* _easydb_ - https://github.com/landim32/easydb
		* _google-directions_ - https://github.com/landim32/google-directions
		* _emagine-base_ - Módulo base da API;
		* _emagine-banner_ - Módulo de gestão de banners;
 		* _emagine-log_ - Módulo de log de execução no sistema;
 		* _emagine-login_ - Módulo de autenticação de usuários;
 		* _emagine-endereco_ - Módulo de endereços e busca por CEP;
 		* _emagine-pagamento_ - Módulo de pagamentos online;
 		* _emagine-produto_ - Módulo de cadastro de produtos;
 		* _emagine-pedido_ - Módulo de pedidos/entregas de compra;
 		* _emagine-social_ - Módulo de integração social e com redes sociais;
* _admin_ - Administrativo, cadastro de produtos, gestão de pedidos, etc (PHP);
	* _core_ - Contem as configurações;
	* _templates_ - Templates da loja virtual;
	* _test_ - Testes unitários;
	* _vendor/landim32_ - Módulos personalizados reutilizáveis;
		* _btmenu_ - https://github.com/landim32/btmenu
		* _easydb_ - https://github.com/landim32/easydb
		* _google-directions_ - https://github.com/landim32/google-directions
		* _emagine-base_ - Módulo base da API;
		* _emagine-banner_ - Módulo de gestão de banners;
 		* _emagine-log_ - Módulo de log de execução no sistema;
 		* _emagine-login_ - Módulo de autenticação de usuários;
 		* _emagine-endereco_ - Módulo de endereços e busca por CEP;
 		* _emagine-pagamento_ - Módulo de pagamentos online;
 		* _emagine-produto_ - Módulo de cadastro de produtos;
 		* _emagine-pedido_ - Módulo de pedidos/entregas de compra;
 		* _emagine-social_ - Módulo de integração social e com redes sociais;
 		* _emagine-grafico_ - Módulo de gráficos para web;
 * _app_ - Aplicativo de vendas para Android e iOS (Xamarin)
	 * _Loja_ - Biblioteca com o core do aplicativo;
		 * _Emagine_ - Biblioteca geral com todos os módulos desenvolvidos;
	 * _Loja.Droid_ - Versão para Android;
	 * _Loja.iOS_ - Versão para iOS;
 * _ponto-venda_ - Aplicativo de ponto venda offline para Android e iOS (Xamarin) **(INCOMPLETO)**
	 * _Loja_ - Biblioteca com o core do aplicativo;
		 * _Emagine_ - Biblioteca geral com todos os módulos desenvolvidos;
	 * _Loja.Droid_ - Versão para Android;
	 * _Loja.iOS_ - Versão para iOS;
* _material_ - Material visual para o desenvolvimento;
* _sql_ - Dump com várias versões do banco de dados em MySQL;

## Histórico de lançamentos

* 0.8.1
    * Primeira versão utilizável

## Meta

Rodrigo Landim – [@Landim32Oficial](https://twitter.com/landim32oficial) – rodrigo@emagine.com.br

Distribuído sob a licença GPLv2. Veja `LICENSE` para mais informações.

[https://github.com/landim32/LojaVirtual](https://github.com/landim32/LojaVirtual)

## Contributing

1. Faça o _fork_ do projeto (<https://github.com/landim32/LojaVirtual/fork>)
2. Crie uma _branch_ para sua modificação (`git checkout -b landim32/LojaVirtual`)
3. Faça o _commit_ (`git commit -am 'Add some fooBar'`)
4. Push_ (`git push origin landim32/LojaVirtual`)
5. Crie um novo _Pull Request_
