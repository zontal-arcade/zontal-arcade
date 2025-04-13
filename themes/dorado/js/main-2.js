function LeftScroll(event, scroll_amount) {
  var container = event.nextElementSibling;
  var scroll = scroll_amount;

  container.scrollLeft -= scroll;

  setTimeout(() => {
    if (container.scrollLeft <= 0) {
      event.classList.add("hidden");
    }
  }, 900);
}

function RightScroll(event, scroll_amount) {
  var container = event.previousElementSibling;
  var scroll = scroll_amount;

  container.scrollLeft += scroll;

  event.previousElementSibling.previousElementSibling.classList.remove(
    "hidden"
  );

  // console.log(event.previousElementSibling.previousElementSibling);

  // if (container.scrollLeft > 0) {
  // console.log(container.scrollLeft);
  // }
}

// const button = document.querySelectorAll(".ripple");
// if (button.length > 0) {
//     button.forEach((btn) => {
//         // var span = document.createElement("span");
//         // btn.appendChild(span);
//         // Listen for click event
//         btn.onclick = function (e) {
//           // Create span element
//           let ripple = document.createElement("span");

//           // Add ripple class to span
//           ripple.classList.add("ripple");

//           // Add span to the button
//           this.appendChild(ripple);

//           // Get position of X
//           let x = e.clientX - e.currentTarget.offsetLeft;

//           // Get position of Y
//           let y = e.clientY - e.currentTarget.offsetTop;

//           // Position the span element
//           ripple.style.left = `${x}px`;
//           ripple.style.top = `${y}px`;

//           // Remove span after 0.3s
//           setTimeout(() => {
//             ripple.remove();
//           }, 300);
//         };
//     })
// }

var ui_input = document.querySelectorAll(".ui-input");

if (ui_input.length > 0) {
  ui_input.forEach((input) => {
    input.onfocus = () => {
      input.classList.add("in-focused");
    };

    input.onblur = () => {
      input.classList.remove("in-focused");
    };
  });
}

// offcanvas menu tabs @code
var tabButtons = document.getElementsByClassName("tab-button");
var tabContents = document.getElementsByClassName("tab-content");

// console.log(tabButtons);
if (tabButtons.length > 0) {
  for (let i = 0; i < tabButtons.length; i++) {
    const button = tabButtons[i];
    button.addEventListener("click", HandleClickEvent);
    // button.onclick = () => {
    //   var target = button.getAttribute("data-target");
    //   console.log(target);
    //   button.classList.add("active");
    // };
  }
  // tabButtons.forEach((button) => {});
}

// for offcanvas tabs
function removeActiveClass(type) {
  if (type === "button") {
    for (let i = 0; i < tabButtons.length; i++) {
      const button = tabButtons[i];
      button.classList.remove("active");
    }
  }

  if (type === "tab-content") {
    for (let i = 0; i < tabContents.length; i++) {
      const button = tabContents[i];
      button.classList.add("hidden");
    }
  }
}

// for offcanvas tab button
function HandleClickEvent() {
  removeActiveClass("button");
  this.classList.add("active");
  var target = this.getAttribute("data-target");
  removeActiveClass("tab-content");
  var element = document.querySelector(target);
  element.classList.remove("hidden");
}

var offcanvasClose = document.querySelectorAll(".menu-close-button") || [];
offcanvasClose.forEach((close) => {
  close.onclick = (event) => {
    var offcanvas = event.target.parentNode.parentNode;
    offcanvas.classList.remove("active");
    document.querySelectorAll(".offcanvas-menu").forEach((menu) => {
      menu.classList.add("hidden");
    });
  };
});

var offcanvas = document.querySelector(".offcanvas");

const openLoginMenu = () => {
  closeAllMenus();
  offcanvas.classList.add("active");
  document.querySelector(".login-canvas").classList.remove("hidden");
};

const openUserMenu = () => {
  closeAllMenus();
  offcanvas.classList.add("active");
  document.querySelector(".user-canvas").classList.remove("hidden");
};

const openSignUpMenu = () => {
  closeAllMenus();
  offcanvas.classList.add("active");
  document.querySelector(".sign-up-canvas").classList.remove("hidden");
};

const closeAllMenus = () => {
  document.querySelectorAll(".offcanvas-menu").forEach((menu) => {
    menu.classList.add("hidden");
  })
}

// code for sidebar
var sidebar = document.getElementById("site-sidebar");

sidebar.addEventListener("mousemove", () => {
  sidebar.classList.add("active");
});

sidebar.addEventListener("mouseleave", () => {
  sidebar.classList.remove("active");
});

const openSidebar = () => {
  var burgerButtonOpen = document.querySelector(".menu-icon-open");
  var burgerButtonClose = document.querySelector(".menu-icon-closed");
  if (sidebar.classList.contains("short")) {
    burgerButtonOpen.style.display = "block";
    burgerButtonClose.style.display = "none";
  } else {
    burgerButtonClose.style.display = "block";
    burgerButtonOpen.style.display = "none";
  }
  sidebar.classList.toggle("short");
};

var search = document.getElementById("squery");

if (search) {
  search.addEventListener("keyup", () => {
    var searchResults = document.querySelector(".search-results");
    var overlay = document.querySelector(".search-result-overlay");
    if (search.value.length > 1) {
      searchResults.classList.add("active");
      overlay.classList.add("active");
    } else {
      searchResults.classList.remove("active");
      overlay.classList.remove("active");
    }
  });
}
