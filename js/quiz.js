let currentQuestion = 0;
let score = 0;

const questions = [
    {
        question: "What is 2 + 2?",
        answers: ["3", "4", "5"],
        correct: "4"
    },
    {
        question: "What is capital of France?",
        answers: ["London", "Berlin", "Paris"],
        correct: "Paris"
    }
];

function loadQuestion() {

    const q = questions[currentQuestion];

    document.getElementById("question").innerText = q.question;

    const answersDiv = document.getElementById("answers");
    answersDiv.innerHTML = "";

    q.answers.forEach(a => {

        const btn = document.createElement("button");
        btn.innerText = a;

        btn.onclick = () => {

            if (a === q.correct) {
                score++;
            }

        };

        answersDiv.appendChild(btn);
    });
}

function nextQuestion() {

    currentQuestion++;

    if (currentQuestion < questions.length) {
        loadQuestion();
    } else {
        finishQuiz();
    }
}

function finishQuiz() {

    document.getElementById("question").innerText = "Quiz finished!";
    document.getElementById("answers").innerHTML = "";

    document.getElementById("score").innerText =
        "Score: " + score + "/" + questions.length;

    const userId = localStorage.getItem("user_id");

    console.log("SAVING user_id:", userId);

    fetch("http://localhost:8888/quiz-app/php/saveResult.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            user_id: userId,
            score: score,
            total: questions.length
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("SAVE RESPONSE:", data);

        setTimeout(() => {
            window.location.href = "results.html?score=" + score;
        }, 500);
    });
}

loadQuestion();