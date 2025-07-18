function closeErrorPopup() {
    document.querySelector('.error-popup').style.display = 'none';
}

// Auto-hide the popup after 5 seconds
setTimeout(() => {
    let popup = document.querySelector('.error-popup');
    if (popup) {
        popup.style.display = 'none';
    }
}, 5000);