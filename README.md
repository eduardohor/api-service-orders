# TESTE PARA VAGA DE DESENVOLVEDOR BACK-END Júnior (Jump)

### Back-end feito com laravel

### Arquitetura 

- PHP 8.2.4
- Laravel 9.52.7
- Composer 2.5.5

### Instalação
```sh
git clone https://github.com/eduardohor/api-service-orders.git
```

```sh
cd api-service-orders
```

- Instalar as dependências

```sh
composer install
```

- Duplicar o arquivo **.env.example** e renomear a copia para **.env**
```sh
cp .env.example .env
```

- Com um editor alterar os dados de banco no arquivo .env para os referente ao seu banco local. O banco utilizado no projeto foi o banco do arquivo 'db-structure.sql' recebido em anexo com nome de 'jump_park'. (O arquivo 'db-structure.sql' pode ser encontrado na pasta database deste repositório. Baixe-o e execute-o em seu banco de dados).

- Logo depois execute o comando abaixo para gerar uma nova chave
```PHP
php artisan key:generate
```

- Gerar documentação com Swagger

```sh
php artisan l5-swagger:generate
```

- Subir o servidor

```sh
php artisan serve
```

 - Acessar documentação com Swagger

```sh
http://localhost:8000/api/documentation
```

Obs: (Para poder criar uma ordem de serviço é necessário ter usuários cadastrados para poder pegar o id de usuário).
