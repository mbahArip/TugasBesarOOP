function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function logout() {
    var logout = confirm("Yakin ingin keluar?");

    if (logout == true) {
        window.location.href = 'http://localhost/web/TugasBesarOOP/logout';
    } else {
        
    }
}

function selectedMenu(args) {
    console.log(args);
    document.getElementById(args).classList.add('menu-selected');
}