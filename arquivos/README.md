# Raspadmin Arquivos

Site Demo: https://arquivos.raspadmin.tk

**Função**
- Facilitar navegação entre arquivos em um servidor web.
- Abrir vídeos sem baixar de forma dinâmica.

**Notas**
- Lembre-se de configurar o seu servidor web.
- A pasta **./files** é a pasta principal que será listada.

**Instalação no nginx**
- Abra o arquivo de configuração do seu site, geralmente fica em **/etc/nginx/sites-available** o arquivo padrão é o **default** ou o **default.conf**
- Você precisa editar as seguintes linhas.
```
location / {
    try_files $uri $uri/ =404;
}
```
- Ele deve ficar assim para que o arquivo **index.html** consiga acessar o caminho da url.
```
location / {
    try_files $uri $uri/ /index.html?$query_string;
}
```

- Para adicionar a API basta adicionar uma nova linha
```
location /api {
    try_files $uri $uri/ /api.php?$query_string;
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


