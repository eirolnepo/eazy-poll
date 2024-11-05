const darkModeButton = document.getElementById("nav-dark-mode");
const navInfoButton = document.getElementById("nav-info");
const profileImage = document.getElementById("profile-img");
const showPassImage = document.getElementById("show-pass-img");
const passwordVisible = localStorage.getItem("passwordVisible") === "true";
const npassInput = document.getElementById("npass");
const cnpassInput = document.getElementById("cnpass");

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    darkModeButton.src = "../imgs/dark-mode-white.png";
    navInfoButton.src = "../imgs/info_button_dark.svg";
    profileImage.src = "../imgs/default_profile_image_dark.svg";
} else {
    darkModeButton.src = "../imgs/dark-mode-green.png";
    navInfoButton.src = "../imgs/info_button_light.svg";
    profileImage.src = "../imgs/default_profile_image_light.svg";
}

darkModeButton.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        darkModeButton.src = "../imgs/dark-mode-white.png";
        navInfoButton.src = "../imgs/info_button_dark.svg";
        profileImage.src = "../imgs/default_profile_image_dark.svg";
    } else {
        localStorage.setItem("darkMode", "disabled");
        darkModeButton.src = "../imgs/dark-mode-green.png";
        navInfoButton.src = "../imgs/info_button_light.svg";
        profileImage.src = "../imgs/default_profile_image_light.svg";
    }

    if (npassInput.type === "text") {
        showPassImage.src = document.body.classList.contains("dark-mode")
            ? "../imgs/eye_open_dark.svg"
            : "../imgs/eye_open_light.svg";
    } else {
        showPassImage.src = document.body.classList.contains("dark-mode")
            ? "../imgs/eye_close_dark.svg"
            : "../imgs/eye_close_light.svg";
    }
});

if (passwordVisible) {
    npassInput.type = "text";
    cnpassInput.type = "text";
    showPassImage.src = document.body.classList.contains("dark-mode")
        ? "../imgs/eye_open_dark.svg"
        : "../imgs/eye_open_light.svg";
} else {
    npassInput.type = "password";
    cnpassInput.type = "password";
    showPassImage.src = document.body.classList.contains("dark-mode")
        ? "../imgs/eye_close_dark.svg"
        : "../imgs/eye_close_light.svg";
}

showPassImage.addEventListener("click", function () {
    const img = showPassImage;

    if (npassInput.type === "password") {
        npassInput.type = "text";
        cnpassInput.type = "text";

        img.src = document.body.classList.contains("dark-mode")
            ? "../imgs/eye_open_dark.svg"
            : "../imgs/eye_open_light.svg";

        localStorage.setItem("passwordVisible", "true");
    } else {
        npassInput.type = "password";
        cnpassInput.type = "password";

        img.src = document.body.classList.contains("dark-mode")
            ? "../imgs/eye_close_dark.svg"
            : "../imgs/eye_close_light.svg";

        localStorage.setItem("passwordVisible", "false");
    }
});

document.querySelector("#profile-link").addEventListener("click", function (e) {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});

var check = function() {
    if(document.getElementById("npass").value == "" &&
      document.getElementById("cnpass").value == ""){
      document.getElementById("matched-message").innerHTML = "";
    }
    else if (document.getElementById("npass").value ==
      document.getElementById("cnpass").value) {
      document.getElementById("matched-message").style.color = "green";
      document.getElementById("matched-message").innerHTML = "Matching";
    } else {
      document.getElementById("matched-message").style.color = "red";
      document.getElementById("matched-message").innerHTML = "Not matching";
    }
}

const infoModal = document.getElementById("info-modal");
const openButton = document.getElementById("nav-info");
const closeButton = infoModal.querySelector(".close-button");

openButton.addEventListener("click", () => {
    infoModal.style.display = "flex";
    infoModal.classList.add("fade-in");

  setTimeout(() => {
    infoModal.classList.remove("fade-in");
  }, 300);
});

window.addEventListener("click", (event) => {
  if (event.target === infoModal) {
    infoModal.classList.add("fade-out");

    setTimeout(() => {
        infoModal.style.display = "none";
        infoModal.classList.remove("fade-out");
    }, 300);
  }
});

const deleteBtn = document.getElementById("account-delete-btn");
const modal = document.getElementById("deleteModal");

function showModal(modal) {
    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";
    modal.classList.add("fade-in");
    document.body.style.overflow = "hidden";

    setTimeout(() => {
        modal.classList.remove("fade-in");
    }, 300);
}

function hideModal(modal) {
    modal.classList.add("fade-out");

    setTimeout(() => {
        modal.style.display = "none";
        modal.classList.remove("fade-out");
        document.body.style.overflow = "auto";
    }, 300);
}

deleteBtn.onclick = function (event) {
    event.preventDefault();
    showModal(modal);
};

window.onclick = function (event) {
    if (event.target === modal) {
        hideModal(modal);
    }
};

function triggerFileInput() {
    document.getElementById("image-input").click();
}

document.getElementById("profile-img-container").addEventListener("click", triggerFileInput);
document.getElementById("change-img-btn").addEventListener("click", triggerFileInput);

document.getElementById("image-input").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("profile-img").src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});