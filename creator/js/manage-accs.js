document.querySelector("#profile-link").addEventListener("click", function(e) {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});

document.getElementById("show-pass-img").addEventListener("click", function() {
    const cupassInput = document.getElementById("cupass");
    const npassInput = document.getElementById("npass");
    const cnpassInput = document.getElementById("cnpass");
    const img = document.getElementById("show-pass-img");

    if (npassInput.type === "password") {
        cupassInput.type = "text";
        npassInput.type = "text";
        cnpassInput.type = "text";
        img.src = "../imgs/eye_open.svg";
    } else {
        cupassInput.type = "password";
        npassInput.type = "password";
        cnpassInput.type = "password";
        img.src = "../imgs/eye_close.svg";
    }
});