function isMobile() {
  const toMatch = [
    /android/i,
    /webOS/i,
    /iphone/i,
    /ios/i,
    /BlackBerry/i,
    /windows Phone/i,
  ];
  return toMatch.some((toMatchItem) => {
    return navigator.userAgent.match(toMatchItem);
  });
}

const mainFeild = document.querySelector(".share-data"),
  shareIcon = document.querySelector(".share-icon"),
  closeShareField = document.querySelector(".social-share .close"),
  urlOfpage = document.querySelector("#copy-link"),
  copyButton = document.querySelector("#copy-btn"),
  shareTitle = encodeURIComponent(document.querySelector("title").textContent);
const Link = window.location;
const messAge = "Hey,its awesome 🥰 wow!.";

if (shareIcon) {
  urlOfpage.value = Link;
  shareIcon.addEventListener("click", () => {
    mainFeild.classList.add("active");
    shareIcon.style.display = "none";
    document.querySelector(".share-data").classList.add("active");
  });
  closeShareField.addEventListener("click", () => {
    shareIcon.style.display = "block";
    mainFeild.classList.remove("active");
  });
  copyButton.addEventListener("click", () => {
    if (copyButton.textContent === "COPY") {
      navigator.clipboard.writeText(urlOfpage.value);
      copyButton.textContent = "COPIED";
      setTimeout(() => {
        copyButton.textContent = "COPY";
      }, 3000);
    }
  });

  let shareField = document.querySelector(".social-share");
  document.addEventListener("click", (e) => {
    if (!shareIcon.contains(e.target) && !shareField.contains(e.target)) {
      shareIcon.style.display = "block";
      mainFeild.classList.remove("active");
    }
  });
  // social link share method
  const AllSocialIcon = document.querySelectorAll(".social-share a");
  AllSocialIcon.forEach((shareIcon) => {
    shareIcon.addEventListener("click", (event) => {
      event.preventDefault();
      if (shareIcon.classList.contains("facebook")) {
        window.open(`http://www.facebook.com/share.php?u=${Link}`, "_blank");
      }
      if (shareIcon.classList.contains("messenger")) {
        window.open(`http://www.facebook.com/dialog/send/?link=${Link}&app_id=561059605946205&redirect_uri=${Link}`, "_blank");
      }
      if (shareIcon.classList.contains("whatsapp")) {
        window.open(`https://api.whatsapp.com/send?text=${Link}`, "_blank");
      }
      if (shareIcon.classList.contains("twitter")) {
        window.open(`http://twitter.com/share?&url=${Link}&text=${messAge}&hashtags=${shareTitle}`, "_blank");
      }
      if (shareIcon.classList.contains("pinterest")) {
        window.open(`https://pinterest.com/pin/create/button/?url=${Link}`, "_blank");
      }
      if (shareIcon.classList.contains("telegram")) {
        window.open(`https://t.me/share/url?url=${Link}`, "_blank");
      }
      if (shareIcon.classList.contains("reddit")) {
        window.open( `http://www.reddit.com/submit?url=${Link}&title=${shareTitle}`, "_blank");
      }
      if (shareIcon.classList.contains("envelope")) {
        window.open(`mailto:?subject=${encodeURIComponent(messAge)}&body=${encodeURIComponent(Link)}`, "_blank");
      }
    });
  });
}
