$(document).ready(function () {
  $("#LoginForm").submit(function (e) {
    e.preventDefault();

    $("#login-error").addClass("d-none");

    let email = $("#email").val();
    let password = $("#password").val();

    $.ajax({
      type: "POST",
      url: "backend/api/web/auth.php",
      data: { requestType: "Login", email, password },
      success: function (response) {
        // FIX 1: Handle response if it's already an object (auto-parsed by jQuery)
        let res = typeof response === "string" ? JSON.parse(response) : response;

        console.log(res);

        if (res.status == "error") {
          $("#login-error").removeClass("d-none");
        } else {
          // FIX 2: Explicitly go to home page instead of just reloading
          window.location.href = "pages/home.php";
        }
      },
    });
  });

  $("#ForgotPasswordForm").submit(function (e) {
    e.preventDefault();

    $("#forgot-password-error").addClass("d-none");

    let email = $("#email").val();

    $.ajax({
      type: "POST",
      url: "backend/api/web/auth.php",
      data: { requestType: "SendOTP", email },
      success: function (response) {
        let res = typeof response === "string" ? JSON.parse(response) : response;

        if (res.status == "error") {
          $("#forgot-password-error").removeClass("d-none");
        } else {
          window.location.href = "login-using-otp.php?email=" + email;
        }
      },
    });
  });

  $("#LoginUsingOtpForm").submit(function (e) {
    e.preventDefault();

    $("#login-using-otp-error").addClass("d-none");

    let email = $("#email").val();
    let otp = $("#otp").val();

    $.ajax({
      type: "POST",
      url: "backend/api/web/auth.php",
      data: { requestType: "LoginUsingOtp", email, otp },
      success: function (response) {
        let res = typeof response === "string" ? JSON.parse(response) : response;

        if (res.status == "error") {
          $("#login-using-otp-error").removeClass("d-none");
        } else {
          // FIX 3: Explicit redirect here too
          window.location.href = "pages/home.php";
        }
      },
    });
  });
});