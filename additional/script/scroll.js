  const selectCarousel = document.querySelector(".card-container"),
    carouselCards = document.querySelectorAll(".Scroll-items .card-container .card");

 if(carouselCards){
  if (carouselCards.length > 0) {
    let cardWidth = carouselCards[0].clientWidth;
    function scrollCarousel() {
      if ((selectCarousel.scrollLeft + selectCarousel.clientWidth >= selectCarousel.scrollWidth - 10)) {
        selectCarousel.style.scrollBehavior = "auto";
        selectCarousel.scrollLeft = 0;
      } else {
        selectCarousel.scrollLeft += cardWidth;
        selectCarousel.style.scrollBehavior = "smooth";
      }
    }
    let carouselInterval = setInterval(scrollCarousel, 1000);
    selectCarousel.addEventListener("mouseover",()=>{
      clearInterval(carouselInterval);
    });
    selectCarousel.addEventListener("mouseout",()=>{
      carouselInterval = setInterval(scrollCarousel, 1000);
    });
  }else{
    selectCarousel.style.display = "none";
  }

  let containerCard = document.querySelectorAll('.main-container .card');
  if (containerCard.length > 1) {
    document.querySelector(".fa-arrow-down-wide-short").style.display = "block";
  } else {
    document.querySelector(".fa-arrow-down-wide-short").style.display = "none";
  }
  document.querySelector(".fa-arrow-down-wide-short").onclick = () => {
    let container = document.querySelector('.main-container');
    container.scrollTop += 350;
  }
 }