document.addEventListener('DOMContentLoaded', function () {
    // Find the notification element
    var notification = document.getElementById('notification');
    document.getElementById('notification').classList.add('show');

    // Set a timeout to hide the notification after 5 seconds
    setTimeout(function () {
        notification.style.display = 'none';
    }, 5000);
});