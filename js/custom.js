$(document).ready(function() {
    if (Modernizr.history) {
        history.replaceState({
            myTag: true
        }, null, window.location.href);
    }
    // Form 1 submission
    $('#success').hide();
    $('#error').hide();
    $(".errors").hide();


    $(document).on('submit','#remove_photo',function(evt) {
        event.preventDefault(); // Prevent the default form submission

        var $form = jQuery("#remove_photo");
        var formData = new FormData($form[0]); // Create FormData object from the form
        formData.append("remove_photo", "remove");
        console.log(...formData.entries());
        // Send AJAX request
        $.ajax({
            url: 'profile_sub.php',
            type: 'POST',
            data: formData,
            contentType: false, // Don't set contentType
            processData: false, // Don't process data
            success: function(response) {
                console.log(response);
                if (response.success) {
                    console.log("data saved111", response); // Display response
                    // alert("photo removed!!");
                    // $("#RemoveImageModal").modal("show");
                    if (Modernizr.history) {
                        var _href = window.location.href;
                        loadContent(_href);
                        history.pushState({
                            myTag: true
                        }, null, _href);
                        return false;
                    } else
                        window.location.href = _href;
                    // $('#default_photo').attr('src', 'uploads/default_photo.jpg');
                    // console.log($('#default_photo'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Request failed with status code', xhr.status);
            }
        });
    });



    // Form 2 submission
    $(document).on('submit','#profile_form',function(evt) {
        console.log(0);
        evt.preventDefault();
        // console.log(1);

        $(".errors").hide();
        var _href = window.location.href;
        // console.log(_href);
        // evt.preventDefault(); // Prevent the default form submission
        if (validateForm()) {
            var $form = jQuery("#profile_form");
            var formData = new FormData($form[0]);
            formData.append("update", "update");
            console.log(...formData.entries());
            // Send AJAX request
            formSubmit(formData);
        }
    });
});


function formSubmit(formData) {
    $.ajax({
        url: 'profile_sub.php',
        type: 'POST',
        data: formData,
        dataType: "json",
        contentType: false, // Don't set contentType
        processData: false, // Don't process data
        success: function(response) {
            console.log(response);
            // console.log(typeof  response);
            if (response.success) {
                console.log("data saved", response); // Display response
                $("#successModal").modal("show");
                if (Modernizr.history) {
                    var _href = window.location.href;
                    loadContent(_href);
                    history.pushState({
                        myTag: true
                    }, null, _href);
                    return false;
                }
                // else
                // window.location.href = _href;
            } else {
                console.log(response.errors);
                for (var key in response.errors) {
                    console.log(key + ': ' + response.errors[key]);
                    $('#er' + key).show().html(response.errors[key]).css("color", "red");
                }

                // }
            }
        },
        error: function(xhr, status, error) {
            console.error('Request failed with status code', xhr.status);
        }
    });
}

function loadContent(_href) {
    jQuery.ajax({
        type: 'post',
        url: _href,
        success: function(data) {
            // console.log(data);
            var data1 = jQuery(data).filter(".mpage-container").html();
            console.log(_href);
            console.log(data1);
            if (typeof(data1) == "undefined") {
                data1 = jQuery(".mpage-container > *", data);
            }
            jQuery(".mpage-container").html(data1);
            unsaved = false;
            // if (callback && typeof callback == "function") {
            //     callback(data);
            // }
        }
    });
}

function validateForm() {
    var c = true;
    var fname = $('#fname').val();
    var lname = $('#lname').val();
    var photo = $('#photo').val();
    var dob = $('#dob').val();
    var psword = $('#psword').val();
    var chngpsword = $('#chngpsword').val();
    if (fname.length == 0) {
        $("#erfname").show().html("* First name is required.").css("color", "red");
        c = false;
    }
    if (lname.length == 0) {
        $("#erlname").show().html("* Last name is required.").css("color", "red");
        c = false;
    }

    if (photo.length != 0) {
        var ext = photo.split('.');
        ext = ext.pop();
        ext = ext.toLowerCase();
        var allowed = ['jpg', 'png', 'jpeg'];

        if (!allowed.includes(ext)) {
            $("#erphoto").show().html("* Invalid image type.").css("color", "red");
            c = false;
        }
    }
    dob = new Date(dob);
    var date = new Date();
    console.log(dob);
    console.log(date);
    if (isNaN(dob.getTime())) {
        $("#erdob").show().html("* Invalid dob.").css("color", "red");
        c = false; // Invalid date format
    } 
    else if (dob > date) 
    {
        $("#erdob").show().html("* Dob should be less than the current date.").css("color", "red");
        c = false;
    }
    if (psword != '' && chngpsword == '') {
        $("#erchngpsword").show().html("* New password required.").css("color", "red");
        c = false;
    } else if (psword == '' && chngpsword != '') {

        $("#erpsword").show().html("* Current password required.").css("color", "red");
        c = false;
    }


    return c;
}