# Loja E-commerce Moderna - InfinityFree

Este projeto é um esqueleto completo de loja online, inspirado em designs modernos de e-commerce. Inclui catálogo de produtos, carrinho de compras e integração com Mercado Pago para pagamentos.

## Funcionalidades

- **Catálogo responsivo**: Grid de produtos com imagens placeholder
- **Carrinho lateral**: Adicione/remova itens, veja subtotal
- **Checkout**: Integração com Mercado Pago (Pix, boleto, cartão)
- **Design moderno**: Layout inspirado em e-commerce contemporâneo

## Estrutura dos Arquivos

| Arquivo | Descrição |
|---------|-----------|
| `index.html` | Página principal com header, produtos e carrinho |
| `products.json` | Dados dos 100 produtos fictícios |
| `assets/style.css` | Estilos CSS modernos e responsivos |
| `assets/app.js` | JavaScript para carrinho e checkout |
| `create_preference.php` | Backend PHP para Mercado Pago |
| `success.html`, `failure.html`, `pending.html` | Páginas de retorno |

## Deploy em InfinityFree

1. Crie conta em https://infinityfree.net
2. Faça upload dos arquivos para `htdocs`
3. Configure o Access Token do Mercado Pago em `create_preference.php`
4. Acesse seu subdomínio

## Teste Local

```bash
cd infinityfree-demo
php -S localhost:8000
```

Abra http://localhost:8000 no navegador.

## Personalização

- **Produtos**: Edite `products.json` com seus produtos reais
- **Imagens**: Substitua os placeholders por URLs reais
- **Cores**: Ajuste as variáveis CSS em `:root`
- **Pagamentos**: Configure outros gateways se necessário

## Próximos Passos

1. Substitua produtos fictícios pelos reais
2. Adicione imagens de produto
3. Configure domínio próprio
4. Implemente banco de dados para persistência
5. Adicione autenticação de usuários

Boa sorte com sua loja! 🚀

Action