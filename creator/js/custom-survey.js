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

const surveyInput = document.getElementById("survey-title");
const navTitle = document.getElementById("nav-title");

surveyInput.addEventListener("input", function() {
    navTitle.textContent = surveyInput.value || "Untitled Survey";
});

const textarea = document.getElementById("survey-desc");

textarea.style.resize = "none";
textarea.addEventListener("input", function() {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
});

document.addEventListener("DOMContentLoaded", function () {
    const addQuestionBtn = document.querySelector("#add-question-btn");
    const surveyContainer = document.querySelector("#survey-container");
    const addOptionsBtn = document.querySelector("#add-options-btn");
    const addOptionsContainer = document.querySelector("#add-options-container");
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

        addChoice();

        questionType.addEventListener("change", function (e) {
            choicesContainer.innerHTML = "";
            choicesContainer.appendChild(addChoiceBtn);

            if (e.target.value === "Multiple Choice") {
                addChoice("radio",questionIndex);
            } else if (e.target.value === "Checkboxes") {
                addChoice("checkbox",questionIndex);
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
});