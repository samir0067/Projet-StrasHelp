function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    // add a zero in front of numbers<10
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('time').innerHTML = today.getDate() +
        "/" + (today.getMonth() + 1) + "/" + today.getFullYear() + " à " + h + ":" + m + ":" + s;
    t = setTimeout(function () {
        startTime()
    }, 500);
}

startTime();


$('a[href*="#"]').on('click', function (e) {
    e.preventDefault()

    $('html, body').animate(
        {
            scrollTop: $($(this).attr('href')).offset().top,
        },
        500,
        'linear'
    )
})