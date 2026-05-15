console.log("auth.js loaded");

window.login = async function () {

    console.log("LOGIN CLICKED");

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    const response = await fetch("http://localhost:8888/quiz-app/php/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ username, password })
    });

    const data = await response.json();

    console.log("LOGIN RESPONSE:", data);

    if (data && data.success === true) {

        console.log("SUCCESS BLOCK ENTERED");

        const userId = data.user_id;

        console.log("USER ID:", userId);

        localStorage.setItem("user_id", String(userId));

        console.log("STORED USER ID:", localStorage.getItem("user_id"));

        setTimeout(() => {
            window.location.href = "index.html";
        }, 500);

    } else {

        console.log("LOGIN FAILED OR INVALID RESPONSE");

        document.getElementById("message").innerText =
            data.message || "Login failed";
    }
};