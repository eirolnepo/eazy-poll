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

const surveyInput = document.getElementById("nav-survey-title");
const navTitleInput = document.getElementById("nav-title");
let typingTimeout;

surveyInput.addEventListener("input", function() {
    navTitleInput.value = surveyInput.value || "Untitled Survey";
    navTitleInput.scrollLeft = navTitleInput.scrollWidth;
});

surveyInput.addEventListener("blur", function() {
    navTitleInput.scrollLeft = 0;
});

const textareas = document.getElementsByClassName("survey-desc");

for (let i = 0; i < textareas.length; i++) {
    textareas[i].style.resize = "none";

    textareas[i].addEventListener("input", function() {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const darkModeButton = document.getElementById("nav-dark-mode");
    const profileOptionsButton = document.getElementById("nav-profile-img");
    const navInfoButton = document.getElementById("nav-info");
    const profileOptionsImage = document.getElementById("profile-options-img");
    const homeImage = document.getElementById("nav-home-btn");
    const addQuestionBtn = document.querySelector("#add-question-btn");
    const surveyContainer = document.querySelector("#survey-container");
    const addOptionsBtn = document.querySelector("#add-options-btn");
    const addOptionsContainer = document.querySelector("#add-options-container");
    const addTdBtn = document.querySelector("#add-td-btn");
    const addImageBtn = document.querySelector("#add-image-btn");
    const addSaveImage = document.getElementById("add-save-btn");

    let questionCounter = 1;

    addOptionsContainer.style.display = "none";
    addOptionsContainer.style.opacity = 0;
    addOptionsContainer.style.transform = "scale(0.9)";

    function updateImagesForDarkMode() {
        const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";
        const addChoiceBtns = document.getElementsByClassName("add-choice-btn");
        const deleteChoiceBtns = document.getElementsByClassName("delete-choice-btn");
        const deleteSectionBtns = document.getElementsByClassName("delete-section-btn");
        const deleteQuestionBtns = document.getElementsByClassName("delete-question-btn");
        const deleteUploadBtns = document.getElementsByClassName("delete-upload-btn");
        const uploadImageBtns = document.getElementsByClassName("upload-image-btn");

        darkModeButton.src = darkModeEnabled ? "../imgs/dark-mode-white.png" : "../imgs/dark-mode-green.png";
        profileOptionsButton.src = darkModeEnabled ? "../imgs/default_profile_image_dark.svg" : "../imgs/default_profile_image_light.svg";
        navInfoButton.src = darkModeEnabled ? "../imgs/info_button_dark.svg" : "../imgs/info_button_light.svg";
        profileOptionsImage.src = darkModeEnabled ? "../imgs/default_profile_image_dark.svg" : "../imgs/default_profile_image_light.svg";
        homeImage.src = darkModeEnabled ? "../imgs/home_dark.svg" : "../imgs/home.svg";
        addQuestionBtn.src = darkModeEnabled ? "../imgs/plus_choices_dark.svg" : "../imgs/plus_choices.svg";
        addTdBtn.src = darkModeEnabled ? "../imgs/text_dark.svg" : "../imgs/text_logo.svg";
        addImageBtn.src = darkModeEnabled ? "../imgs/image_dark.svg" : "../imgs/image_logo.svg";
        addSaveImage.src = darkModeEnabled ? "../imgs/save_dark.svg" : "../imgs/save.svg";

        const addChoiceSrc = darkModeEnabled ? "../imgs/plus_choices_dark.svg" : "../imgs/plus_choices.svg";
        const deleteChoiceSrc = darkModeEnabled ? "../imgs/close_dark.svg" : "../imgs/close.svg";
        const deleteSrc = darkModeEnabled ? "../imgs/delete_dark.svg" : "../imgs/delete.svg";
        const uploadSrc = darkModeEnabled ? "../imgs/upload_dark.svg" : "../imgs/upload.svg";

        for (let img of addChoiceBtns) img.src = addChoiceSrc;
        for (let img of deleteChoiceBtns) img.src = deleteChoiceSrc;
        for (let img of deleteSectionBtns) img.src = deleteSrc;
        for (let img of deleteQuestionBtns) img.src = deleteSrc;
        for (let img of deleteUploadBtns) img.src = deleteSrc;
        for (let img of uploadImageBtns) img.src = uploadSrc;
    }

    if (localStorage.getItem("darkMode") === "enabled") {
        document.body.classList.add("dark-mode");
        updateImagesForDarkMode();
    }

    darkModeButton.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {
            localStorage.setItem("darkMode", "enabled");
        } else {
            localStorage.setItem("darkMode", "disabled");
        }
        updateImagesForDarkMode();
    });

    function handleChoices(questionDiv) {
        const choicesContainer = questionDiv.querySelector(".question-choices-container");
        const addChoiceBtn = questionDiv.querySelector(".add-choice-btn");

        function addChoice() {
            const dropdown = document.createElement("select");
            dropdown.classList.add("dropdown-choices");

            const trueOption = document.createElement("option");
            trueOption.value = "True";
            trueOption.text = "True";

            const falseOption = document.createElement("option");
            falseOption.value = "False";
            falseOption.text = "False";

            dropdown.appendChild(trueOption);
            dropdown.appendChild(falseOption);

            choicesContainer.appendChild(dropdown);
        }

        addChoice();

        addChoiceBtn.style.display = "none";

        const deleteQuestionBtn = questionDiv.querySelector(".delete-question-btn");
        deleteQuestionBtn.addEventListener("click", function () {
            questionDiv.classList.remove("show");

            setTimeout(function () {
                questionDiv.remove();
            }, 300);
        });
    }

    function addQuestion() {
        const questionDiv = document.createElement("div");
        questionDiv.classList.add("question-container");

        questionDiv.innerHTML = `
            <div class="question-upper">
                <input type="text" name="question-title[${questionCounter}]" class="question-title" placeholder="Untitled Question" required>
            </div>
            <div class="question-choices-container">
                <img src="../imgs/plus_choices.svg" alt="Add choice button" class="add-choice-btn" style="display: none;">
            </div>
            <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
        `;

        surveyContainer.appendChild(questionDiv);
        questionCounter++;
        handleChoices(questionDiv);
        updateImagesForDarkMode();

        setTimeout(() => {
            questionDiv.classList.add("show");
        }, 10);
    }

    addOptionsBtn.addEventListener("click", function () {
        addOptionsContainer.classList.toggle("show");
    
        if (addOptionsContainer.classList.contains("show")) {
            addOptionsContainer.style.display = "flex";
            setTimeout(() => {
                addOptionsContainer.style.opacity = 1;
                addOptionsContainer.style.transform = "scale(1)";
                scrollToBottom();
            }, 10);
        } else {
            addOptionsContainer.style.opacity = 0;
            addOptionsContainer.style.transform = "scale(0.9)";
            setTimeout(() => {
                addOptionsContainer.style.display = "none";
            }, 300);
        }
    });
    
    function scrollToBottom() {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: "smooth"
        });
    }

    document.addEventListener("click", function (event) {
        if (!addOptionsContainer.contains(event.target) && event.target !== addOptionsBtn) {
            if (addOptionsContainer.classList.contains("show")) {
                addOptionsContainer.style.opacity = 0;
                addOptionsContainer.style.transform = "scale(0.9)";
                setTimeout(() => {
                    addOptionsContainer.style.display = "none";
                }, 300);
                addOptionsContainer.classList.remove("show");
            }
        }
    });

    addQuestionBtn.addEventListener("click", function () {
        addQuestion();
        scrollToBottom();
    });

    addTdBtn.addEventListener("click", function () {
        const titleDescContainer = document.createElement("div");
        titleDescContainer.classList.add("new-title-desc-container");

        titleDescContainer.innerHTML = `
            <input type="text" name="survey-title" class="survey-title" value="Untitled Section">
            <textarea name="survey-desc" class="survey-desc" placeholder="Section Description"></textarea>
            <img src="../imgs/delete.svg" alt="Delete section button" class="delete-section-btn">
        `;
        surveyContainer.appendChild(titleDescContainer);
        updateImagesForDarkMode();

        const newTextarea = titleDescContainer.querySelector(".survey-desc");
        newTextarea.style.resize = "none";

        newTextarea.addEventListener("input", function() {
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";
        });

        setTimeout(() => {
            titleDescContainer.classList.add("show");
        }, 10);

        const deleteSectionBtn = titleDescContainer.querySelector(".delete-section-btn");
        deleteSectionBtn.addEventListener("click", function () {
            titleDescContainer.classList.remove("show");

            setTimeout(() => {
                titleDescContainer.remove();
            }, 300);
        });
        scrollToBottom();
    });

    addImageBtn.addEventListener("click", function () {
        const imageUploadDiv = document.createElement("div");
        imageUploadDiv.classList.add("image-upload-container");
        imageUploadDiv.innerHTML = `
            <p class="upload-text"><img src="../imgs/upload.svg" alt="Upload button" class="upload-image-btn">Click to upload an image</p>
            <img src="../imgs/delete.svg" alt="Delete button" class="delete-upload-btn">
        `;
    
        const imageInput = document.createElement("input");
        imageInput.type = "file";
        imageInput.accept = "image/*";
        imageInput.style.display = "none";
    
        imageUploadDiv.appendChild(imageInput);
    
        imageUploadDiv.addEventListener("click", function () {
            imageInput.click();
        });
    
        imageInput.addEventListener("change", function () {
            const file = imageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("uploaded-image");
                    img.style.maxWidth = "100%";
                    img.style.maxHeight = "100%";
    
                    imageUploadDiv.innerHTML = "";
                    imageUploadDiv.appendChild(img);
    
                    const deleteBtn = document.createElement("img");
                    deleteBtn.classList.add("delete-upload-btn");
    
                    const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";
                    deleteBtn.src = darkModeEnabled ? "../imgs/delete_dark.svg" : "../imgs/delete.svg";
                    deleteBtn.alt = "Delete button";
    
                    deleteBtn.addEventListener("click", function (event) {
                        event.stopPropagation();
                        imageUploadDiv.classList.remove("show");
    
                        setTimeout(() => {
                            imageUploadDiv.remove();
                        }, 300);
                    });
    
                    imageUploadDiv.appendChild(deleteBtn);
                };
                reader.readAsDataURL(file);
            }
        });
    
        imageUploadDiv.querySelector(".delete-upload-btn").addEventListener("click", function (event) {
            event.stopPropagation();
            imageUploadDiv.classList.remove("show");
    
            setTimeout(() => {
                imageUploadDiv.remove();
            }, 300);
        });
    
        surveyContainer.appendChild(imageUploadDiv);
    
        updateImagesForDarkMode();
    
        setTimeout(() => {
            imageUploadDiv.classList.add("show");
        }, 10);
        scrollToBottom();
    });

    const existingQuestions = document.querySelectorAll(".question-container");
    existingQuestions.forEach(questionDiv => {
        handleChoices(questionDiv);
        questionDiv.classList.add("show");
    });
});