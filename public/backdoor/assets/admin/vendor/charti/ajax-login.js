jQuery(document).ready(function($) {

    // Show the login dialog box on click
    // $('a#show_login').on('click', function(e){
    //     $('body').prepend('<div class="login_overlay"></div>');
    //     $('form#login-ajax').fadeIn(500);
    //     $('div.login_overlay, form#login-ajax a.close').on('click', function(){
    //         $('div.login_overlay').remove();
    //         $('form#login-ajax').hide();
    //     });
    //     e.preventDefault();
    // });

    // Perform AJAX login on form submit
    $('form#login-ajax').on('submit', function(e){
        $('form#login-ajax p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'log': $('form#login-ajax #user_login').val(), 
                'password': $('form#login-ajax #password').val(), 
                'security': $('form#login-ajax #security').val() },
            success: function(data){
                $('form#login-ajax p.status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

});