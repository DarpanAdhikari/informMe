$(document).ready(function () {
  function offline(value) {
    $.ajax({
      url: 'change',
      type: 'POST',
      data: { change: value },
      success: function (response) {

      }
    });
  }
  // set online offline
  const introPicture = document.querySelector('#profile picture');
  if (introPicture) {
    function updateOnlineStatus() {
      if (navigator.onLine) {
        offline(1);
        introPicture.classList.add('online');
      } else {
        offline(0);
        introPicture.classList.remove('online');
      }
    }
    updateOnlineStatus();
    setInterval(updateOnlineStatus, 5000);
    window.addEventListener('beforeunload', (e) => {
      offline(0);
    });
  }
// check online
  function checkOnline(){
    $.ajax({
      url: 'getdata', 
      type: 'POST',
      data: { checkStatus: "checkStatus" },
      success:function(response){
        $('#team').html(response);
      }
    });
  }
  if($('#team')){
    checkOnline()
    setInterval(checkOnline,5000);
  }
});