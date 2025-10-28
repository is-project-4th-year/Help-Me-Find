// Get the popup and buttons
var profilePopup = document.getElementById('profilePopup');
var profileDisplay = document.getElementById('profileDisplay');


profileDisplay.onclick = function() {
    // profilePopup.style.display = (profilePopup.style.display === 'block') ? 'none' : 'block';
    profilePopup.classList.toggle('open');
}

// Close the popup when the close button is clicked
closeProfilePopupBtn.onclick = function() {
    // profilePopup.style.display = 'none'; // Hide the popup
    profilePopup.classList.toggle('open');
}

// window.onclick = function(event) {
//     if (event.target == profilePopup) {
//         // profilePopup.style.display = 'none';
//         profilePopup.classList.toggle('open');
//     }
// }

window.toggleDrawer = function() {
    var drawer = document.getElementById('drawer');
    var menuBtn = document.getElementById('menu-btn');
    var mainContent = document.querySelector('.main-content');

    drawer.classList.toggle('open');

    if (window.innerWidth >= 768) {
        mainContent.classList.toggle('shifted');
    }

    if (drawer.classList.contains('open')) {
        menuBtn.innerHTML = '<i class="fa fa-close"></i>';
    } else {
        menuBtn.innerHTML = '<i class="fa fa-navicon"></i>';
    }

}

window.toggleLightMode = function() {
    document.body.classList.toggle('light-mode');
    const isLightMode = document.body.classList.contains("light-mode");
    localStorage.setItem('lightMode', isLightMode);

    document.getElementById('light-mode-btn').innerHTML = isLightMode
        ? `<i class="fa fa-moon-o"></i> Dark Mode`
        : `<i class="fa fa-sun-o"></i> Light Mode`;
}

window.detailTab = function() {
    var rootStyles = getComputedStyle(document.documentElement);
    var bodyStyles = getComputedStyle(document.body);
    var mainCardColor, mainCardDarkerColor;

    if(document.body.classList.contains('light-mode')){
        mainCardColor = bodyStyles.getPropertyValue('--main-card');
        mainCardDarkerColor = bodyStyles.getPropertyValue('--main-card-darker');
    } else {
        mainCardColor = rootStyles.getPropertyValue('--main-card');
        mainCardDarkerColor = rootStyles.getPropertyValue('--main-card-darker');
    }
    document.getElementById('tab1').style.background = mainCardColor;
    document.getElementById('tab2').style.background = mainCardDarkerColor;

    document.getElementById('bookLogs').style.display = "none";
    document.getElementById('bookDetails').style.display = "block";

    document.getElementById('tab1').style.zIndex = 1;
    document.getElementById('tab2').style.zIndex = 0;
}

window.logTab = function() {
    var rootStyles = getComputedStyle(document.documentElement);
    var bodyStyles = getComputedStyle(document.body);
    var mainCardColor, mainCardDarkerColor;

    if(document.body.classList.contains('light-mode')){
        mainCardColor = bodyStyles.getPropertyValue('--main-card');
        mainCardDarkerColor = bodyStyles.getPropertyValue('--main-card-darker');
    } else {
        mainCardColor = rootStyles.getPropertyValue('--main-card');
        mainCardDarkerColor = rootStyles.getPropertyValue('--main-card-darker');
    }
    document.getElementById('tab2').style.background = mainCardColor;
    document.getElementById('tab1').style.background = mainCardDarkerColor;


    document.getElementById('bookDetails').style.display = "none";
    document.getElementById('bookLogs').style.display = "block";
    
    document.getElementById('tab1').style.zIndex = 0;
    document.getElementById('tab2').style.zIndex = 1;
}

// Check for light mode preference on page load
document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("lightMode") === "true") {
        document.body.classList.add("light-mode");
        document.getElementById("light-mode-btn").innerHTML = `<i class="fa fa-moon-o"></i> Dark Mode`;
    }
});
