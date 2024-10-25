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

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    darkModeButton.src = "imgs/dark-mode-white.png";
} else {
    darkModeButton.src = "imgs/dark-mode-green.png";
}

darkModeButton.addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");
    
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        darkModeButton.src = "imgs/dark-mode-white.png";
    } else {
        localStorage.setItem("darkMode", "disabled");
        darkModeButton.src = "imgs/dark-mode-green.png";
    }
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

  const userInputCode = document.getElementById("verification-code").value;

  if (userInputCode === sentCode && userInputCode.length !== 0) {
    console.log("Verification successful!");
    document.getElementById("forgot-pass-error-msg").style.display = "none"
    document.getElementById("forgot-pass-error-msg").textContent = ""
    replaceModalContent();
  } else {
    document.getElementById("forgot-pass-error-msg").style.display = "block"
    document.getElementById("forgot-pass-error-msg").textContent = "Verification failed. The codes do not match."
  }
});

function replaceModalContent() {
    const modalContent = document.getElementById("forgot-pass-modal-content");
    modalContent.innerHTML = `
      <h2 id="change-pass-modal-title">Forgot Password</h2>
      <form id="change-pass-form" action="" method="post">
          <label for="change-pass-password" class="change-pass-modal-label">New Password</label>
          <input type="password" id="change-password" name="password" placeholder="Enter your new password" onkeyup='check();' required>
          <label for="confirm-password" class="change-pass-modal-label">Confirm New Password <span id="change-message"></span></label>
          <input type="password" id="confirm-change-password" name="confirm-password" placeholder="Confirm your new password" onkeyup='check();' required>
          <button type="submit" name="change" class="forgot-pass-btns">Change Password</button><br>
          <a href="" class="change-pass-modal-links" id="change-pass-in-btn">Sign In</a>
      </form>
    `;
    const changePassInBtn2 = document.getElementById("change-pass-in-btn");
    changePassInBtn2.addEventListener("click", function(event) {
        event.preventDefault();
        hideModal(changePassModal);
    
        setTimeout(() => {
            showModal(modal);
        }, 300);
    });
}

function generateCode() {
  return Math.floor(100000 + Math.random() * 900000).toString();
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}