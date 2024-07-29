  document.title += ' // chats';
  const navItems = document.querySelectorAll(".navbar .nav-links:nth-child(2) li a");
  navItems.forEach(navitem => {
    navitem.classList.remove("active");
  });
  const msgAction = document.querySelectorAll(".message .actionChat .action-container"),
    messageReply = document.querySelectorAll(" .message .actionChat i.fa-reply"),
    newMessage = document.querySelectorAll(".message"),
    messageChatNo = document.querySelectorAll(".message .chatNo"),
    submitBtn = document.querySelector(".input-container span");

  newMessage.forEach((Element, index) => {
    messageChatNo[index].textContent = `@${index + 1}`;
  });
  messageReply.forEach((reply, index) => {
    reply.addEventListener("click", () => {
      document.querySelector(".input-container textarea").value = `@${index + 1} `;
      focusOnMessage();
    });
  });
function focusOnMessage(){
  document.querySelector(".input-container textarea").focus();
}
focusOnMessage();
  // help button get data
if(localStorage.getItem('help')){
  window.addEventListener("load", () => {
    document.querySelector(".input-container textarea").value = localStorage.getItem('help');
    localStorage.removeItem("help");
    document.querySelector(".input-container textarea").focus();
  });
}

  // Loop through each paragraph
  let paragraphs = document.querySelectorAll(".message p");
  paragraphs.forEach((paragraph, index) => {
    const text = paragraph.innerHTML;
    if (text.startsWith("@")) {
      let words = text.split(" ");
      const firstWord = words[0];
      if (firstWord === "@help") {
        newMessage[index].style.backgroundColor = "#7a7b1e";
      } else {
        newMessage[index].style.backgroundColor = "#4e8855";
      }
      const updatedWord = `<span style="color:#5fdbdd;">${words[0]}</span>`;
      const updatedText = text.replace(firstWord, updatedWord);
      paragraph.innerHTML = updatedText;
    }
  });

  document.querySelector('.chat-container').scrollTop = window.innerHeight;

  function checkDate() {
    let dateToday = new Date();
    let thisDay = dateToday.getDate() + ", " + (dateToday.getMonth() + 1) + ", " + dateToday.getFullYear(),
      actualDate = thisDay.split(', ');
    for (i = 0; i < newMessage.length; i++) {
      let msgDate = newMessage[i].getAttribute('title'),
        uplDate = msgDate.split(', ');

      if ((uplDate[0] === actualDate[0]) && (uplDate[1] === actualDate[1]) && (uplDate[2] === actualDate[2])) {
        newMessage[i].setAttribute('title', 'Today');
      } else if ((uplDate[0] !== actualDate[0]) && (uplDate[1] === actualDate[1]) && (uplDate[2] === actualDate[2])) {
        var days = actualDate[0] - uplDate[0];
        newMessage[i].setAttribute('title', days + 'd ago');
      } else if ((uplDate[0] !== actualDate[0]) || (uplDate[1] !== actualDate[1]) && (uplDate[2] === actualDate[2])) {
        let months = actualDate[1] - uplDate[1];
        newMessage[i].setAttribute('title', months + 'month ago');
      } else {
        let years = actualDate[2] - uplDate[2];
        newMessage[i].setAttribute('title', years + 'Year ago');
      }
    }
  }
  checkDate();