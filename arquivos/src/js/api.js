const api_url = '/api';
const path_icons = '/src/img/icons';

function getFiles(path, func) {
    $.ajax({
        url: api_url+path,
        method: 'GET',
        success: function (data) {
            func(data);
        },
        error: function (res) {
            console.log(res.status);
            func({success: false, code: res.status, message: 'Internal server error'});
        }
    });
}

function getFileExt(name) {
    let name_splited = name.split('.');
    return name_splited[name_splited.length - 1];
}

function getFileIcon(name, is_dir=false) {
    if (is_dir) {
        return path_icons+'/folder.png';
    }
    switch (getFileExt(name)) {
        case 'mp4':
        case 'mkv':
            return path_icons+'/video.png';
        case "exe":
           return path_icons+'/exe.png';
        case "zip":
        case "rar":
        case "tar":
        case "gz":
            return path_icons+'/compact.png';
        case "java":
        case "jar":
        case "class":
            return path_icons+'/java.png';
        case 'iso':
        case 'img':
            return path_icons+'/iso.png';
        case "part":
            return path_icons+'/part.png';
        default:
            return path_icons+'/document.png';
    }
    return path_icons+'/document.png';
}
