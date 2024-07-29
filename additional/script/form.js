// scripts for profileUpdator------------------------------------------------------
if(document.querySelector('#profileUpdate')){
    const newImg = document.querySelector("#newImg"),
    imgBtn = document.querySelector("picture.form-group button");
    if(imgBtn){
         imgBtn.addEventListener("click",(e)=>{
      e.preventDefault();
      newImg.click();
    });
    newImg.addEventListener("change", () => {
        let srcImg =  document.querySelector("#prevImage").getAttribute("src");
      const file =newImg.files[0];
      const reader = new FileReader();
      reader.onload = () => {
        document.querySelector("#prevImage").setAttribute("src",`${reader.result}`);
      };
      if (file) {
        reader.readAsDataURL(file);
      } else {
        document.querySelector("#prevImage").setAttribute("src",`${srcImg}`);
      }
     });
    }
}
// script for post edition---------------------------------------------------------
if(document.querySelector('#postMaker')){
    const sidebarItems = document.querySelectorAll(".sidebar ul.navbar li");
    sidebarItems.forEach(item => {
      item.classList.remove("active");
      sidebarItems[6].classList.add("active");
    });
  
    // select feature image for post
    const featureImg = document.querySelector("#featureImg"),
      featureImgBtn = document.querySelector("#featureImgBtn"),
      buttons = document.querySelectorAll("button");
    const featureImgDisplay = document.querySelector(".feature-image picture img");
  
    featureImgBtn.addEventListener("click", (e) => {
      e.preventDefault();
      featureImg.click();
    });
    document.querySelector(".feature-image").style.display = "none";
    featureImg.addEventListener("change", (event) => {
      const file = event.target.files[0];
      if (file) {
        document.querySelector(".feature-image").style.display = "block";
        const reader = new FileReader();
        reader.onload = function () {
          featureImgDisplay.src = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        document.querySelector(".feature-image").style.display = "none";
      }
    });
    
  
    // upload all images in database for future use
    buttons.forEach(Btn => {
      if (Btn.getAttribute("type") !== "submit") {
        Btn.onclick = (e) => {
          e.preventDefault();
        }
      }
    });
    
    const dropzone = document.querySelector(".images-container .image-drop .drop-here"),
    imageInsert = document.getElementById("imageInsert"),
    imageInsertBtn = document.querySelector(".images-container .image-drop button"),
    linkImg = document.querySelector("#linkImg"),
    updateSection = document.querySelector(".images-holder");
  
    linkImgBtn.addEventListener("click", (e) => {
        updateSection.classList.add("active");
      });
      document.querySelector("#hideIt").addEventListener("click",()=>{
        updateSection.classList.remove("active");
      })
    imageInsertBtn.addEventListener("click",(e)=>{
      imageInsert.click();
    });
    dropzone.addEventListener("dragenter", (event) => {
    event.preventDefault();
    dropzone.classList.add("dragover");
  });
  dropzone.addEventListener("dragover", (event) => {
    event.preventDefault();
  });
  dropzone.addEventListener("dragleave", () => {
    dropzone.classList.remove("dragover");
  });
  dropzone.addEventListener("drop", (event) => {
    event.preventDefault();
    dropzone.classList.remove("dragover");
    const files = event.dataTransfer.files;
    const reader = new FileReader();
    const newFileList = new DataTransfer();
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.type.match("image.*")) {
        reader.onload = function (e) {
          const droppedImage = document.createElement("img");
          droppedImage.src = e.target.result;
          droppedImage.classList.add("dropped-image");
          dropzone.appendChild(droppedImage);
          newFileList.items.add(file);
          addDroppedFiles(newFileList.files);
        };
        reader.readAsDataURL(file);
      }
    }
  });
  
  imageInsert.addEventListener("change", (event) => {
    const selectedFiles = event.target.files;
    function addDroppedFiles(files) {
        imageInsert.files = files;
      }
  });
  
  function addDroppedFiles(files) {
    const existingFiles = imageInsert.files;
    const newFiles = new DataTransfer();
  
    for (let i = 0; i < existingFiles.length; i++) {
      newFiles.items.add(existingFiles[i]);
    }
  
    for (let i = 0; i < files.length; i++) {
      newFiles.items.add(files[i]);
    }
  
    imageInsert.files = newFiles.files;
  }
  
//   script base on selecting images for post
  let imagesSelect = document.querySelectorAll(".images-container .uploaded-images .images img");
  let imageDisp = document.querySelector(".imgLinks .select");
  let hostname = window.location.hostname;
  let selectedImages = new Set();
  
  imagesSelect.forEach((image) => {
    image.addEventListener("click", (e) => {
      image.classList.toggle("active");
      if (selectedImages.has(image.src)) {
        selectedImages.delete(image.src);
        let selectedImage = imageDisp.querySelector(`[src="${image.src}"]`);
        if (selectedImage) {
          selectedImage.remove();
        }
      } else {
        selectedImages.add(image.src);
        let selectImage = document.createElement("img");
        selectImage.src = image.src;
        selectImage.className = "selectedImg";
        imageDisp.appendChild(selectImage);
  
        selectImage.addEventListener("click", () => {
          var copy = selectImage.getAttribute("src");
          navigator.clipboard.writeText(copy);
          selectImage.title = copy;
        });
      }
    });
  });
  
//   script to manage slug for post
  document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll("input");
    inputs[0].focus();
  
    function generateSlug(title) {
      let escapedTitle = title.replace(/\'/g, "");
      let slug = escapedTitle.toLowerCase().replace(/[:.,\\`"/!~<>?|=-_#$%^*(){}[\]]/g, "").replace(/\s+/g, "-");
      inputs[1].value = slug;
    }
  
    inputs[1].addEventListener("input", function() {
      generateSlug(inputs[1].value);
    });
  
    inputs[0].addEventListener("input", function() {
      generateSlug(inputs[0].value);
    });
  });
  function checkFile(event) {
    let oldImg = document.querySelector("#old_img");
    if (!oldImg) {
      if (featureImg.files.length > 0) {} else {
        alert("Feature image can`t be empty!");
        event.preventDefault();
      }
    } else {
      if (featureImg.files.length > 0 || oldImg.value !== "") {
  
      } else {
        alert("Feature image can`t be empty!.");
        event.preventDefault();
      }}}
}
// script for validation
var selectInteracted = {};
function validateForm() {
    var isValid = true;
    document.querySelectorAll('select').forEach(select => {
        var selectedOption = select.value;
        if (!selectInteracted[select.id]) {
            select.setCustomValidity("Please select an option.");
            isValid = false;
        } else {
            select.setCustomValidity("");
        }
    });
    
    return isValid;
}

document.querySelectorAll('select').forEach(select => {
    select.addEventListener("input", function() {
        selectInteracted[select.id] = true;
        this.setCustomValidity("");
    });
});
// navigation modification script--------------------------------------------------------
if(document.querySelector('#navigation')){
    const sidebarItems = document.querySelectorAll(".sidebar li");
    sidebarItems.forEach(item => {
      item.classList.remove("active");
      sidebarItems[7].classList.add("active");
    });
   document.querySelectorAll(".sidebar ul li.management ul li")[0].classList.add("active");
}

// default script for all page
  const floatBtn = document.querySelector('.submission');
  let forms = document.querySelectorAll('.submission input');

  if (floatBtn) {
    document.addEventListener('keydown', (event) => {
      if (event.ctrlKey && 's'.indexOf(event.key) !== -1) {
        event.preventDefault();
        floatBtn.classList.add('float');
      }
      if (event.key === "Enter") {
        event.preventDefault();
        forms[0].click();
      }
    });
    document.addEventListener('click', (e) => {
      let buttons = document.querySelectorAll('.submission input');
      buttons.forEach(buton => {
        if (!floatBtn.contains(e.target) && !buton.contains(e.target)) {
          if (floatBtn.classList.contains('float')) {
            floatBtn.classList.remove('float');
          }
        }
      });
    });
  }