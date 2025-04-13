// for game page code 
// === screens
var frame = document.getElementById("game-frame");
var playScreen = document.getElementById("play-screen");
var loadingScreen = document.getElementById("loading-screen");
// === button 
var playButton = document.getElementById("game-play-button");
// === progress 
var progressLine = document.getElementById("progressLine");

if (frame !== null && playScreen !== null && loadingScreen !== null) {
  function PlayGame() {
    playScreen.classList.add("d-none");
    loadingScreen.classList.remove("d-none");
    var frameSource = frame.getAttribute("data-frame-src");
    frame.src = frameSource;
    setTimeout(() => { progressLine.style.width = '10%' }, 400 * 2);
    setTimeout(() => { progressLine.style.width = '20%' }, 800 * 2);
    setTimeout(() => { progressLine.style.width = '30%' }, 1200 * 2);
    setTimeout(() => { progressLine.style.width = '40%' }, 1600 * 2);
    setTimeout(() => { progressLine.style.width = '50%' }, 2000 * 2);
    setTimeout(() => { progressLine.style.width = '60%' }, 2400 * 2);
    setTimeout(() => { progressLine.style.width = '70%' }, 2800 * 2);
    setTimeout(() => { progressLine.style.width = '80%' }, 3200 * 2);
    setTimeout(() => { progressLine.style.width = '90%' }, 3600 * 2);
    setTimeout(() => { progressLine.style.width = '100%'; }, 4000 * 2);
  }
    // playButton.onclick = () => {
    //     }

    frame.onload = () => {
        setTimeout(() => {
            loadingScreen.classList.add("d-none");
        }, 16000);

    }
}


// for sidebar toggle in mobile
function ToggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  sidebar?.classList.toggle("active");
}

// for header effect
var header = document.querySelector("header.zon-header");
document.addEventListener("scroll", () => {
  if (header !== null) {
    if (document.documentElement.scrollTop > 0 || document.body.scrollTop > 0) {
      header.classList.add("active");
    } else {
      header.classList.remove("active");
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const lazyImages = document.querySelectorAll(".lazy");

  if ("IntersectionObserver" in window) {
    const lazyImageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const lazyImage = entry.target;
          lazyImage.src = lazyImage.dataset.src;
          lazyImage.classList.remove("lazy");
          lazyImageObserver.unobserve(lazyImage);
        }
      });
    });

    lazyImages.forEach((lazyImage) => {
      lazyImageObserver.observe(lazyImage);
    });
  } else {
    // Fallback for browsers that do not support IntersectionObserver
    let lazyLoadThrottleTimeout;

    function lazyLoad() {
      if (lazyLoadThrottleTimeout) {
        clearTimeout(lazyLoadThrottleTimeout);
      }

      lazyLoadThrottleTimeout = setTimeout(function () {
        const scrollTop = window.pageYOffset;
        lazyImages.forEach((img) => {
          if (img.offsetTop < window.innerHeight + scrollTop) {
            img.src = img.dataset.src;
            img.classList.remove("lazy");
          }
        });
        if (lazyImages.length == 0) {
          document.removeEventListener("scroll", lazyLoad);
          window.removeEventListener("resize", lazyLoad);
          window.removeEventListener("orientationChange", lazyLoad);
        }
      }, 20);
    }

    document.addEventListener("scroll", lazyLoad);
    document.addEventListener("mousemove", lazyLoad);
    window.addEventListener("resize", lazyLoad);
    window.addEventListener("orientationChange", lazyLoad);
  }
});

// for image lazy loading
var games = document.querySelectorAll(".game-card");
if (games.length > 0) {
  games.forEach((game) => {
    var loader = game.querySelector(".game-poster .loader");
    var poster = game.querySelector(".game-poster img[alt]");
    loader?.classList.add("active");

    if (poster.complete) {
      setTimeout(() => {
        loader?.classList.remove("active");
      }, 2000);
    } else {
      // Add an event listener to check when the image loads
      poster.onload = () => {
        setTimeout(() => {
          loader?.classList.remove("active");
        }, 2000);
      };

      poster.onerror = () => {
        loader?.classList.add("active");
      };
    }
  });
}

// for loader
var appLoader = document.getElementById("app_loader");
var intro = document.getElementById("intro");
var loader = document.getElementById("loader");
var imageSrc = loader?.getAttribute("data-src");
var overlay = loader?.querySelector(".overlay-full");

if (appLoader !== null) {
  setTimeout(() => {
    intro.classList.add("hide");
  }, 1700);

  setTimeout(() => {
    intro.classList.add("hidden");
    loader.style.backgroundImage = `url(${imageSrc})`;
    loader.classList.replace("hide", "show");
  }, 2800);

  setTimeout(() => {
    loader.classList.replace("opacity-0", "opacity-1");
  }, 3500);

  setTimeout(() => {
    overlay.classList.replace("opacity-1", "opacity-0");
  }, 3700);

  setTimeout(() => {
    overlay.classList.replace("opacity-0", "opacity-1");
    appLoader.classList.remove("active");
  }, 5800);
}

// set theme according to localStorage data
var themeChanger = document.getElementById("themeChanger");
var theme = localStorage.getItem("theme");
var html = document.documentElement;
var condition = theme === "light" ? "light" : "dark";
html.setAttribute("data-zon-skin", condition);
themeChanger?.classList.add(condition);

// zontal theme changer
function ChangeTheme() {
  var theme = localStorage.getItem("theme");

  if (theme === null) {
    localStorage.setItem("theme", "dark");
  }

  if (theme === "light") {
    // dark mode
    localStorage.setItem("theme", "dark");
    document.documentElement.setAttribute("data-zon-skin", "dark");
    themeChanger?.classList.replace("light", "dark");
  } else {
    // light mode
    localStorage.setItem("theme", "light");
    document.documentElement.setAttribute("data-zon-skin", "light");
    themeChanger?.classList.replace("dark", "light");
  }
}

// functions for slider
function SlideLeft(scroll, element) {
  var slider = element.nextElementSibling;

  var slideLeftButton = element;
  var slideRightButton = element.nextElementSibling.nextElementSibling;

  if (slider !== undefined) {
    slider.scrollLeft -= scroll + 20;
  }

  // condition || code for showing / hiding slide buttons
  setTimeout(() => {
    if (slider.scrollLeft <= 420) {
      slideLeftButton.classList.add("d-none");
    }
  }, 100);

  // for right button
  if (slider.scrollLeft + slider.clientWidth + 1 >= slider.scrollWidth) {
    slideRightButton.classList.remove("d-none");
  }
}

function removeActiveClass() {
  var featuredGames = document.querySelectorAll(".featured-games .game-card");
  if (featuredGames.length > 0) {
    featuredGames.forEach((game) => {
      game.classList.remove("active");
    });
  }
}

function AutoSlide(scroll, element) {
  var dynamicContent = document.getElementById("dynamicContent");
  var slider = element.parentNode.parentNode;
  var slideLeftButton = element.parentNode.parentNode.previousElementSibling;
  var slideRightButton = element.parentNode.parentNode.nextElementSibling;
  if (slider !== undefined) {
    slider.scrollLeft += scroll + 20;
  }

  // dynamicContent.classList.remove("FadeIn");
  removeActiveClass();
  element.classList.add("active");
  // dynamicContent.classList.add("FadeIn");

  setTimeout(() => {
    // for left button
    if (slider.scrollLeft > 0) {
      slideLeftButton.classList.remove("d-none");
    }

    // for right button
    if (slider.scrollLeft + slider.clientWidth + 1 >= slider.scrollWidth) {
      slideRightButton.classList.remove("d-none");
    }
  }, 100);
}

function SlideRight(scroll, element) {
  var slider = element.previousElementSibling;

  var slideRightButton = element;
  var slideLeftButton = element.previousElementSibling.previousElementSibling;

  if (slider !== undefined) {
    slider.scrollLeft += scroll + 20;
  }

  setTimeout(() => {
    // for left button
    if (slider.scrollLeft > 0) {
      slideLeftButton.classList.remove("d-none");
    }
  }, 100);

  setTimeout(() => {
    // condition || code for showing / hiding slide buttons
    if (slider.scrollLeft + slider.clientWidth + 10 >= slider.scrollWidth) {
      slideRightButton.classList.add("d-none");
    }
  }, 100);
}

// for viewing game data in banner
var featuredGames = document.querySelectorAll(".featured-games .game-card");
if (featuredGames.length > 0) {
  featuredGames.forEach((game) => {
    game.addEventListener("click", handleData);
  });

  function handleData() {
    var button = this;
    var gameIcon = button.getAttribute("data-game-icon");
    var gameName = button.getAttribute("data-game-name");
    var gameImage = button.getAttribute("data-bg-image");
    var gameCategory = button.getAttribute("data-game-category");
    var gameCategoryLink = button.getAttribute("data-game-category-link");
    var gameLink = button.getAttribute("data-game-link");

    var dynamicData = document.getElementById("dynamicContent");
    dynamicData.classList.add("wow");
    dynamicData.classList.add("fadeInDown");
    dynamicData.querySelector(".content-title").textContent = gameName;
    dynamicData.querySelector(".content-title-sm").textContent = gameName;
    dynamicData.querySelector(".content-icon").src = gameIcon;
    dynamicData.querySelector(".content-category").textContent = gameCategory;
    dynamicData.querySelector(".content-category").href = gameCategoryLink;
    dynamicData.querySelector(".play-button").href = gameLink;
    addBgImage(gameImage);
    setTimeout(() => {
      dynamicData.classList.remove("wow");
      dynamicData.classList.remove("fadeInDown");
    }, 1500);
  }
}

// for add image in bg of activated slide game
function addBgImage(src) {
  var element = document.getElementById("bg-game-img");
  if (element !== null) {
    element.style.setProperty("background-image", `url(${src})`);
  }
}

// for activating page button
var buttonLists = document.querySelectorAll(
  ".app_start .sidebar ul li:not(.no-focus)"
);
// add click event
if (buttonLists.length > 0 && buttonLists !== null) {
  buttonLists.forEach((button) => {
    button.addEventListener("click", OnActiveRoute);
  });

  //  removing 'active' class from all button
  const removeClassFromAllButton = () => {
    buttonLists.forEach((button) => {
      button.classList.remove("active");
    });
  };

  // add 'active' class to which user click on the click
  function OnActiveRoute() {
    var button = this;
    // first remove class
    removeClassFromAllButton();
    // then add class in clicked button
    button.classList.add("active");
  }
}
