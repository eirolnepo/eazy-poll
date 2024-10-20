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