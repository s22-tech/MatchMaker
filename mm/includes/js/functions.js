'use strict';

function ahref_go(url) {
    window.location.assign(url);

  	// To allow multiple pages to work as an Add To Homescreen web app on the iPhone.
  	// This function MUST be on each page to work - it can't be included from an external file.
    // This technique is almost exactly the same as a full <a> page refresh, but it prevents Mobile Safari from jumping out of full-screen mode.

    // window.location.replace(url) prevents the back button from working.
}

function set_cookie(Name, Value) {
	var hours = 3;
	var expiration = new Date((new Date()).getTime() + hours * 3600000);
	var expires = expiration.toGMTString();
	var cookie = Name + '=' + Value + '; path=/; samesite=strict; expires=' + expires;
	document.cookie = cookie;
}
// set_cookie('window_width', window.innerWidth);


function dialog(message, yesCallback, noCallback) {
    $('.title').html(message);
    var dialog = $('#modal_dialog').dialog();

    $('#btnYes').click(function() {
        dialog.dialog('close');
        yesCallback();
    });
    $('#btnNo').click(function() {
        dialog.dialog('close');
        noCallback();
    });
}
// https://stackoverflow.com/questions/6929416/custom-confirm-dialog-in-javascript
// https://sweetalert.js.org/


