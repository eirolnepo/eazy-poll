const darkModeButton = document.getElementById('nav-dark-mode');
const profileOptionsButton = document.getElementById('nav-profile-img');
const navInfoButton = document.getElementById('nav-info');
const profileOptionsImage = document.getElementById('profile-options-img');

if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
    darkModeButton.src = '../imgs/dark-mode-white.png';
    profileOptionsButton.src = "../imgs/default_profile_image_dark.svg";
    navInfoButton.src = "../imgs/info_button_dark.svg";
    profileOptionsImage.src = "../imgs/default_profile_image_dark.svg";
} else {
    darkModeButton.src = '../imgs/dark-mode-green.png';
    profileOptionsButton.src = "../imgs/default_profile_image_light.svg";
    navInfoButton.src = "../imgs/info_button_light.svg";
    profileOptionsImage.src = "../imgs/default_profile_image_light.svg";
}

darkModeButton.addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    
    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
        darkModeButton.src = '../imgs/dark-mode-white.png';
        profileOptionsButton.src = "../imgs/default_profile_image_dark.svg";
        navInfoButton.src = "../imgs/info_button_dark.svg";
        profileOptionsImage.src = "../imgs/default_profile_image_dark.svg";
    } else {
        localStorage.setItem('darkMode', 'disabled');
        darkModeButton.src = '../imgs/dark-mode-green.png';
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
            options.offsetHeight;
        }
        
        options.classList.toggle("show");
    };

    window.onclick = (event) => {
        if (!img.contains(event.target) && !options.contains(event.target)) {
            options.classList.remove("show");
            setTimeout(() => {
                options.style.display = "none";
            }, 300);
        }
    };
});