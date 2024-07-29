$(document).ready(function() {
  function getData(value){
    $.ajax({
      url: 'getdata', 
      type: 'POST',
      data: { getContent: value },
      success:function(response){
        $('#dataRelated').html(response);
      }
    });
  }
  let search = $("#searchPost");
  if(search){
     search.on("input", function() {
    var Value = $(this).val();
    getData(Value);
  });
  }

  function downloadD(){
    $.ajax({
      url: 'getdata', 
      type: 'POST',
      data: { donload : "What the ululu" },
      success:function(response){
        $('#downloadData').html(response);
      }
    });
  }
  if($('#downloadData')){
    $('#downloadData').on('click',function(){
      downloadD();
    })
  }
  // comments
  function comments(name, id, cmt) {
    if (name.trim() !== "" && cmt.trim() !== "") {
      $.ajax({
        url: 'change',
        type: 'POST',
        data: { id: id, name: name, comment: cmt },
        success: function (response) {
          $('.comment-view .comments').append(response);
          document.querySelector(".comment-view").classList.add("active");
        }
      });
    } else if (cmt.trim() !== "") {
      $.ajax({
        url: 'change',
        type: 'POST',
        data: { id: id, comment: cmt },
        success: function (response) {
          $('.comment-view .comments').append(response);
          document.querySelector(".comment-view").classList.add("active");
        }
      });
    }
  }
  
  let newComment = document.querySelector('.comment-section');
  if (newComment) {
    let commentForm = newComment.querySelector('form'),
        post = newComment.querySelector('#post'),
        cmtInput = newComment.querySelector('#reader'),
        cmtSec = newComment.querySelector('#comment');
  
    commentForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (cmtInput) {
        if (cmtInput.value.trim() !== '' && cmtSec.value.trim() !== '') {
          e.preventDefault();
          comments(cmtInput.value, post.value, cmtSec.value);
          cmtInput.value = "";
          cmtSec.value = "";
        }
      } else {
        if (cmtSec.value.trim() !== '') {
          e.preventDefault();
          comments("", post.value, cmtSec.value);
          cmtSec.value = "";
        }
      }
    });
  }  

  var actionBtn = document.getElementById('uploadFor');
  if(actionBtn){
    actionBtn.onclick=(e)=>{
      e.preventDefault();
      var fileInput = $("#imageInsert");
                var files = fileInput[0].files;
                if (files.length === 0) {
                    alert("Your image did not selected! select again.");
                    return;
                }
        var formData = new FormData($("#uploadForm")[0]);
        $.ajax({
            url: "change",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
              $('.images-container .uploaded-images .images').html(response);
              $("#imageInsert").val("");
              actionBtn.style.visibility = "visible";
            }
        });
        actionBtn.style.visibility = "hidden";
    }
  }
    // for likes
    function liker(value) {
      $.ajax({
        url: 'change',
        type: 'POST',
        data: { likes: value },
        success: function (response) {
        }
      });
    }
    const likes = document.querySelector('.reactIcon .fa-heart');
    if (likes) {
      likes.onclick = () => {
        let like = likes.getAttribute('data-like');
        liker(like);
        likes.onclick = null;
      };
    }
});
