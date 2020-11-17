# RaspGa

link: https://rasp.ga

**função**
- encurtar links de forma simples e fácil
- facilitar o entendimento de alguns conceitos básicos de php
- usa ferramentas como jquery para melhorar a dinamica

**notas**
- você precisa configurar o arquivo **index.php**
- na primeira execução descomenta a linha que cria a tabela no banco de dados

**instalação no nginx**
- abra o arquivo de configuração do seu site, geralmente fica em **/etc/nginx/sites-available** o arquivo padrão é o **default** ou o **default.conf**
- vc precisa editar as seguintes linhas
```
location / {
    try_files $uri $uri/ =404;
}
```
- ele deve ficar assim para que o arquivo **index.php** consiga acessar o caminho da url
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

- para ativar o php basta adicionar essa linha
```
location ~ \.php$ {
       include snippets/fastcgi-php.conf;
       fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
}
```

- depois só instalar o **php7.2-fpm** (no ubuntu ou debian você pode instalado com o comando **apt install php7.2-fpm**)
- após isso basta reiniciar o nginx com o comando **service nginx reload**

**instalação no apache2**
- no apache não precisa fazer muita coisa só executar o comando **a2enmod rewrite**
- todas as configurações no apache deve está no arquivo **.htaccess**

