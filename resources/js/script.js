// Note: DOM elements may not exist on every page. Attach handlers after DOMContentLoaded

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
    // Profile popup handlers (only if elements exist on the page)
    var profilePopup = document.getElementById('profilePopup');
    var profileDisplay = document.getElementById('profileDisplay');
    var closeProfilePopupBtn = document.getElementById('closeProfilePopupBtn');

    if (profileDisplay && profilePopup) {
        profileDisplay.addEventListener('click', function () {
            profilePopup.classList.toggle('open');
        });
    }

    if (closeProfilePopupBtn && profilePopup) {
        closeProfilePopupBtn.addEventListener('click', function () {
            profilePopup.classList.toggle('open');
        });
    }

    // Light mode preference
    if (localStorage.getItem("lightMode") === "true") {
        document.body.classList.add("light-mode");
        var lmBtn = document.getElementById("light-mode-btn");
        if (lmBtn) {
            lmBtn.innerHTML = `<i class="fa fa-moon-o"></i> Dark Mode`;
        }
    }
});

/**
 * Handles the download of the QR code with Top and Bottom text + Padding + Square Border.
 */
window.handleDownload = function() {
    try {
        const qrContainer = document.querySelector('.qr-code-inner-box');

        if (!qrContainer) {
            console.error('QR Code container not found.');
            return;
        }

        const originalSvg = qrContainer.querySelector('svg');
        const topTextElement = qrContainer.querySelector('.top-text');
        const bottomTextElement = qrContainer.querySelector('.bottom-text');

        if (!originalSvg) {
            console.error('QR Code SVG element not found.');
            alert('Error: Could not find QR Code to download.');
            return;
        }

        // Get computed style for Primary Color
        const rootStyles = getComputedStyle(document.documentElement);
        const primaryColor = rootStyles.getPropertyValue('--primary').trim() || '#000000';

        // Get original dimensions
        const width = parseInt(originalSvg.getAttribute('width') || 200);
        const height = parseInt(originalSvg.getAttribute('height') || 200);

        // Define settings
        const padding = 2; // Minimum padding
        const topTextHeight = 35;
        const bottomTextHeight = 35;

        // Calculate Content Dimensions (Text + QR)
        const contentWidth = width;
        const contentHeight = height + topTextHeight + bottomTextHeight;

        // Determine Square Size
        // We take the larger of the width or height to ensure nothing is cropped,
        // and add padding to create the square box.
        const maxContentDimension = Math.max(contentWidth, contentHeight);
        const totalDimension = maxContentDimension + (padding * 2);

        // Final SVG Dimensions (Square)
        const totalWidth = totalDimension;
        const totalHeight = totalDimension;

        // Calculate Centering Offsets
        // This centers the content block (QR + Text) within the square canvas
        const xOffset = (totalWidth - width) / 2;
        const yOffset = (totalHeight - contentHeight) / 2;

        // Extract content
        const innerContent = originalSvg.innerHTML;
        const topText = topTextElement ? topTextElement.innerText : "Did you find this lost item?";
        const bottomText = bottomTextElement ? bottomTextElement.innerText : "Help me find my belonging";

        // Create new SVG string
        const newSvg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="${totalWidth}" height="${totalHeight}" viewBox="0 0 ${totalWidth} ${totalHeight}">
            <rect width="100%" height="100%" fill="white"/>

            <rect x="0" y="0" width="${totalWidth}" height="${totalHeight}" fill="none" stroke="${primaryColor}" stroke-width="1" />

            <text x="50%" y="${yOffset + topTextHeight - 10}" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="${primaryColor}">
                ${topText}
            </text>

            <g transform="translate(${xOffset}, ${yOffset + topTextHeight})">
                ${innerContent}
            </g>

            <text x="50%" y="${yOffset + topTextHeight + height + bottomTextHeight - 10}" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="${primaryColor}">
                ${bottomText}
            </text>
        </svg>
        `;

        // Create blob and download link
        const blob = new Blob([newSvg], {type: 'image/svg+xml'});
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = "help-me-find-qr-code.svg";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);

    } catch (e) {
        console.error('Error during download:', e);
        alert('An error occurred while trying to download the QR code.');
    }
}
