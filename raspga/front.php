<!DOCTYPE html>
<html>
    <head>
        <title>RaspGa encurtador de links</title>
        <link rel="stylesheet" href="/estilo.css"/>
    </head>
    <body>
        <div class="row-center">
            <div class="form-center">
                <h1>Encurtador de links</h1>
                <div>
                    <label for="id">Nome da url</label><br>
                    <input type="text" id="id" placeholder="Ex: google" class="form-input"/>
                </div>
                <div>
                    <label for="url">Link a ser encurtado</label><br>
                    <input type="text" id="url" placeholder="Ex: https://google.com" class="form-input"/>
                </div>
                <button class="btn btn-green" onclick="shortLink()">Encurtar</button>
            </div>
            <div class='alert' style="opacity: 0">
                <p class="alert-message"></p>
            </div>
        </div>
    </body>
    <script src="/jquery-3.5.1.min.js"></script>
    <script src="/app.js"></script>
</html>

