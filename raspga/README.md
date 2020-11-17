# RaspGa

Site Demo: https://rasp.ga

**Função**
- Encurtar links de forma simples e fácil.
- Facilitar o entendimento de alguns conceitos básicos de php.
- Usa ferramentas como jquery para melhorar a dinâmica.

**Notas**
- Você precisa configurar o arquivo **index.php**.
- Na primeira execução descomenta a linha que cria a tabela no banco de dados.

**Instalação no nginx**
- Abra o arquivo de configuração do seu site, geralmente fica em **/etc/nginx/sites-available** o arquivo padrão é o **default** ou o **default.conf**
- Você precisa editar as seguintes linhas.
```
location / {
    try_files $uri $uri/ =404;
}
```
- Ele deve ficar assim para que o arquivo **index.php** consiga acessar o caminho da url.
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

- Para ativar o php basta adicionar essa linha.
```
location ~ \.php$ {
       include snippets/fastcgi-php.conf;
       fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
}
```

- Depois só instalar o **php7.2-fpm** (no ubuntu ou debian você pode instalado com o comando **apt install php7.2-fpm**)
- Após isso basta reiniciar o nginx com o comando: **service nginx reload**

**Instalação no apache2**
- No apache não precisa fazer muita coisa só executar o comando: **a2enmod rewrite**
- Todas as configurações no apache deve está no arquivo: **.htaccess**

