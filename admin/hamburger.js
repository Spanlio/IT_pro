// hamburher menu script
let indentifikators = document.querySelector("#hamburger-btn")

    if(!indentifikators){
        console.log("neatradu hamburgeri")
    }else{
        document.addEventListener("DOMContentLoaded", () => { 
    // domcontentloaded vienkarsos vardos pagaida kamer html dom ir ieladejies

    const btn = document.getElementById("hamburger-btn");
    const nav = document.getElementById("admin-nav");

    if (!btn || !nav) {
        console.log("hamburger not found");
        return;
    }

    btn.addEventListener("click", () => {
        nav.classList.toggle("active");
        console.log("clicked");
    });

    document.addEventListener("click", (e) => {
        if (!nav.contains(e.target) && !btn.contains(e.target)) {
            nav.classList.remove("active");
        }
    });

});
    }

