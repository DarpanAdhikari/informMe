  const formBtn = document.querySelectorAll("form p a"),
    formContainer = document.querySelector(".form-container"),
    doorIcon = document.querySelector(".door i"),
    chooseFileBtn = document.querySelector("#chooseFile"),
    previewBtn = document.querySelector("#previewBtn");
  let title = document.title;

  formBtn.forEach(toggleBtn => {
    toggleBtn.addEventListener("click", (e) => {
      e.preventDefault();
      document.title = title + ' // ' + toggleBtn.textContent;
      formContainer.classList.toggle("logInform");
      if (formContainer.classList.contains("logInform")) {
        doorIcon.setAttribute("class", "fas fa-lock");
        document.querySelector(".door").classList.remove("open");
      } else {
        document.querySelector(".door").classList.add("open");
        doorIcon.setAttribute("class", "fas fa-key");
      }
    });
  });

  let fileName = document.querySelector("#choosePP");
  chooseFileBtn.addEventListener("click", () => {
    fileName.click();
  });
  fileName.onchange = () => {
    chooseFileBtn.value = fileName.value;
  }

  // password field data viewer script=============

  const passwordShow = document.querySelectorAll("#showHide"),
    password = document.querySelectorAll("input[type='password']");

  passwordShow.forEach(showPass => {
    showPass.addEventListener("click", () => {
      if ((password[0].type === "password" && password[1].type === "password") || (password[2].type === "password")) {
        password[0].type = "text";
        password[1].type = "text";
        password[2].type = "text";
      } else {
        password[0].type = "password";
        password[1].type = "password";
        password[2].type = "password";
      }
      if (showPass.className === "fas fa-eye-slash") {
        showPass.setAttribute("class", "fas fa-eye");
      } else {
        showPass.setAttribute("class", "fas fa-eye-slash");
      }
    });
  })
  password[1].addEventListener("input", () => {
    if (password[0].value === password[1].value) {
      password[1].style.border = "1px solid #2e8e0b";
    } else {
      password[1].style.border = "1px solid #bf280d";
    }
  });

  // preopen if user open some thing first
  let userDemand = localStorage.getItem("account");
  if (userDemand === "signUp") {
    document.title = title + " // Register";
    formContainer.classList.add("logInform");
    doorIcon.setAttribute("class", "fas fa-key");
    document.querySelector(".door").classList.add("open");
  } else {
    formContainer.classList.remove("logInform");
    document.title = title + " // Log In";
    doorIcon.setAttribute("class", "fas fa-lock");
    document.querySelector(".door").classList.remove("open");
  }
  //  -------------------remove account from localstorage
  window.onload = () => {
    localStorage.removeItem("account");
  }

  // ----------------------preview script-----------


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

  window.oncontextmenu = () => {
    return false;
  }
  document.body.addEventListener('keydown', event => {
    if (event.ctrlKey && 'usp'.indexOf(event.key) !== -1) {
      event.preventDefault();
    }
  });

  document.querySelectorAll('form').forEach(form => {
    form.querySelectorAll('input')[0].focus();
  })

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