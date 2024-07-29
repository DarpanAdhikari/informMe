
// script for account table-----------------------------------------------------------------------------------
let accountTable = document.querySelector('.table-responsive.accounts');
if(accountTable){
    const postitionEditor = document.querySelectorAll(".acc.action .fa-edit"),
    openPositionEdit = document.querySelectorAll(".AcceditForm");
    postitionEditor.forEach((data, index) => {
    data.addEventListener("click", () => {
    openPositionEdit[index].classList.toggle("active");
  });
});
document.addEventListener("click", (event) => {
  postitionEditor.forEach((data, index) => {
    if (!data.contains(event.target) && !openPositionEdit[index].contains(event.target)) {
      openPositionEdit[index].classList.remove("active");
    }
  });
});
const sidebarItems = document.querySelectorAll(".sidebar li");
sidebarItems.forEach(item => {
  item.classList.remove("active");
  sidebarItems[7].classList.add("active");
});
document.querySelectorAll(".sidebar ul li.management ul li")[1].classList.add("active");
}

// script for navigation------------------------------------------------------------------------------------
let navigationTable = document.querySelector('.table-responsive.navigation')
if(navigationTable){
    const sidebarItems = document.querySelectorAll(".sidebar li");
    sidebarItems.forEach(item => {
      item.classList.remove("active");
      sidebarItems[7].classList.add("active");
    });
    document.querySelectorAll(".sidebar ul li.management ul li")[2].classList.add("active");
}
// script for chat (conversation)------------------------------------------------------------------------------
let chatTable = document.querySelector('.table-responsive.conversation');
if(chatTable){
    const sidebarItems = document.querySelectorAll(".sidebar ul.navbar li");
    sidebarItems.forEach(item => {
      item.classList.remove("active");
      sidebarItems[7].classList.add("active");
    }); 
   let hoverNav = document.querySelectorAll(".sidebar ul li.management ul li");
   hoverNav[hoverNav.length-2].classList.add("active");
}
// table data selection-------------------------------------------------------------------------------------------
const selectChats = document.querySelectorAll("form#selectItem input");
if(selectChats){
  const deleteButton = document.getElementById("deleteChat");
 if(deleteButton){
  deleteButton.style.display = "none";
  function updateDeleteButtonVisibility() {
    const anyChecked = Array.from(selectChats).some(chat => chat.checked);
    if (anyChecked) {
      deleteButton.style.display = "block";
    } else {
      deleteButton.style.display = "none";
    }
  }
  selectChats[0].addEventListener("click", () => {
    selectChats.forEach(checkChat => {
      checkChat.checked = selectChats[0].checked;
    });
    updateDeleteButtonVisibility();
  });
  selectChats.forEach(chats => {
    chats.addEventListener("click", () => {
      updateDeleteButtonVisibility();
    });
  });
  deleteButton.addEventListener('click',(e)=>{
    selectChats.forEach(check=>{
        if(selectChats[0].checked){
          if(!check.checked){
             e.preventDefault();
          }
        }
    })
  })
 }
}

// script for post and drafts management----------------------------------------------------------------------------------
let postTable = document.querySelector('.table-responsive.post');
if(postTable){
  const previewArticle = document.querySelector(".article-preview");
  const tr = document.querySelectorAll(".post tbody tr");
  
  tr.forEach((previewBtn, index) => {
      const openPreview = previewBtn.querySelectorAll("td.action i:nth-child(2)")[0];
      openPreview.addEventListener("click", () => {
        previewArticle.querySelector("h2").textContent = previewBtn.querySelectorAll("td")[2].textContent; 
        previewArticle.querySelector("img").src = previewBtn.querySelectorAll("td")[4].querySelector("img").src; 
        previewArticle.querySelector("p").innerHTML = previewBtn.querySelectorAll("td")[3].innerHTML;
        previewArticle.classList.add("active");
      });
  });
  document.addEventListener("dblclick", (e) => {
      tr.forEach(tr => {
          if (!tr.contains(e.target) && !previewArticle.querySelector("article").contains(e.target)) {
              previewArticle.classList.remove("active");
          }
      });
  });
  tr.forEach((previewBtn, index) => {
      const openPreview = previewBtn.querySelectorAll("td.action i:nth-child(2)")[0];
      openPreview.addEventListener("click", () => {
        previewArticle.querySelector("h2").textContent = previewBtn.querySelectorAll("td")[2].textContent; 
        previewArticle.querySelector("img").src = previewBtn.querySelectorAll("td")[4].querySelector("img").src; 
        previewArticle.querySelector("p").innerHTML = previewBtn.querySelectorAll("td")[3].innerHTML;
        previewArticle.classList.add("active");
      });
  });
  document.addEventListener("dblclick", (e) => {
      tr.forEach(tr => {
          if (!tr.contains(e.target) && !previewArticle.querySelector("article").contains(e.target)) {
              previewArticle.classList.remove("active");
          }
      });
  });
  const resizableDiv = previewArticle.querySelector("article");
  let isResizing = false;
  let originalWidth;
  let originalHeight;
  let startX;
  let startY;
  resizableDiv.addEventListener("mousedown", initResize);
  function initResize(e) {
      e.preventDefault();
      isResizing = true;
      originalWidth = resizableDiv.offsetWidth;
      originalHeight = resizableDiv.offsetHeight;
      startX = e.clientX;
      startY = e.clientY;
      document.addEventListener("mousemove", resize);
      document.addEventListener("mouseup", stopResize);
  }
  function resize(e) {
      if (!isResizing) return;
      const deltaX = e.clientX - startX;
      const deltaY = e.clientY - startY;
      resizableDiv.style.width = `${originalWidth + deltaX}px`;
      resizableDiv.style.height = `${originalHeight + deltaY}px`;
  }
  function stopResize() {
      isResizing = false;
      document.removeEventListener("mousemove", resize);
      document.removeEventListener("mouseup", stopResize);
  }
}

// default method for every table like searching and more
function searchTables(inpValue) {
    var input = inpValue.toLowerCase();
    var tables = document.getElementsByTagName('tbody');

    for (var i = 0; i < tables.length; i++) {
      var table = tables[i];
      var rows = table.getElementsByTagName('tr');

      for (var j = 0; j < rows.length; j++) {
        var row = rows[j];
        var rowData = row.textContent.toLowerCase();

        if (rowData.includes(input)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      }
    }
  }
  window.onload = () => {
    document.querySelector('#seachOnTable').focus();
  }

  let uploadTable = document.querySelectorAll(".uploadedImg td.action i.fa-edit");
  uploadTable.forEach((editBtn, index) => {
    let form = document.querySelectorAll(".uploadedImg td form")[index];
    editBtn.addEventListener("click", (e) => {
      form.classList.toggle("active");
      form.querySelectorAll('input')[1].focus();
    });
  });