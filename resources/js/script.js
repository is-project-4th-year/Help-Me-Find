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
 * Handles the download of the QR code as a PDF.
 * Includes Top/Bottom text, Padding, and a 1px Square Border.
 */
window.handleDownload = function() {
    try {
        // 1. Check dependencies
        if (!window.jspdf) {
            alert('PDF Library is not loaded yet. Please check your internet connection.');
            return;
        }

        // 2. Get DOM elements
        const qrContainer = document.querySelector('.qr-code-inner-box');
        if (!qrContainer) return;

        const originalSvg = qrContainer.querySelector('svg');
        const topTextElement = qrContainer.querySelector('.top-text');
        const bottomTextElement = qrContainer.querySelector('.bottom-text');

        if (!originalSvg) {
            alert('Error: Could not find QR Code to download.');
            return;
        }

        // 3. Get Styles & Dimensions
        const rootStyles = getComputedStyle(document.documentElement);
        const primaryColor = rootStyles.getPropertyValue('--primary').trim() || '#000000';

        const width = parseInt(originalSvg.getAttribute('width') || 200);
        const height = parseInt(originalSvg.getAttribute('height') || 200);

        // Settings for layout
        const padding = 2;
        const topTextHeight = 35;
        const bottomTextHeight = 35;

        // Determine Square Dimensions
        const contentWidth = width;
        const contentHeight = height + topTextHeight + bottomTextHeight;
        const maxContentDimension = Math.max(contentWidth, contentHeight);
        const totalDimension = maxContentDimension + (padding * 2);

        const totalWidth = totalDimension;
        const totalHeight = totalDimension;

        // Center offsets
        const xOffset = (totalWidth - width) / 2;
        const yOffset = (totalHeight - contentHeight) / 2;

        const topText = topTextElement ? topTextElement.innerText : "Did you find this lost item?";
        const bottomText = bottomTextElement ? bottomTextElement.innerText : "Help me find my belonging";

        // 4. Construct SVG String
        const newSvg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="${totalWidth}" height="${totalHeight}" viewBox="0 0 ${totalWidth} ${totalHeight}">
            <rect width="100%" height="100%" fill="white"/>

            <rect x="0" y="0" width="${totalWidth}" height="${totalHeight}" fill="none" stroke="${primaryColor}" stroke-width="1" />

            <text x="50%" y="${yOffset + topTextHeight - 10}" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="${primaryColor}">
                ${topText}
            </text>

            <g transform="translate(${xOffset}, ${yOffset + topTextHeight})">
                ${originalSvg.innerHTML}
            </g>

            <text x="50%" y="${yOffset + topTextHeight + height + bottomTextHeight - 10}" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="${primaryColor}">
                ${bottomText}
            </text>
        </svg>
        `;

        // 5. Convert SVG -> Canvas -> PDF
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        // Use a multiplier for better PDF resolution (optional, but good for text)
        const scale = 2;
        canvas.width = totalWidth * scale;
        canvas.height = totalHeight * scale;
        ctx.scale(scale, scale);

        const svgBlob = new Blob([newSvg], {type: 'image/svg+xml;charset=utf-8'});
        const url = URL.createObjectURL(svgBlob);

        img.onload = function() {
            ctx.drawImage(img, 0, 0);

            const { jsPDF } = window.jspdf;
            // Create PDF with the exact square dimensions
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'px',
                format: [totalWidth, totalHeight]
            });

            // Add image to PDF
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 0, 0, totalWidth, totalHeight);

            doc.save("help-me-find-qr-code.pdf");

            URL.revokeObjectURL(url);
        };

        img.src = url;

    } catch (e) {
        console.error('Error during download:', e);
        alert('An error occurred while trying to generate the PDF.');
    }
}
