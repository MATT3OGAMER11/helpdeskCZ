window.onload = function() {
    // Check if the cookie is already set
    if (document.cookie.split(';').some((item) => item.trim().startsWith('cookies_accepted='))) {
        // The user has already accepted the cookies
        console.log('Cookie is set');
        document.getElementById('cookie-banner').hidden = true;
    } else {
        // The user has not accepted the cookies yet
        console.log('Cookie is not set');
        document.getElementById('cookie-banner').hidden = false;
    }

    // When the user clicks on the "Accept" button
    document.getElementById('accept-cookies').onclick = function() {
        cookieAccpet();
    };
};

function cookieAccpet() {
    // Set a cookie to remember that the user has accepted the cookies
    let date = new Date();
    date.setFullYear(date.getFullYear() + 1); // The cookie will expire in 1 year
    document.cookie = 'cookies_accepted=true; expires=' + date.toUTCString() + '; path=/';

    // Hide the cookie banner
    document.getElementById('cookie-banner').hidden = true;
}

