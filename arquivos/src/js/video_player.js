function playVideo(url) {
    /* Removendo video caso já exista um aberto */
    closeVideo();
    
    /* Criando novo elemento video */
    video_player = document.createElement('div');
    video_player.id = 'video_player';
    
    /* Criando tag video para reprodução */
    let video_element = document.createElement('video');
    video_element.src = url;
    video_element.oncanplay = setLoaded;
    video_element.controls = true;
    video_element.autoplay = true;
    
    /* Criando botão de fechar */
    let video_close_btn = document.createElement('button');
    video_close_btn.innerHTML = '<i class="fa fa-times" aria-hidden="true">';
    video_close_btn.onclick = closeVideo;
    
    /* Adicionando video na tag body da página */
    video_player.appendChild(video_close_btn);
    video_player.appendChild(video_element);
    let body_element = document.getElementsByTagName('body')[0];
    body_element.appendChild(video_player);
    
    setLoading();
}

function setLoading() {
    let video_player = document.getElementById('video_player');
    if (video_player) {
        let video = video_player.getElementsByTagName('video')[0];
        video.style = 'display: none';
        let loading = document.createElement('div');
        loading.id = 'video_loading';
        video_player.appendChild(loading);
    }
}

function setLoaded() {
    let video_player = document.getElementById('video_player');
    if (video_player) {
        let video = video_player.getElementsByTagName('video')[0];
        video.style = '';
        document.getElementById('video_loading').remove();
    }
}

function closeVideo() {
    let video_player = document.getElementById('video_player');
    if (video_player) {
        video_player.remove();
    }
}