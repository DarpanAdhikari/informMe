  const darkModeIcon = document.querySelectorAll(".profile .theme .tIcons h3"),
    darkModeIconI = document.querySelectorAll('.profile .theme .tIcons i');

  darkModeIcon[0].onclick = () => {
    darkModeIcon[1].classList.remove("active");
    if (darkModeIconI[0].className === "fas fa-sun") {
      makeDark();
      darkModeIcon[0].classList.add('active');
      darkModeIcon[0].querySelector('div').textContent = "Light Mode";
      darkModeIconI[0].className = "fas fa-moon";
    } else {
      removeDark();
      darkModeIcon[0].classList.remove('active');
      darkModeIcon[0].querySelector('div').textContent = "Dark Mode";
      darkModeIconI[0].className = "fas fa-sun";
    }
  }
  darkModeIcon[1].onclick = () => {
    system();
    darkModeIcon[1].classList.toggle("active");
  }

  function system() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      if (darkModeIcon[1].classList.contains("active")) {
        removeDark();
        localStorage.removeItem("darkMode");
      } else {
        makeDark();
        localStorage.setItem("darkMode", "system");
      }
    }
    setInterval(system,1000);
  }

  let previousTheme = localStorage.getItem("darkMode");
  if (previousTheme === "on" && previousTheme !== "system") {
    makeDark();
    darkModeIcon[0].classList.add('active');
    darkModeIcon[0].querySelector('div').textContent = "Light Mode";
    darkModeIconI[0].className = "fas fa-moon";
  }
  if (previousTheme !== "on" && previousTheme === "system") {
    system();
    darkModeIcon[1].classList.add("active");
    darkModeIconI[0].className = "fas fa-sun";
  }

  function makeDark() {
    localStorage.setItem("darkMode", "on");
    document.body.classList.add("dark-mode");
  }
  function removeDark() {
    localStorage.removeItem("darkMode");
    document.body.classList.remove("dark-mode");
  }