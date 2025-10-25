function openTab(event, tabName) {
    let tabContents = document.getElementsByClassName('tab-content');
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].style.display = 'none';
    }
    let tabLinks = document.getElementsByClassName('tab-link');
    for (let i = 0; i < tabLinks.length; i++) {
        tabLinks[i].className = tabLinks[i].className.replace(' active', '');
    }
    document.getElementById(tabName).style.display = 'block';
    event.currentTarget.className += ' active';
}
document.addEventListener('DOMContentLoaded', (event) => {
    // Pastikan tab 'myQuests' aktif saat halaman dimuat
    let myQuestsTab = document.getElementById('myQuests');
    if (myQuestsTab) {
        myQuestsTab.style.display = 'block';
    }
});