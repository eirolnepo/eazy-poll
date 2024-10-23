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

surveyInput.addEventListener("input", function() {
    navTitleInput.value = surveyInput.value || "Untitled Survey";
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
    const addQuestionBtn = document.querySelector("#add-question-btn");
    const surveyContainer = document.querySelector("#survey-container");
    const addOptionsBtn = document.querySelector("#add-options-btn");
    const addOptionsContainer = document.querySelector("#add-options-container");
    const addTdBtn = document.querySelector("#add-td-btn");
    const addImageBtn = document.querySelector("#add-image-btn");
    let questionCount = 0;

    addOptionsContainer.style.display = "none";
    addOptionsContainer.style.opacity = 0;
    addOptionsContainer.style.transform = "scale(0.9)";

    function handleChoices(questionDiv, questionIndex) {
        const questionType = questionDiv.querySelector(".question-type");
        const choicesContainer = questionDiv.querySelector(".question-choices-container");
        const addChoiceBtn = questionDiv.querySelector(".add-choice-btn");

        function addChoice(inputType = "radio", questionIndex) {
            const choiceDiv = document.createElement("div");
            choiceDiv.classList.add("choice-container");

            const inputOption = document.createElement("input");
            inputOption.type = inputType;
            inputOption.name = `multiple-choice[${questionIndex}]`;

            const inputText = document.createElement("input");
            inputText.type = "text";
            inputText.classList.add("choice-input-text");
            inputText.name = `choice-input[${questionIndex}][]`;
            inputText.placeholder = "Option text";

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
            choicesContainer.appendChild(shortAnswerInput);
        }

        function addParagraph() {
            const paragraphTextarea = document.createElement("textarea");
            paragraphTextarea.classList.add("paragraph-textarea");
            paragraphTextarea.placeholder = "Your answer";
            paragraphTextarea.rows = 4;
            paragraphTextarea.style.resize = "none";

            paragraphTextarea.addEventListener("input", function () {
                this.style.height = "auto";
                this.style.height = this.scrollHeight + "px";
            });

            choicesContainer.appendChild(paragraphTextarea);
        }

        addChoice();
        addChoice();

        questionType.addEventListener("change", function (e) {
            choicesContainer.innerHTML = "";
            if (e.target.value === "Multiple Choice") {
                choicesContainer.appendChild(addChoiceBtn);
                addChoice("radio",questionIndex);
                addChoice("radio",questionIndex);
            } else if (e.target.value === "Checkboxes") {
                choicesContainer.appendChild(addChoiceBtn);
                addChoice("checkbox",questionIndex);
                addChoice("checkbox",questionIndex);
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
            addChoice(inputType,questionIndex);
        });

        const deleteQuestionBtn = questionDiv.querySelector(".delete-question-btn");
        deleteQuestionBtn.addEventListener("click", function () {
            questionDiv.classList.remove("show");

            setTimeout(function () {
                questionDiv.remove();
            }, 300);
        });
    }

    const existingQuestions = document.querySelectorAll(".question-container");
    existingQuestions.forEach(questionDiv => {
        handleChoices(questionDiv, questionCount);
        questionDiv.classList.add("show");
        questionCount++;
    });

    function addQuestion() {
        const questionDiv = document.createElement("div");
        questionDiv.classList.add("question-container");

        questionDiv.innerHTML = `
            <div class="question-upper">
                <input type="text" name="question-title[${questionCount}]" class="question-title" placeholder="Untitled Question">
                <select name="question-type[${questionCount}]" class="question-type">
                    <option value="Multiple Choice">Multiple Choice</option>
                    <option value="Checkboxes">Checkboxes</option>
                    <option value="Dropdown">Dropdown</option>
                    <option value="Short Answer">Short Answer</option>
                    <option value="Paragraph">Paragraph</option>
                </select>
            </div>
            <div class="question-choices-container">
                <img src="../imgs/plus_choices.svg" alt="Add choice button" class="add-choice-btn">
            </div>
            <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
        `;

        surveyContainer.appendChild(questionDiv);
        handleChoices(questionDiv, questionCount);
        questionCount++;

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
            }, 10);
        } else {
            addOptionsContainer.style.opacity = 0;
            addOptionsContainer.style.transform = "scale(0.9)";
            setTimeout(() => {
                addOptionsContainer.style.display = "none";
            }, 300);
        }
    });

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
                    deleteBtn.src = "../imgs/delete.svg";
                    deleteBtn.alt = "Delete button";
                    deleteBtn.classList.add("delete-upload-btn");
    
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
    
        setTimeout(() => {
            imageUploadDiv.classList.add("show");
        }, 10);
    });      
});