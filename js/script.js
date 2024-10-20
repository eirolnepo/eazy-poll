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