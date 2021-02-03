async function hideSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarButton = document.querySelector('.hide-sidebar');
    const sidebarButtonShow = document.querySelector('.show-sidebar');
    const content = document.querySelector('.content');

    sidebar.style.left = "-15vw";
    sidebarButton.style.left = "0vw";
    sidebarButtonShow.style.left = "0vw";
    content.style.left = "0vw";
    content.style.width = "95vw";
    await sleep(500);
    sidebarButton.style.display = "none";
    sidebarButtonShow.style.display = "flex";
}
async function showSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarButton = document.querySelector('.hide-sidebar');
    const sidebarButtonShow = document.querySelector('.show-sidebar');
    const content = document.querySelector('.content');

    sidebar.style.left = "0vw";
    sidebarButton.style.left = "15vw";
    sidebarButtonShow.style.left = "15vw";
    content.style.left = "15vw";
    content.style.width = "80vw";
    await sleep(500);
    sidebarButton.style.display = "flex";
    sidebarButtonShow.style.display = "none";
}

document.querySelector('.hide-sidebar').addEventListener("click", hideSidebar);
document.querySelector('.show-sidebar').addEventListener("click", showSidebar);