let navbar = document.querySelector('.header .navbar');
let menuBtn = document.querySelector('#menu-btn');
document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    menuBtn.classList.toggle('fa-times');

}
window.onscroll = () => {
    navbar.classList.remove('active');
    menuBtn.classList.remove('fa-times');
}