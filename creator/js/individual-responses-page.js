const homeButton = document.getElementById("nav-home-btn");
const darkModeButton = document.getElementById("nav-dark-mode");
const profileOptionsButton = document.getElementById("nav-profile-img");
const navInfoButton = document.getElementById("nav-info");
const profileOptionsImage = document.getElementById("profile-options-img");

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    homeButton.src = "../imgs/home_dark.svg";
    darkModeButton.src = "../imgs/dark-mode-white.png";
    profileOptionsButton.src = "../imgs/default_profile_image_dark.svg";
    navInfoButton.src = "../imgs/info_button_dark.svg";
    profileOptionsImage.src = "../imgs/default_profile_image_dark.svg";
} else {
    homeButton.src = "../imgs/home.svg";
    darkModeButton.src = "../imgs/dark-mode-green.png";
    profileOptionsButton.src = "../imgs/default_profile_image_light.svg";
    navInfoButton.src = "../imgs/info_button_light.svg";
    profileOptionsImage.src = "../imgs/default_profile_image_light.svg";
}

darkModeButton.addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");
    
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        homeButton.src = "../imgs/home_dark.svg";
        darkModeButton.src = "../imgs/dark-mode-white.png";
        profileOptionsButton.src = "../imgs/default_profile_image_dark.svg";
        navInfoButton.src = "../imgs/info_button_dark.svg";
        profileOptionsImage.src = "../imgs/default_profile_image_dark.svg";
    } else {
        localStorage.setItem("darkMode", "disabled");
        homeButton.src = "../imgs/home.svg";
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
    document.getElementById('toggleSwitch').addEventListener('change', function() {
        // Get the form
        var form = document.getElementById('surveyForm');
    
        // Determine the new status based on the switch state
        var newStatus = this.checked ? 'ACCEPTING' : 'NOT ACCEPTING';
    
        // Create a hidden input to store the status
        var statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);
    
        // Submit the form
        form.submit();
    });
});

