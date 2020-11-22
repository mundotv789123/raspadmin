function shortLink() {
    let id = $('#id')[0].value;
    let url = $('#url')[0].value;
    if (id === "" || url === "") {
        alertMessage('Todos os dados precisam ser informados', 'error');
        return;
    }
    $.ajax({
        url: '/',
        method: 'POST',
        data: {
            uuid: id,
            url: url
        },
        success: function (data) {
            if (data['success']) {
                alertMessage('Link criado com sucesso em https://rasp.ga/' + id, 'success');
            } else {
                alertMessage(data['message'], 'error');
            }
        },
        error: function () {
            alertMessage('Erro interno ao processar requisição', 'error');
        }
    });
}
function alertMessage(msg, type) {
    let alert = $(".alert")[0];
    alert.style.opacity = 100;
    if (type === "success") {
        alert.className = "alert alert-success";
    } else if (type === "error") {
        alert.className = "alert alert-danger";
    } else {
        console.error('invalid alert type');
    }
    $(".alert")[0].innerHTML = msg;
}