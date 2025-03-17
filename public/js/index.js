function menu_shown () {
    const list = document.getElementById("top_menu").classList;
  list.add("shown");  
}
function menu_hidden () {
    const list = document.getElementById("top_menu").classList;
  list.remove("shown");  
}


var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");    
    var content = this.nextElementSibling;
    var faqStatus = this.querySelector(".faq-status img"); 
    if (content.style.display === "block") {
      content.style.display = "none";
      faqStatus.src = "/./images/plus (1).png"    
    } else {
      content.style.display = "block";
      faqStatus.src = "./images/minus.png"
    }
    
  });
}

const myButton = document.getElementById("fadeUp");

window.addEventListener("scroll", () => {
  myButton.style.display = window.scrollY > 1000 ? "flex" : "none";
});

myButton.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

function sidebar_open () {  
  const list = document.getElementById('sidebar').classList;  
  list.remove('sidebar-hidden')
  
}
function sidebar_close () {  
  const list = document.getElementById('sidebar').classList;
  list.add('sidebar-hidden')
  document.getElementsByClassName('sidebar-close')[0].addEventListener('onclick', top_sidebar_close());
}


document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("toggle");
  const overlay = document.getElementById("overlay");
  const menuIcon = toggle.querySelector("img");

  toggle.addEventListener("click", function (event) {
      event.stopPropagation();
      overlay.classList.toggle("open");
      if (overlay.classList.contains("open")) {
          menuIcon.src = "./images/close.png";  
      } else {
          menuIcon.src = "./images/menu.png"; 
      }
  });

  document.addEventListener("click", function (event) {
      if (!toggle.contains(event.target) && !overlay.contains(event.target)) {
          overlay.classList.remove("open");  
          menuIcon.src = "./images/menu.png";  
      }
  });

  const menuItems = document.querySelectorAll('.side-list');  
  menuItems.forEach(item => {
      item.addEventListener('click', function () {
          overlay.classList.remove("open");  
          menuIcon.src = "./images/menu.png";  
      });
  });
});

