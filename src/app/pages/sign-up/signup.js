$(document).ready(function () {
  $(".toast").each(function () {
    var toast = new bootstrap.Toast($(this));
    toast.show();
  });
});
