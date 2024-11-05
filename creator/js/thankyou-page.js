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
    const addQuestionBtn = document.querySelector("#add-question-btn");
    const surveyContainer = document.querySelector("#survey-container");
    const addOptionsBtn = document.querySelector("#add-options-btn");
    const addOptionsContainer = document.querySelector("#add-options-container");
    const addTdBtn = document.querySelector("#add-td-btn");
    const addImageBtn = document.querySelector("#add-image-btn");
    const addSaveImage = document.getElementById("add-save-btn");

    let questionCounter = 0;

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

    function handleExistingChoices(questionDiv, existingquestionCounter) {
        const questionType = questionDiv.querySelector(".question-type");
        const choicesContainer = questionDiv.querySelector(".question-choices-container");
        const addChoiceBtn = questionDiv.querySelector(".add-choice-btn");

        function addChoice(inputType = "radio") {
            const choiceDiv = document.createElement("div");
            choiceDiv.classList.add("choice-container");

            const inputOption = document.createElement("input");
            inputOption.type = inputType;
            inputOption.name = "multiple-choice";
            inputOption.addEventListener("click", (event) => {
                
            });

            const inputText = document.createElement("input");
            inputText.type = "text";
            inputText.classList.add("choice-input-text");
            inputText.placeholder = "Option text";
            inputText.required = true;
            inputText.id = existingquestionCounter;
            inputText.name = `more_choice[${existingquestionCounter}][]`;

            const deleteImg = document.createElement("img");
            deleteImg.src = "../imgs/close.svg";
            deleteImg.alt = "Remove option";
            deleteImg.classList.add("delete-choice-btn");
            deleteImg.addEventListener("click", function () {
                choiceDiv.remove();
            });

            choiceDiv.appendChild(inputOption);
            choiceDiv.appendChild(inputText);
            choiceDiv.appendChild(deleteImg);
            choicesContainer.insertBefore(choiceDiv, addChoiceBtn);
            updateImagesForDarkMode();
        }

        function addDropdownChoices() {
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

        function addShortAnswer() {
            const shortAnswerInput = document.createElement("input");
            shortAnswerInput.type = "text";
            shortAnswerInput.classList.add("short-answer-input");
            shortAnswerInput.placeholder = "Your answer";
            shortAnswerInput.readOnly = true;
            choicesContainer.appendChild(shortAnswerInput);
        }
        
        function addParagraph() {
            const paragraphTextarea = document.createElement("textarea");
            paragraphTextarea.classList.add("paragraph-textarea");
            paragraphTextarea.placeholder = "Your answer";
            paragraphTextarea.rows = 4;
            paragraphTextarea.style.resize = "none";
            paragraphTextarea.readOnly = true;
        
            paragraphTextarea.addEventListener("input", function () {
                this.style.height = "auto";
                this.style.height = this.scrollHeight + "px";
            });
        
            choicesContainer.appendChild(paragraphTextarea);
        }

        questionType.addEventListener("change", function (e) {
            choicesContainer.innerHTML = "";
            if (e.target.value === "Multiple Choice") {
                choicesContainer.appendChild(addChoiceBtn);
                addChoice("radio");
                addChoice("radio");
            } else if (e.target.value === "Checkboxes") {
                choicesContainer.appendChild(addChoiceBtn);
                addChoice("checkbox");
                addChoice("checkbox");
            } else if (e.target.value === "Dropdown") {
                addDropdownChoices();
            } else if (e.target.value === "Short Answer") {
                addShortAnswer();
            } else if (e.target.value === "Paragraph") {
                addParagraph();
            }
        });

        addChoiceBtn.addEventListener("click", function () {
            const inputType = questionType.value === "Multiple Choice" ? "radio" : "checkbox";
            addChoice(inputType);
        });

        const deleteQuestionBtn = questionDiv.querySelector(".delete-question-btn");
        deleteQuestionBtn.addEventListener("click", function () {
            questionDiv.classList.remove("show");

            setTimeout(function () {
                questionDiv.remove();
            }, 300);
        });
    }

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
    let existingquestionCounter = 0;
    existingQuestions.forEach(questionDiv => {
        questionDiv.classList.add("show");

        const questionType = questionDiv.querySelector(".question-type").value;
        if (questionType === "Multiple Choice" || questionType === "Checkboxes") {
            handleExistingChoices(questionDiv,existingquestionCounter);
        }
        existingquestionCounter++;
    });

    const form = document.getElementById("survey-form");

    form.addEventListener("submit", function(event) {
        const checkboxes = document.querySelectorAll('input[name^="checkbox"]');
        let checked = false;

        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                checked = true;
            }
        });

        if (!checked) {
            event.preventDefault();
            alert("Please select at least one checkbox.");
        }
    });

    document.getElementById('clear-btn').addEventListener('click', function(){
        const id = document.getElementById("user_id").value;
        const survey_id = document.getElementById("survey_id").value;
        window.location.href = `response-page.php?id=${id}&survey_id=${survey_id}`;
    });
});