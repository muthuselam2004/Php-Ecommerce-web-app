function toggleMode() {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
        document.getElementById("mode-btn").innerText = "☀ Light";
    } else {
        localStorage.setItem("theme", "light");
        document.getElementById("mode-btn").innerText = "🌙 Dark";
    }
}

window.onload = () => {
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
        document.getElementById("mode-btn").innerText = "☀ Light";
    }
};
