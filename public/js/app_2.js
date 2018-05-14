$('#menu-btn').click(function() {    
    $('#mobile-side-menu').show();
});

$('#back-btn').click(function() {
    $('#mobile-side-menu').hide();
});

var is_clicked_account = false;
$('a.dropdown-toggle').click( function(){
    is_clicked_account = !is_clicked_account;
    if (is_clicked_account) {
        $('li.admin-top-menu').addClass('open');
    } else {
        $('li.admin-top-menu').removeClass('open');
    }
});