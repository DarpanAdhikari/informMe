$(document).ready(function() {
  function searchData(searchValue) {
    $.ajax({
      url: "search",
      type: "POST",
      data: { search: searchValue },
      success: function(response) {
        $(".searchList").empty();
        $(".searchList").html(response);
      }
    });
  }
  $("#searchInput").on("input", function() {
    var searchValue = $(this).val();
    searchData(searchValue);
  });
});

