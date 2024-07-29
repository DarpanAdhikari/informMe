document.title += " // " + document.querySelector(".profile h2").textContent;

document.addEventListener("DOMContentLoaded", function() {
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

let inputField = document.querySelectorAll('input:not([type="file"])'),
textareaField = document.querySelectorAll('textarea');
if(inputField){
  inputField.forEach(inputV=>{
    inputV.addEventListener('input',(value)=>{
      validateInput(value);
    });
  });
}
if(textareaField){
  textareaField.forEach(textareaV=>{
    textareaV.addEventListener('input',(value)=>{
      validateInput(value);
    });
  });
}
function validateInput(event) {
  var inputText = event.target.value;
  var restrictedWords = ["muji", "chigne", "chod","chikne","lado","puti","fuck","dick","vegina","sex","bhosadi","randi","madar","madhar"];
  for (var i = 0; i < restrictedWords.length; i++) {
      var restrictedWord = restrictedWords[i];
      if (inputText.toLowerCase().includes(restrictedWord)) {
          var asterisks = "".repeat(restrictedWord.length);
          inputText = inputText.replace(new RegExp(restrictedWord, 'gi'), asterisks);
      }
  }
  event.target.value = inputText;
}
const navigatorBtn = document.querySelector(".navbar-toggle .drp.bar-cr"),
navField = document.querySelector('.sidebar');
navigatorBtn.addEventListener('click',()=>{
  navField.classList.toggle('active');
})
document.addEventListener('click',(e)=>{
  if(!navField.contains(e.target) && !navigatorBtn.contains(e.target)){
    navField.classList.remove('active');
  }
});
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
window.oncontextmenu = () => {
  return false;
}
// document.body.addEventListener('keydown', function (e) {
//   if ((e.ctrlKey && e.key === 'u') ||
//       (e.ctrlKey && e.key === 'p') || 
//       (e.ctrlKey && e.key === 's')) 
//   {
//       e.preventDefault();
//   }
//   if (e.ctrlKey && e.shiftKey && e.key === 'I') {
//     e.preventDefault();
//   }
//   if ((e.ctrlKey || e.metaKey) && e.altKey && e.key === 'c') {
//     e.preventDefault();
//   }
//   if ((e.ctrlKey || e.metaKey) && e.altKey && e.key === 'i') {
//     e.preventDefault();
//   }
// });