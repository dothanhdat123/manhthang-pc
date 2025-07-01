document.addEventListener('DOMContentLoaded', function () {
    var popupOverlay = document.querySelector('.promo-popup-overlay');
    var popup = document.querySelector('.promo-popup');
    var closeBtn = document.querySelector('.promo-popup .close-btn');

    function showPopup() {
        popupOverlay.style.display = 'block';
        popup.style.display = 'block';
    }

    function closePopup() {
        popupOverlay.style.display = 'none';
        popup.style.display = 'none';
    }

    closeBtn.addEventListener('click', closePopup);
    popupOverlay.addEventListener('click', closePopup);

    // Show popup after 1 second delay
    setTimeout(showPopup, 1000);
});
