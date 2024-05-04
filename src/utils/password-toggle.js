$(document).ready(function () {
  $("#togglePassword").click(function () {
    // Get the password input field
    var password = $("#password");

    // Check the current type and switch it
    var currentType = password.attr("type");
    var newType = currentType === "password" ? "text" : "password";
    password.attr("type", newType);

    // Toggle the eye icon classes
    $(this).toggleClass("bi-eye bi-eye-slash");
  });
});
