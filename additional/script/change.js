$(document).ready(function() {
  function changeNav(value){
    $.ajax({
      url: value, 
      type: 'POST',
      data: { getContent: value },
      success:function(response){
        $('html').html(response);
      }
    });
  }
  document.querySelectorAll('.navbar .nav-links:nth-child(2) a').forEach(navChange=>{
    navChange.addEventListener('click',(e)=>{
      let navName = navChange.getAttribute('href');
      e.preventDefault();
      changeNav(navName);
    })
  });
});
