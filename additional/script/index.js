  const anchorCard = document.querySelectorAll(".carousel-container .carousel a");
  let textWriter = document.querySelector('.text-writer');
  let counter = 0;
  let cardCount = anchorCard.length;
  if (anchorCard && textWriter) {
    function scrollCarousel() {
      if (document.hidden) {
        return;
      }

      textWriter.classList.add('writing');
      anchorCard.forEach(card => {
        card.classList.remove("active");
      });

      if (counter >= cardCount - 1) {
        counter = 0;
      } else {
        counter += 1;
      }
      anchorCard[counter].classList.add("active");
      let writerTxt = anchorCard[counter].querySelector('p');
      let txt = writerTxt.textContent;
      textWriter.querySelector("p").textContent = txt;
    }

    if (cardCount > 1) {
      setInterval(scrollCarousel, 5000);
    }
    scrollCarousel();
  }