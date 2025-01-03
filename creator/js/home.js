const darkModeButton = document.getElementById("nav-dark-mode");
const profileOptionsButton = document.getElementById("nav-profile-img");
const navInfoButton = document.getElementById("nav-info");
const profileOptionsImage = document.getElementById("profile-options-img");

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    darkModeButton.src = "../imgs/dark-mode-white.png";
    profileOptionsButton.src = "../imgs/default_profile_image_dark.svg";
    navInfoButton.src = "../imgs/info_button_dark.svg";
    profileOptionsImage.src = "../imgs/default_profile_image_dark.svg";
} else {
    darkModeButton.src = "../imgs/dark-mode-green.png";
    profileOptionsButton.src = "../imgs/default_profile_image_light.svg";
    navInfoButton.src = "../imgs/info_button_light.svg";
    profileOptionsImage.src = "../imgs/default_profile_image_light.svg";
}

darkModeButton.addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");
    
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        darkModeButton.src = "../imgs/dark-mode-white.png";
        profileOptionsButton.src = "../imgs/default_profile_image_dark.svg";
        navInfoButton.src = "../imgs/info_button_dark.svg";
        profileOptionsImage.src = "../imgs/default_profile_image_dark.svg";
    } else {
        localStorage.setItem("darkMode", "disabled");
        darkModeButton.src = "../imgs/dark-mode-green.png";
        profileOptionsButton.src = "../imgs/default_profile_image_light.svg";
        navInfoButton.src = "../imgs/info_button_light.svg";
        profileOptionsImage.src = "../imgs/default_profile_image_light.svg";
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const options = document.getElementById("profile-options");
    const img = document.getElementById("nav-profile-img");

    img.onclick = () => {
        if (!options.classList.contains("show")) {
            options.style.display = "flex";
            options.style.pointerEvents = "auto";
            options.offsetHeight;
        } else {
            options.style.pointerEvents = "none";
            setTimeout(() => {
                options.style.display = "none";
            }, 300);
        }
        
        options.classList.toggle("show");
    };

    window.onclick = (event) => {
        if (!img.contains(event.target) && !options.contains(event.target)) {
            options.classList.remove("show");
            options.style.pointerEvents = "none";
            setTimeout(() => {
                options.style.display = "none";
            }, 300);
        }
    };
});

const modal = document.getElementById("info-modal");
const openButton = document.getElementById("nav-info");
const closeButton = modal.querySelector(".close-button");

openButton.addEventListener("click", () => {
  modal.style.display = "flex";
  modal.classList.add("fade-in");

  setTimeout(() => {
      modal.classList.remove("fade-in");
  }, 300);
});

window.addEventListener("click", (event) => {
  if (event.target === modal) {
    modal.classList.add("fade-out");

    setTimeout(() => {
        modal.style.display = "none";
        modal.classList.remove("fade-out");
    }, 300);
  }
});