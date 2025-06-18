// Function for Sticky Navbar
const header = document.querySelector("header");
const scrollToTopBtn = document.getElementById("myBtn");

window.addEventListener("scroll", function() {
    header.classList.toggle("sticky", window.scrollY > 100);

    // Show/hide the "scroll to top" button based on the scroll position
    if (window.scrollY > 100) {
        scrollToTopBtn.classList.add("show");
    } else {
        scrollToTopBtn.classList.remove("show");
    }
});

// // Function for Search Button
// document.getElementById("search-btn").addEventListener("click", () => {
//     const query = document.getElementById("search-input").value.toLowerCase();
//     const sections = document.querySelectorAll("section");
//     sections.forEach(section => {
//         if (section.textContent.toLowerCase().includes(query)) {
//             section.style.backgroundColor = "yellow";
//             section.scrollIntoView({ behavior: "smooth" });
//         } else {
//             section.style.backgroundColor = "transparent";
//         }
//     });
// });


let menu = document.querySelector("#menu-icon");
let navlist = document.querySelector(".navlist");

menu.onclick = () => {
    menu.classList.toggle("bx-menu");
    navlist.classList.toggle("open");
};

window.onscroll = () => {
    menu.classList.remove("bx-x");
    navlist.classList.remove("open");
};

// Function to scroll to the top
function topFunction() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

// const chatbotBtn = document.getElementById("chatbot-btn");
// const chatbotWindow = document.getElementById("chatbot-window");

// chatbotBtn.addEventListener("click", () => {
//     chatbotWindow.style.display = chatbotWindow.style.display === "block" ? "none" : "block";
// });


// Fuction for Sending Message to Whatsapp
function sendwhatsapp() {
    var phonenumber = "+6282322473876";

    var name = document.querySelector('.name').value;
    var email = document.querySelector('.email').value;
    var number = document.querySelector('.number').value;
    var message = document.querySelector('.message').value;

    var url = "https://wa.me/+6282322473876/" + phonenumber + "?text="
    +"*Nama : *" +name+"%0a"
    +"*Email : *" +email+"%0a"
    +"*Nomor : *" +number+"%0a"
    +"*Pesan : *" +message+"%0a"

    window.open(url, '_blank').focus();
}