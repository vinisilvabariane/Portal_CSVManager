# Portal MultiGarantia

Bem-vindo ao **Portal MultiGarantia** – uma solução profissional para gestão e consulta de garantias de produtos.

## Descrição

O Portal MultiGarantia é um sistema web desenvolvido para que clientes possam consultar a garantia de seus produtos utilizando o número de série ou a nota fiscal. O sistema possui dois níveis de acesso:

- **Membro:** Pode consultar garantias por número serial ou nota fiscal.
- **Administrador:** Além das funcionalidades de membro, pode cadastrar novos usuários e gerenciar registros.

## Requisitos

- **PHP** 8 ou superior
- **MySQL** 8 ou superior
- **Laragon** (ou outro ambiente PHP)
- **Apache** (ou Nginx)
- **Composer** (opcional, para dependências PHP)

## Tecnologias Utilizadas

- PHP (backend)
- MySQL (banco de dados)
- JavaScript (frontend)
- Bootstrap (estilização)
- Apache (servidor web)

## Instalação

1. **Clone o repositório:**
   ```sh
   git clone https://github.com/seu-usuario/PortalMultiGarantia.git
   ```
2. **Configure o DOCUMENT_ROOT do seu ambiente:**
   ```sh
   Exemplo: require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/arquivo.php";
   ```

## Uso

- Para **administrar usuários e registros**, faça login como administrador e acesse o painel de controle.
