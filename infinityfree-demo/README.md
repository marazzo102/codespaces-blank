# Demonstração de loja para InfinityFree

Este pequeno projeto mostra uma forma simples de criar um site de comércio eletrônico usando hospedagem gratuita no [InfinityFree](https://infinityfree.net/).

O objetivo é ter:

* Catálogo estático de produtos (dois exemplos) em `index.html`.
* Um pequeno script PHP (`create_preference.php`) que cria uma preferência no Mercado Pago e redireciona para o checkout.

---

## Deploy em InfinityFree

1. Crie uma conta em https://infinityfree.net (e confirme o e-mail).
2. No painel, clique em **Control Panel** para a conta desejada.
3. Na seção **Files** use o **File Manager** ou configure FTP usando o servidor `ftpupload.net`, o usuário/​senha fornecidos.
4. Envie os arquivos deste repositório para a pasta `htdocs`.
   * por exemplo, `index.html`, `create_preference.php`, `success.html`, etc.
5. Se quiser usar um domínio próprio, adicione na seção **Addon Domains** ou crie um subdomínio gratuito.
6. Certifique‑se de que a conta tem suporte a PHP (InfinityFree oferece PHP 7.4/8.0) para executar o script `create_preference.php`.

## Configuração do pagamento

1. Cadastre‑se no Mercado Pago (ou outro gateway de sua preferência).
2. Obtenha o **Access Token** da sua conta de teste ou produção.
3. Edite `create_preference.php` substituindo a variável `$access_token` pelo seu valor.
4. No `index.html`, ajuste os detalhes dos produtos/tabulagem de preços conforme desejar.

> O mesmo princípio vale para PagSeguro, Pagar.me, etc. use a API correspondente.

## Testando localmente

Para simular um ambiente, você pode executar um servidor PHP local:

```bash
php -S localhost:8000 -t /workspaces/codespaces-blank/infinityfree-demo
```

Depois abra `http://localhost:8000` e clique em "Comprar" para ver o redirecionamento.

## Extensões

Esses arquivos são meramente um ponto de partida. Para um projeto real você precisará:

* Base de dados para catalogar 100+ produtos.
* Autenticação de usuários, carrinho, estoque, etc.
* Tratamento dos retornos do Mercado Pago (webhooks) para atualizar estados de pedido.
* SSL (InfinityFree oferece Let's Encrypt automático quando usado com domínio personalizado).

Boa sorte com sua loja! Se quiser ajuda para estendê-la, é só pedir.