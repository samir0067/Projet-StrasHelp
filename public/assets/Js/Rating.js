// $(document).ready();

function rateUser(data) {
    // $('.ratingStar').on('click touchstart', function () {
    //     var value = parseInt($(this).attr('data-value'));
    //     var target = parseInt($(this).attr('data-target'));
    var result = JSON.stringify(data);
    // var params = {
    //     rating: value,
    //     target: target
    // };
    post('', {rating: data});
    // $.ajax({
    //     url: '/rating/add',
    //     type: 'POST',
    //     data: params,
    //     dataType: 'json',
    //     success: function (response) {
    //
    //         alert('Vote enregistré');
    //     }
    // });

    // });
}


/**
 * sends a request to the specified url from a form. this will change the window location.
 * @param {string} path the path to send the post request to
 * @param {object} params the paramiters to add to the url
 * @param {string} [method=post] the method to use on the form
 */

function post(path, params, method = 'post') {
    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    const form = document.createElement('form');
    form.method = method;
    form.action = path;

    for (const key in params) {
        if (params.hasOwnProperty(key)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];

            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
}