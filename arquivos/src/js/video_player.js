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
    video_element.currentTime = getVideoTime(getFileName(url));
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
    
    savingTimeLoop(video_element)
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
        let loading = (document.getElementById('video_loading'));
        if (loading) {
            loading.remove();
        }
    }
}

function closeVideo() {
    let video_player = document.getElementById('video_player');
    if (video_player) {
        video_player.remove();
    }
}

async function savingTimeLoop(video_element) {
    await new Promise(r => setTimeout(r, 500));
    if (!video_element.paused) {
        if (video_element.currentTime != 0) {
            let video_name = getFileName(video_element.src);
            saveTime(video_name, video_element.currentTime)
        }
    }
    savingTimeLoop(video_element);
}

function getFileName(url) {
    let urlDecoded = decodeURI(url);
    return urlDecoded.substring(urlDecoded.lastIndexOf('/') + 1);
}

function getVideoTime(video_name) {
    let videos = (localStorage['videos'] ?  JSON.parse(localStorage['videos']) : {});
    return (videos[video_name] ? videos[video_name] : 0);
}

function saveTime(video_name, video_time) {
    let videos = (localStorage['videos'] ?  JSON.parse(localStorage['videos']) : {});
    videos[video_name] = parseFloat(video_time.toFixed(2));
    localStorage['videos'] = JSON.stringify(videos);
}
