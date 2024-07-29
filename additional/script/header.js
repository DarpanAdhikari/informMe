  const navOpener = document.querySelector(".navbar .toggleBar .drp.bar"),
    navBarField = document.querySelector(".navbar .nav-links:nth-child(2)"),
    searchBtn = document.querySelector(".navbar .nav-links:nth-child(2) .searchIcon"),
    searchField = document.querySelector(".navbar .nav-links:nth-child(2) .searchField"),
    upperNav = document.querySelector(".navbar"),
    loginBtn = document.querySelectorAll(".login-system h3")[1],
    setting = document.querySelector(".setting"),
    settingMenu = document.querySelector(".setting-menu");
  navOpener.addEventListener("click", (e) => {
    navBarField.classList.add("active");
  });

  // open login form
  function signUp() {
    localStorage.setItem("account", "signUp");
  }
  function openProfile() {
    document.querySelector(".profile-container").classList.add("active");
  }
  // search script-----------------
  const searchFieldInp = document.querySelector(".navbar .nav-links:nth-child(2) .searchField input"),
    searchedList = document.querySelector(".navbar .nav-links:nth-child(2) .searchField .searchList");

  searchBtn.addEventListener("click", () => {
    searchField.classList.add("active");
    searchFieldInp.focus();
  });
  searchFieldInp.oninput = () => {
    if (searchFieldInp.value !== "") {
      searchedList.classList.add("active");
    } else {
      searchedList.classList.remove("active");
    }
  }
  // setting menu--------------------------

  setting.addEventListener("click", () => {
    settingMenu.classList.toggle("active");
  });

  let prevScrollPos = window.pageYOffset;
  window.addEventListener('scroll', () => {
    if (window.innerWidth > 800) {
      const currentScrollPos = window.pageYOffset;

      if (prevScrollPos > currentScrollPos) {
        upperNav.classList.remove("sticky");
      } else {
        upperNav.classList.add("sticky");
      }
      prevScrollPos = currentScrollPos;
    } else {
      upperNav.classList.remove("sticky");
    }
  });

  let AllCards = document.querySelectorAll(".card");
  if (AllCards) {
    AllCards.forEach(cardTitle => {
      let title = cardTitle.querySelector('h1').textContent;
      cardTitle.setAttribute("title", title);
    });
  }

  const helpBtn = document.querySelector('.user-info .help');
  helpBtn.addEventListener("click", () => {
    localStorage.setItem("help", "@help ");
  });


  // logo script
  document.querySelector(".logo").addEventListener('click', backKey);

  function backKey() {
    if (document.referrer.includes(window.location.hostname)) {
      let home = document.querySelectorAll('.navbar .nav-links:nth-child(2) a')[0];
      if (home.classList.contains('active')) {
        window.location.href = "Home";
      }else{
        history.back();
      }
    } else {
      window.location.href = "Home";
    }
  }
  

  // script on the basis of cursor
  document.addEventListener("click", (event) => {
    if (!navBarField.contains(event.target) && !navOpener.contains(event.target)) {
      navBarField.classList.remove("active");
    }
    if (!searchBtn.contains(event.target) && !searchField.contains(event.target)) {
      searchField.classList.remove("active");
    }
    if (!settingMenu.contains(event.target) && !setting.contains(event.target)) {
      settingMenu.classList.remove("active");
      if(document.querySelector(".profile-container")){
        document.querySelector(".profile-container").classList.remove("active");
      }
    }
  });

  let inputField = document.querySelectorAll('input'),
    textareaField = document.querySelectorAll('textarea');
  if (inputField) {
    inputField.forEach(inputV => {
      inputV.addEventListener('input', (value) => {
        validateInput(value);
      });
    });
  }
  if (textareaField) {
    textareaField.forEach(textareaV => {
      textareaV.addEventListener('input', (value) => {
        validateInput(value);
      });
    });
  }

  function validateInput(event) {
    var inputText = event.target.value;
    var restrictedWords = ["muji", "chigne", "chod", "chikne", "lado", "puti", "fuck", "dick", "vegina", "sex", "bhosadi", "randi", "madar", "madhar"];
    for (var i = 0; i < restrictedWords.length; i++) {
      var restrictedWord = restrictedWords[i];
      if (inputText.toLowerCase().includes(restrictedWord)) {
        var asterisks = "".repeat(restrictedWord.length);
        inputText = inputText.replace(new RegExp(restrictedWord, 'gi'), asterisks);
      }
    }
    event.target.value = inputText;
  }

  document.addEventListener("DOMContentLoaded", function () {
    alertInfo();
    function alertInfo() {
      let alertInfo = document.querySelector(".alert_info");
      if (alertInfo) {
        setTimeout(() => {
          alertInfo.classList.remove("active");
        }, 5000);
      }
    }
  });
  // Usage example:
  let cardDate = document.querySelectorAll(".UploadDate");
  function checkCardDate() {
    let dateToday = new Date();
    let thisDay = dateToday.getDate() + ", " + (dateToday.getMonth() + 1) + ", " + dateToday.getFullYear(),
      actualDate = thisDay.split(', ');
    for (i = 0; i < cardDate.length; i++) {
      let msgDate = cardDate[i].textContent,
        uplDate = msgDate.split(', ');

      if ((uplDate[0] === actualDate[0]) && (uplDate[1] === actualDate[1]) && (uplDate[2] === actualDate[2])) {
        cardDate[i].textContent = 'Today';;
      } else if ((uplDate[0] !== actualDate[0]) && (uplDate[1] === actualDate[1]) && (uplDate[2] === actualDate[2])) {
        var days = actualDate[0] - uplDate[0];
        cardDate[i].textContent = days + 'd ago';
      } else if (((uplDate[0] !== actualDate[0]) || (uplDate[0] === actualDate[0])) && (uplDate[1] !== actualDate[1]) && (uplDate[2] === actualDate[2])) {
        let months = actualDate[1] - uplDate[1];
        cardDate[i].textContent = months + 'month ago';
      } else {
        let years = actualDate[2] - uplDate[2];
        cardDate[i].textContent = years + ' Year ago';
      }
    }
  }
  if (cardDate) {
    checkCardDate();
  }


  // number counter
  let numberElements = document.querySelectorAll('.specialNumber');
  if (numberElements.length > 0) {
    function formatNumber(number) {
      if (number >= 1000000) {
        return (number / 1000000).toLocaleString('ne-NP', { maximumFractionDigits: 1 }) + "M";
      } else if (number >= 1000) {
        return (number / 1000).toLocaleString('ne-NP', { maximumFractionDigits: 1 }) + "k";
      } else if (Number.isInteger(number)) {
        return number.toLocaleString('ne-NP');
      }
      return number.toFixed(1);
    }

    numberElements.forEach((numberElement) => {
      let targetNumber = parseFloat(numberElement.textContent);
      let currentNumber = 0;
      let duration = 2000;
      let interval = 10;
      let increment = (targetNumber / (duration / interval));

      let animation = setInterval(() => {
        currentNumber += increment;
        if (currentNumber >= targetNumber) {
          clearInterval(animation);
          currentNumber = targetNumber;
        }
        numberElement.textContent = formatNumber(currentNumber);
      }, interval);
    });
  }

  // dialogue box for account delete
  let dialogueBox = document.querySelector('.postVisitSgt'),
    accountDelete = document.getElementById('delete-account');

  if (accountDelete) {
    accountDelete.onclick = () => {
      dialogueBox.classList.add('active');
      dialogueBox.querySelector('input').focus();
    }
    document.addEventListener('click', (e) => {
      if (!accountDelete.contains(e.target) && !dialogueBox.querySelector('.dialogue').contains(e.target)) {
        dialogueBox.classList.remove('active');
      }
    });
  }

  // service worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('sw.js').then(function (registration) {
      });
    });
  }

  document.addEventListener("keydown", (e)=> {
   if ((e.ctrlKey && e.key === 'h')) {
      e.preventDefault();
        window.location.href = "Home";
      }
    if ((e.ctrlKey && e.key === 'd')) {
      e.preventDefault();
        window.location.href = "admin-panel";
      }
      if ((e.ctrlKey && e.key === 'i')){
      e.preventDefault();
        window.location.href = "chat";
      }
      if ((e.ctrlKey && e.key === '/')){
          e.preventDefault();
          window.location.href = "new-post";
        }
        if ((e.ctrlKey && e.key === 'y')){
          e.preventDefault();
            window.location.href = "posts";
          }
     if(document.querySelector(".profile-container")){
       if ((e.ctrlKey && e.key === 'u')){
        e.preventDefault();
        document.querySelector(".profile-container").classList.toggle("active");
      }
     }
  });

  const yourDate = new Date();
  const dateStr = yourDate.toString();
  const timeZoneAbbreviation = dateStr.match(/\(([^)]+)\)/)[1];
  const userLocation = timeZoneAbbreviation.split(" ");
  if(userLocation){
    const myDiv = document.querySelector('.logo');
    myDiv.style.setProperty("--timezone-content", `"${userLocation[0]}"`);
  }