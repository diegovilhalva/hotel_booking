let navbar = document.querySelector('.header .navbar');
let menuBtn = document.getElementById('menu-btn');
document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
}


window.onscroll = () => {
    navbar.classList.remove('active');
}

document.querySelectorAll('.contact .row .faq .box h3').forEach((faqBox) =>{
  faqBox.onclick = () => {
    faqBox.parentElement.classList.toggle('active')
  }
})

var swiper = new Swiper(".home-slider", {    
    loop:true,
    effect: "coverflow",
    grabCursor: true,
    spaceBetween: 30,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows:false,
      },
    
  });
  var swiper = new Swiper(".gallery-slider", {    
    loop:true,
    effect: "coverflow",
    centeredSlides: true,
    grabCursor: true,
    slidesPerView: "auto",
    pagination: {
        el: ".swiper-pagination",
      },
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 2,
        slideShadows:true,
      },
    
  });



  var swiper = new Swiper(".reviews-slider", {    
    loop:true,
    grabCursor: true,
    slidesPerView: "auto",
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        
        768: {
          slidesPerView: 1,
          
        },
        1200: {
          slidesPerView: 2,
          
        }, 
      }
  });