const modal = document.getElementById("sign-in-modal");
const signInBtn = document.getElementById("sign-in-btn");
const signUpModal = document.getElementById("sign-up-modal");
const signUpBtn = document.getElementById("sign-up-btn");
const signInUpBtn = document.getElementById("sign-in-up-btn");
const signUpInBtn = document.getElementById("sign-up-in-btn");
const changePassModal = document.getElementById("change-pass-modal");
const forgotPassLink = document.getElementById("forgot-pass-link");
const changePassInBtn = document.getElementById("change-pass-in-btn");
const darkModeButton = document.getElementById("nav-dark-mode");
const passwordVisible = localStorage.getItem("passwordVisible") === "true";
const showPassImage = document.getElementById("show-pass-img");
const showSignUpPassImage = document.getElementById("show-signup-pass-img");
const signInPass = document.getElementById("password");
const signUpPass = document.getElementById("sign-up-password");
const confirmSignUpPass = document.getElementById("sign-up-confirm-password");

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    darkModeButton.src = "imgs/dark-mode-white.png";
} else {
    darkModeButton.src = "imgs/dark-mode-green.png";
}

signInPass.type = "password";
signUpPass.type = "password";
confirmSignUpPass.type = "password";

function updateEyeImage(inputField, eyeImage) {
    const isDarkMode = document.body.classList.contains("dark-mode");
    const isVisible = inputField.type === "text";

    eyeImage.src = isDarkMode
        ? (isVisible ? "imgs/eye_open_dark.svg" : "imgs/eye_close_dark.svg")
        : (isVisible ? "imgs/eye_open_light.svg" : "imgs/eye_close_light.svg");
}

updateEyeImage(signInPass, showPassImage);
updateEyeImage(signUpPass, showSignUpPassImage);

darkModeButton.addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        darkModeButton.src = "imgs/dark-mode-white.png";
    } else {
        localStorage.setItem("darkMode", "disabled");
        darkModeButton.src = "imgs/dark-mode-green.png";
    }

    updateEyeImage(signInPass, showPassImage);
    updateEyeImage(signUpPass, showSignUpPassImage);
    updateEyeImage(confirmSignUpPass, showConfirmPassImage);
});

function togglePasswordVisibility(inputField, eyeImage) {
    const isCurrentlyVisible = inputField.type === "text";
    inputField.type = isCurrentlyVisible ? "password" : "text";

    updateEyeImage(inputField, eyeImage);

    localStorage.setItem("passwordVisible", inputField.type === "text" ? "true" : "false");
}

showPassImage.addEventListener("click", function () {
    togglePasswordVisibility(signInPass, showPassImage);
});

showSignUpPassImage.addEventListener("click", function () {
    togglePasswordVisibility(signUpPass, showSignUpPassImage);
    togglePasswordVisibility(confirmSignUpPass, showSignUpPassImage);
});

function showModal(modal) {
    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";
    modal.classList.add("fade-in");

    setTimeout(() => {
        modal.classList.remove("fade-in");
    }, 300);
}

function hideModal(modal) {
    modal.classList.add("fade-out");

    setTimeout(() => {
        modal.style.display = "none";
        modal.classList.remove("fade-out");
    }, 300);
}

signInBtn.addEventListener("click", function() {
    showModal(modal);
});

window.addEventListener("click", function(event) {
    if (event.target == modal) {
        hideModal(modal);
    }
});

signUpBtn.addEventListener("click", function() {
    showModal(signUpModal);
});

window.addEventListener("click", function(event) {
    if (event.target == signUpModal) {
        hideModal(signUpModal);
    }
});

signInUpBtn.addEventListener("click", function(event) {
    event.preventDefault();
    hideModal(modal);

    setTimeout(() => {
        showModal(signUpModal);
    }, 300);
});

signUpInBtn.addEventListener("click", function(event) {
    event.preventDefault();
    hideModal(signUpModal);

    setTimeout(() => {
        showModal(modal);
    }, 300);
});

forgotPassLink.addEventListener("click", function(event) {
    event.preventDefault();
    hideModal(modal);

    setTimeout(() => {
        showModal(changePassModal);
    }, 300);
});

window.addEventListener("click", function(event) {
    if (event.target == changePassModal) {
        hideModal(changePassModal);
    }
});

changePassInBtn.addEventListener("click", function(event) {
    event.preventDefault();
    hideModal(changePassModal);

    setTimeout(() => {
        showModal(modal);
    }, 300);
});

var check = function() {
    if(document.getElementById("sign-up-password").value == "" &&
      document.getElementById("sign-up-confirm-password").value == ""){
      document.getElementById("sign-up-message").innerHTML = "";
    }
    else if (document.getElementById("sign-up-password").value ==
      document.getElementById("sign-up-confirm-password").value) {
      document.getElementById("sign-up-message").style.color = "green";
      document.getElementById("sign-up-message").innerHTML = "| Matching";
    } else {
      document.getElementById("sign-up-message").style.color = "red";
      document.getElementById("sign-up-message").innerHTML = "| Not matching";
    }

    if(document.getElementById("change-password").value == "" &&
      document.getElementById("confirm-change-password").value == ""){
      document.getElementById("change-message").innerHTML = "";
    }
    else if (document.getElementById("change-password").value ==
      document.getElementById("confirm-change-password").value) {
      document.getElementById("change-message").style.color = "green";
      document.getElementById("change-message").innerHTML = "| Matching";
    } else {
      document.getElementById("change-message").style.color = "red";
      document.getElementById("change-message").innerHTML = "| Not matching";
    }
}

emailjs.init("U0z2EKZx9cUmVo5Tt");

let sentCode = "";

document.getElementById("get-code-btn").addEventListener("click", function(event) {
  event.preventDefault();
  const email = document.getElementById("change-pass-email").value;

  if (validateEmail(email) && email.length !== 0) {
    document.getElementById("forgot-pass-email-error-msg").style.display = "none"
    document.getElementById("forgot-pass-email-error-msg").textContent = ""

    sentCode = generateCode();

    emailjs.send("service_117moyx", "template_y4nsav5", {
      to_email: email,
      code: sentCode,
    }).then(
      function(response) {
        document.getElementById("get-code-btn").disabled = true;
        document.getElementById("get-code-btn").style.backgroundColor = "#006156";
        document.getElementById("get-code-btn").style.cursor = "not-allowed";
        document.getElementById("forgot-pass-email-error-msg").style.display = "block"
        document.getElementById("forgot-pass-email-error-msg").style.color = "green"
        document.getElementById("forgot-pass-email-error-msg").textContent = "Email sent successfully!"
        console.log("Email sent successfully!", response.status, response.text);
      },
      function(error) {
        console.log("Failed to send email:", error);
      }
    );
  } else {
    document.getElementById("forgot-pass-email-error-msg").style.display = "block"
    document.getElementById("forgot-pass-email-error-msg").textContent = "Please enter a valid email address."
  }
});

document.getElementById("verify-code").addEventListener("click", function(event) {
  event.preventDefault();
  const email = document.getElementById("change-pass-email").value;
  const userInputCode = document.getElementById("verification-code").value;

  if (userInputCode === sentCode && userInputCode.length !== 0) {
    console.log("Verification successful!");
    document.getElementById("forgot-pass-error-msg").style.display = "none"
    document.getElementById("forgot-pass-error-msg").textContent = ""
    console.log("Email to be sent:", email);
    replaceModalContent(email);
  } else {
    document.getElementById("forgot-pass-error-msg").style.display = "block"
    document.getElementById("forgot-pass-error-msg").textContent = "Verification failed. The codes do not match."
  }
});

function replaceModalContent(email) {
  const modalContent = document.getElementById("forgot-pass-modal-content");
  modalContent.innerHTML = `
    <h2 id="change-pass-modal-title">Forgot Password</h2>
    <form id="change-pass-form" action="" method="post">
      <input type="hidden" name="email" value="${email}">
      <label for="change-pass-password" class="change-pass-modal-label">New Password</label>
      <div class="pass-container">
          <input type="password" id="change-password" name="password" placeholder="Enter your new password" onkeyup='check();' required>
          <img src="imgs/eye_close_light.svg" alt="" id="show-forgot-pass-img">
      </div>
      <label for="confirm-password" class="change-pass-modal-label">Confirm New Password <span id="change-message"></span></label>
      <input type="password" id="confirm-change-password" name="confirm-password" placeholder="Confirm your new password" onkeyup='check();' required>
      <span id="password-error-msg" style="color: red; display: none;"></span>
      <button type="button" name="change" id="change-password-btn" class="forgot-pass-btns">Change Password</button><br>
      <a href="" class="change-pass-modal-links" id="change-pass-in-btn">Sign In</a>
    </form>
  `;

  document.getElementById("change-password-btn").addEventListener("click", function(event) {
    event.preventDefault();

    const password = document.getElementById("change-password").value;
    const confirmPassword = document.getElementById("confirm-change-password").value;
    const errorMessage = document.getElementById("password-error-msg");
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if (password !== confirmPassword) {
      errorMessage.textContent = "Passwords do not match.";
      errorMessage.style.display = "block";
    } else if (!passwordRegex.test(password)) {
      errorMessage.textContent = "Password must be at least 8 characters long and include at least one uppercase letter and one number.";
      errorMessage.style.display = "block";
    } else {
      errorMessage.style.display = "none";
      console.log("Password change successful!");
    }
  });

  const changePassInBtn2 = document.getElementById("change-pass-in-btn");
  changePassInBtn2.addEventListener("click", function(event) {
      event.preventDefault();
      hideModal(changePassModal);
  
      setTimeout(() => {
          showModal(modal);
      }, 300);
  });

  const changePasswordInput = document.getElementById("change-password");
  const confirmChangePasswordInput = document.getElementById("confirm-change-password");
  const showForgotPassImage = document.getElementById("show-forgot-pass-img");

  changePasswordInput.type = "password";
  confirmChangePasswordInput.type = "password";

  updateEyeImage(changePasswordInput, showForgotPassImage);
  
  showForgotPassImage.addEventListener("click", function () {
      togglePasswordVisibility(changePasswordInput, showForgotPassImage);
      togglePasswordVisibility(confirmChangePasswordInput, showForgotPassImage);
  });
}

function generateCode() {
  return Math.floor(100000 + Math.random() * 900000).toString();
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}