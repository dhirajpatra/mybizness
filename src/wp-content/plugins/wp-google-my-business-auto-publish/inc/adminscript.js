jQuery(document).ready(function ($) {
    
    function readmoreActivation(){
        $('.post-content').readmore({
                moreLink: '<a style="padding: 6px 12px;" href="#"><span class="read-more-blur"></span>Read More</a>', // (raw HTML)
                lessLink: '<a style="padding: 6px 12px;" href="#">Read Less</a>', // (raw HTML)
//                sectionCSS: 'display: block; width: 100%;', // (sets the styling of the blocks)
                heightMargin: 16, // (in pixels, avoids collapsing blocks that are only slightly larger than maxHeight)
                collapsedHeight: 80

        }); 
    }
    readmoreActivation();
    
    //make help area content into an accordion
    $("#accordion").accordion({
        collapsible: true,
        autoHeight: false,
        heightStyle: "content",
        speed: "fast",
        active: false,
    });
    
    //make tabs tabs
    $("#tabs").tabs({
        activate: function (event, ui) {
            
            readmoreActivation();
        }
    });

    //make links go to particular tabs
    $('.wrap').on("click", ".open-tab", function () {
        var tab = $(this).attr('href');
        var index = $(tab).index() - 1;
        $('#tabs').tabs({
            active: index
        });
        $('#wp_google_my_business_auto_publish_tab_memory').val(tab);
    });
    
    //add link to hidden link setting when a tab is clicked
    $('.wrap').on("click", ".nav-tab", function () {
        var tab = $(this).attr('href');
        $('#wp_google_my_business_auto_publish_tab_memory').val(tab);
    });
    


    //hides and then shows on click help tooltips
    $(".hidden").hide();
    $('#google_my_business_auto_publish_settings_form').on("click", ".google_business_settings_row  i", function (event) {
//        console.log('I was clicked');        
        event.preventDefault();
        $(this).next(".hidden").slideToggle();
    });

    
    //load previous tab when opening settings page
    if($('#wp_google_my_business_auto_publish_tab_memory').length) {
        if($('#wp_google_my_business_auto_publish_tab_memory').val().length > 1) {

        var tab = $('#wp_google_my_business_auto_publish_tab_memory').val();  

        var index = $(tab).index() - 1;
        $('#tabs').tabs({
            active: index
        });
        }
    }
    
        

    


    //adds button text to text area
    $('.google_business_append_buttons').click(function () {
        $(this).parent().next().children().val($(this).parent().next().children().val() + $(this).attr("value"));
        $(this).parent().next().children().focus();
    });



    
    
    //code to manage dont share categories

    var selectedDontShareCategories = $('#wp_google_my_business_auto_publish_dont_share_categories').val();

    if (selectedDontShareCategories != null) {
        var selectedDontShareCategoriesArray = selectedDontShareCategories.split(',');
    }

    $(".dont-share-checkbox").each(function () {

        if ($.inArray($(this).attr('id'), selectedDontShareCategoriesArray) != -1) {
            $(this).prop('checked', true);
        }

        $(this).change(function () {

            if ($(this).is(":checked")) {

                selectedDontShareCategoriesArray.push($(this).attr('id'));

                $('#wp_google_my_business_auto_publish_dont_share_categories').val(selectedDontShareCategoriesArray.join());

            } else {
                selectedDontShareCategoriesArray.splice($.inArray($(this).attr('id'), selectedDontShareCategoriesArray), 1);
                $('#wp_google_my_business_auto_publish_dont_share_categories').val(selectedDontShareCategoriesArray.join());
            }

        }); //end change function

    }); //end each function
    
    
    
    
    
    
    
    
    //code to manage share the following post types and pages

    var selectedPostTypesPages = $('#wp_google_my_business_auto_publish_dont_share_types').val();

    if (selectedPostTypesPages != null) {
        var selectedPostTypesPagesArray = selectedPostTypesPages.split(',');
    }

    $(".post-type-checkbox").each(function () {

        if ($.inArray($(this).attr('id'), selectedPostTypesPagesArray) != -1) {
            $(this).prop('checked', true);
        }

        $(this).change(function () {

            if ($(this).is(":checked")) {

                selectedPostTypesPagesArray.push($(this).attr('id'));

                $('#wp_google_my_business_auto_publish_dont_share_types').val(selectedPostTypesPagesArray.join());

            } else {
                selectedPostTypesPagesArray.splice($.inArray($(this).attr('id'), selectedPostTypesPagesArray), 1);
                $('#wp_google_my_business_auto_publish_dont_share_types').val(selectedPostTypesPagesArray.join());
            }

        }); //end change function

    }); //end each function
    
    
    
    
    
    
    
    //save settings using ajax    
    $('#google_my_business_auto_publish_settings_form').submit(function(event) {
        
        event.preventDefault();
        //we need to check whether the boards tab is active and if it is we are going to do some magic first
        
        var activeTab = $('.ui-tabs-active a').attr('href');
        
        //if the current tab is the account and they save the settings lets take them to the location tab.
        
        if(activeTab == '#googleAccountSelect'){
            $('#wp_google_my_business_auto_publish_tab_memory').val('#googleLocationSelect');
            //we need to delete existing account and locations transients         
            var data = {
                    'action': 'delete_transient',
                    'transientName': 'wp_google_my_business_auto_publish_accounts',
                    };

            jQuery.post(ajaxurl, data, function (response) {
            });
            var data = {
                    'action': 'delete_transient',
                    'transientName': 'wp_google_my_business_auto_publish_locations',
                    };

            jQuery.post(ajaxurl, data, function (response) {
            });
            
            
            
            
        }
        
        if(activeTab == '#googleConnect'){
            $('#wp_google_my_business_auto_publish_tab_memory').val('#googleAccountSelect'); 
            //we need to delete existing locations transients
            var data = {
                    'action': 'delete_transient',
                    'transientName': 'wp_google_my_business_auto_publish_locations',
                    };

            jQuery.post(ajaxurl, data, function (response) {
            });
        }
        
        if(activeTab == '#googleLocationSelect'){
            $('#wp_google_my_business_auto_publish_tab_memory').val('#googleBusinessSharingOptionsPage');     
        }
        
        
        
        
        
        
        $('<div class="notice notice-warning is-dismissible settings-loading-message"><p><i class="fa fa-spinner" aria-hidden="true"></i> Please wait while we save the settings...</p></div>').insertAfter('.google-business-save-settings');
        
        //tinyMCE.triggerSave();

        $(this).ajaxSubmit({
            success: function(){

                $('.settings-loading-message').remove();

                $('<div class="notice notice-success is-dismissible settings-saved-message"><p>The settings have been saved.</p></div>').insertAfter('.google-business-save-settings');

                setTimeout(function() {
                    $('.settings-saved-message').slideUp();
                    
                    if(activeTab == '#googleAccountSelect' || activeTab == '#googleConnect' || activeTab == '#googleLocationSelect'){
                        location.reload();         
                    }
                    
                    
                }, 3000);
                
                
//                if(activeTab == "Integrations "){
//                    location.reload();
//                }

                

            }
        });

        return false; 

        $('.settings-loading-message').remove();

    });
    
    
    
    
    
    function getCurrentPageUrl(){
        
        var currentPage = window.location.href;
   
        //the following 3 variables are used to create the redirect URL
        var pluginName = "wp_google_my_business_auto_publish";
        var findWhereParamatersStart = currentPage.indexOf(pluginName);
        var redirect = currentPage.substr(0,findWhereParamatersStart+pluginName.length);
        
        return redirect;
        
    }
    
    
    
    //common function to get query parameters from url
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    
    
    //runs get access token ajax
    //get current page
    var currentPage = window.location.href;
    //this is what to look for in the URL to run the javascript on
    var pageCheck = "code=";
    
    if (currentPage.indexOf(pageCheck) !== -1) {
        
        
        
        var code = getParameterByName('code');
        
        //console.log(code);
        
        
        jQuery.ajax({
            url: "https://www.googleapis.com/oauth2/v4/token?" + jQuery.param({
                "code": code,
                "client_id": "979275334189-mqphf6kpvpji9km7i6pm0sq5ddvfoa60.apps.googleusercontent.com",
                "client_secret": "hENqfr4whG7qs5QxSSzOa9_s",
                "redirect_uri": "https://northernbeacheswebsites.com.au/redirectgoogle/",
                "grant_type": "authorization_code",
            }),
            type: "POST",
        })
        .done(function(data, textStatus, jqXHR) {
            
            console.log(data);
            
            //save the access token
            var accessToken = data.access_token;
            var refreshToken = data.refresh_token;
                        
            var data = {
                    'action': 'save_authentication_details',
                    'accessToken': accessToken,
                    'refreshToken': refreshToken,
                    };

            jQuery.post(ajaxurl, data, function (response) {
                
                console.log(response);
                
                if(response == 'SUCCESS'){
                    
                    
                    $('#wp_google_my_business_auto_publish_tab_memory').val('#googleAccountSelect');
                    
                    
                    
                    $('#google_my_business_auto_publish_settings_form').ajaxSubmit({
                        success: function(){
                        }
                    });
                    
                    
                    
                    //the call was succesful
                    $('<div class="notice notice-info is-dismissible"><p>The connection to your Google account has been successful. You now need to select your Google account, we will take you there in <span id="countdown"></span></p></div>').insertAfter('#gmb-authentication');
                    
                    
                        var count = 9;
                      var countdown = setInterval(function(){
                        $("#countdown").html(count);
                        if (count == 0) {
                          clearInterval(countdown);
                          window.location.href = getCurrentPageUrl();

                        }
                        count--;
                      }, 1000);
                    
 
                    
                } else {
                    //we ran into an issue
                $('<div class="notice notice-error is-dismissible"><p>Something went wrong, please try again later or if the issue persists please create a support ticket.</p></div>').insertAfter('#gmb-authentication');     
                    
                }
                
                
                
                
            });

            
            
            
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
        })
        .always(function() {
            /* ... */
        });

        
 
        
    }
    
    
    
    
    
    
    
    
     //makes image upload field 
   $('#wpwrap').on("click","#upload-btn", function(event){
        event.preventDefault();
       
        //console.log('I was clicked');
        
        var previousInput = $(this).prev(); 
       
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            
            previousInput.val(image_url);
            
            
            //update user profile image with new image
            
            $('#imagePreview').attr('src',image_url);
            $('#imagePreview').show();
            

        });
    });
    
    //make date time inputs
    //make event start and end a time and date picker
    $('.date-time-input').datetimepicker({
        format: 'MMM D, YYYY HH:mm',
        icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-plus',
            down: 'fa fa-minus',
            next: 'fa fa-chevron-right',
            previous: 'fa fa-chevron-left'
        }
    });
    
    
    
    function showAndHideEventInputsBasedOnCheckbox(){
        
        $( ".event-enable" ).each(function() {
            if(!$(this).prop('checked') == true){
                
                $(this).parent().parent().find('.event-option').hide();
                
                //$('.event-option').hide(); 
            } else {
                $(this).parent().parent().find('.event-option').show();
                
                //$('.event-option').show();       
            }  
            
        });
    }
    
    showAndHideEventInputsBasedOnCheckbox();
    
    //shows the event options based on checkbox
    $('#wpwrap').on("change",".event-enable", function(event){
        showAndHideEventInputsBasedOnCheckbox();  
    });
    
    
//    $('#link-chooser').click(function(){
//        console.log('something happened'); 
//        
//        //var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";
//        
//        wpLink.open('link-chooser'); 
//        
//        
//    });
       
    $('#wpwrap').on("click","#post-now", function(event){
        event.preventDefault();
        
        
        
        var postContent = $('.post-to-google-form #post-content').val();
        var postLink = $('.post-to-google-form #post-link').val();
        var postAction = $('.post-to-google-form #post-action').val();
        var postImage = $('.post-to-google-form #post-image').val();
        
        if($('.post-to-google-form #event-enable').is(":checked")){
            var eventEnable ='true';
        } else {
            var eventEnable ='false';    
        }
        
        var eventName = $('.post-to-google-form #event-name').val();
        var eventStart = $('.post-to-google-form #event-start').val();
        var eventEnd = $('.post-to-google-form #event-end').val();
        
//        console.log(postContent);
//        console.log(postLink);
//        console.log(postAction);
//        console.log(postImage);
//        console.log(eventEnable);
//        console.log(eventName);
//        console.log(eventStart);
//        console.log(eventEnd);
        
        //if there's no content alert the user
        if(postContent.length < 1){
            
            $('.post-send-error-message').remove();
            
            $('<div class="notice notice-error is-dismissible post-send-error-message"><p>The post needs to have some content.</p></div>').insertAfter('#post-now');

            setTimeout(function() {
                $('.post-send-error-message').slideUp();
            }, 6000); 
            
            return;
        }
        
        var locations = '';
        
        //get locations
        $( "#post-now-locations-list li" ).each(function( index ) {
            
            if($(this).hasClass('selected')){
                
                locations += $(this).attr('data')+',';       
                
            }
            
        });
        
        
        //if theres no location selected dont do anything and show a warning
        if(locations == ''){
            $('.post-send-error-message').remove();
            
            $('<div class="notice notice-error is-dismissible post-send-error-message"><p>You need to share to at least one location.</p></div>').insertAfter('#post-now');

            setTimeout(function() {
                $('.post-send-error-message').slideUp();
            }, 6000); 
            
            return;    
            
        } else {
            locations = locations.replace(/,\s*$/, "");    
        }
        
        
        //console.log(locations);
        
        var data = {
                    'action': 'post_now_to_google',
                    'postContent': postContent,
                    'postLink': postLink,
                    'postAction': postAction,
                    'postImage': postImage,
                    'eventEnable': eventEnable,
                    'eventName': eventName,
                    'eventStart': eventStart,
                    'eventEnd': eventEnd,
                    'locations': locations,

                    };
        
        
        
        
        
        $('<div class="notice notice-warning is-dismissible post-send-loading-message"><p><i class="fa fa-spinner" aria-hidden="true"></i> Please wait while we send the post to Google...</p></div>').insertAfter('#post-now');
        
        
        jQuery.post(ajaxurl, data, function (response) {
            
            console.log(response);  
            
            
            $('.post-send-loading-message').remove();
            
            if(response !== 'ERROR'){
                
                var urlMessage = '';   
                
                var responseAsArray = response.split('||');
                
                
                for (var i = 0; i < responseAsArray.length; i++) {
                    
                    var splitUrlAndName = responseAsArray[i].split('|'); 
                    
                    urlMessage += '<a target="_blank" href="'+splitUrlAndName[0]+'">View post ('+splitUrlAndName[1]+')</a> '
                    
                }
                
                
                $('<div class="notice notice-success is-dismissible post-send-success-message"><p>The post has been sent to Google. '+urlMessage+'</p></div>').insertAfter('#post-now');

                setTimeout(function() {
                    $('.post-send-success-message').slideUp();
                }, 9000);
                
            } else {
                
                $('<div class="notice notice-error is-dismissible post-send-error-message"><p>There has been an error, please check your form input and make sure you are currently authenticated.</p></div>').insertAfter('#post-now');

                setTimeout(function() {
                    $('.post-send-error-message').slideUp();
                }, 6000);    
                
            }   
            
            
            

            
            

        });

        
    });
    
    
    
    
    //show number of characters remaining on form description area
    $('.post-to-google-form textarea').on('keyup', function() {
//        console.log('I was pressed');
        
        var valueOfDescription = $(this).val();
        var lengthOfDescription = valueOfDescription.length;
        
        //only show warning if we are getting close to the limit
        if(lengthOfDescription>1000){
            
            $('.textarea-counter').css( "display", "block" );
            
            //first lets empty any existing value
            $('.counter-number').empty();

            $('.counter-number').append(lengthOfDescription);

            if(lengthOfDescription >= 1500){
                $('.textarea-counter').addClass('too-many-characters-warning');   
            } else {
                $('.textarea-counter').removeClass('too-many-characters-warning');        
            }
        } else {
            
            $('.textarea-counter').hide();    
        }
        
    });
    
    
    
    //delete google post
    $('#wpwrap').on("click",".delete-google-post", function(event){
        event.preventDefault();
        
        var thisRow = $(this);
        
        
        var confirmationBox = confirm("Are you sure you want to delete this post?");
        if (confirmationBox == true) {

            var postID = $(this).attr('data');
            //console.log(postID);


            var data = {
                        'action': 'delete_post_on_google',
                        'postID': postID,
                        };


            jQuery.post(ajaxurl, data, function (response) {
                console.log(response);  
                
                if($('.edit-google-post-row').length){
                    $('.edit-google-post-row').remove();    
                }
                
                thisRow.parent().parent().slideUp();

            });
            
            
        } //end confirmation 

    });
    
    
    //hide welcome message
    $('#wpwrap').on("click","#google-my-business-message .notice-dismiss", function(event){
        
        event.preventDefault();
        
        var pluginVersion = $(this).parent().attr('data-version');
        
        $('#wp_google_my_business_auto_publish_dismiss_welcome_message').val(pluginVersion);
        
//        $('#google_my_business_auto_publish_settings_form').ajaxSubmit({
//            success: function(){
//            }
//        });
        
        console.log(pluginVersion);
        

        var data = {
                    'action': 'dismiss_welcome_message',
                    'pluginVersion': pluginVersion,
                    };


        jQuery.post(ajaxurl, data, function (response) {
            console.log(response);  

        });
            
            
    

    });
    
    
    
    //edit google post
    $('#wpwrap').on("click",".edit-google-post", function(event){
        event.preventDefault();
        
        var thisEditButton = $(this);
        
        //remove current or other edit rows when clicking an edit row so it works as like a toggle function
        if($('.edit-google-post-row').length){
            $('.edit-google-post-row').remove();    
        } else {
            
            var postID = $(this).attr('data');

            //get default variables
            var postContent = $(this).attr('data-postContent');
            var postLink = $(this).attr('data-postLink');
            var postAction = $(this).attr('data-postAction');
            var postImage = $(this).attr('data-postImage');
            var makeAnEvent = $(this).attr('data-makeAnEvent');
            var eventName = $(this).attr('data-eventName');
            var eventStart = $(this).attr('data-eventStart');
            var eventEnd = $(this).attr('data-eventEnd');
            
            
            var data = {
                    'action': 'get_post_form',
                    'postContent': postContent,
                    'postLink': postLink,
                    'postAction': postAction,
                    'postImage': postImage,
                    'makeAnEvent': makeAnEvent,
                    'eventName': eventName,
                    'eventStart': eventStart,
                    'eventEnd': eventEnd,
                    };


            jQuery.post(ajaxurl, data, function (response) {
                
                response += '<button data-image="'+postImage+'" data="'+postID+'" style="margin: 0px !important; margin-right: 15px !important; margin-bottom: 15px !important; margin-top: 15px !important;" id="update-now" class="button button-primary"><i class="fa fa-check-square" aria-hidden="true"></i> Update Post</button>';
                
                response += '<button style="margin-bottom: 15px !important; margin-top: 15px !important;" id="cancel-now" class="button button-secondary"><i class="fa fa-ban" aria-hidden="true"></i> Cancel</button>';
                
                thisEditButton.parent().parent().after('<tr class="edit-google-post-row"><td colspan="7">'+response+'</td></tr>');
                
                
                
                showAndHideEventInputsBasedOnCheckbox(); 
                

            });
            

        }
        

    });
  
    
    $('#wpwrap').on('focus',".date-time-input", function(){        
        $(this).datetimepicker({
            format: 'MMM D, YYYY HH:mm',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-plus',
                down: 'fa fa-minus',
                next: 'fa fa-chevron-right',
                previous: 'fa fa-chevron-left'
            }
        });
        
    });
    
    $('#wpwrap').on("click","#cancel-now", function(event){
        $('.edit-google-post-row').remove();    
    });
    
    
    
    
    
    
    
    
    
    $('#wpwrap').on("click","#update-now", function(event){
        event.preventDefault();
        
        var confirmationBox = confirm("Are you sure you want to update this post?");
        if (confirmationBox == true) {
        
            
            var postID = $(this).attr('data');
            var originalPostImage = $(this).attr('data-image');
            var postContent = $('.edit-google-post-row #post-content').val();
            var postLink = $('.edit-google-post-row #post-link').val();
            var postAction = $('.edit-google-post-row #post-action').val();
            var postImage = $('.edit-google-post-row #post-image').val();
            //var postImageName = $('.edit-google-post-row #post-image').val();

            if($('.edit-google-post-row #event-enable').is(":checked")){
                var eventEnable ='true';
            } else {
                var eventEnable ='false';    
            }

            var eventName = $('.edit-google-post-row #event-name').val();
            var eventStart = $('.edit-google-post-row #event-start').val();
            var eventEnd = $('.edit-google-post-row #event-end').val();
            
//            console.log(postID);
//            console.log(postContent);
//            console.log(postLink);
//            console.log(postAction);
//            console.log(postImage);
//            console.log(eventEnable);
//            console.log(eventName);
//            console.log(eventStart);
//            console.log(eventEnd);

            //if there's no content alert the user
            if(postContent.length < 1){

                $('.post-send-error-message').remove();

                $('<div class="notice notice-error is-dismissible post-send-error-message"><p>The post needs to have some content.</p></div>').insertAfter('#update-now');

                setTimeout(function() {
                    $('.post-send-error-message').slideUp();
                }, 6000); 

                return;
            }




            var data = {
                        'action': 'update_google_post',
                        'postID': postID,
                        'postContent': postContent,
                        'postLink': postLink,
                        'postAction': postAction,
                        'originalPostImage': originalPostImage,
                        'postImage': postImage,
                        'eventEnable': eventEnable,
                        'eventName': eventName,
                        'eventStart': eventStart,
                        'eventEnd': eventEnd,

                        };

            $('<div class="notice notice-warning is-dismissible post-send-loading-message"><p><i class="fa fa-spinner" aria-hidden="true"></i> Please wait while we update the post...</p></div>').insertAfter('#post-update');


            jQuery.post(ajaxurl, data, function (response) {

                console.log(response);  


                $('.post-send-loading-message').remove();

                if(response !== 'ERROR'){

                    $('<div class="notice notice-success is-dismissible post-send-success-message"><p>The post has been sent to Google. View it <a href="'+response+'" target="_blank">here</a>.</p></div>').insertAfter('#post-update');
                        
                    setTimeout(function() {
                        $('.post-send-success-message').slideUp();
                    }, 6000);
                    
                    //reload the page so we can display the updated info
                    location.reload();
                    

                } else {

                    $('<div class="notice notice-error is-dismissible post-send-error-message"><p>There has been an error, please check your form input and make sure you are currently authenticated.</p></div>').insertAfter('#post-update');

                    setTimeout(function() {
                        $('.post-send-error-message').slideUp();
                    }, 6000);    

                }   


            });
        }
        
    });
    
    
    //toggle locations for location selection
    $('#wpwrap').on("click",".location-list-item", function(event){
        event.preventDefault();
        
        var valueOfSetting = $('#wp_google_my_business_auto_publish_location_selection').val();
        
        var locationId = $(this).attr('data');
        
        if($(this).hasClass('selected')){
            
            //we need to remove the item
            
            var itemSelected = true;
            
            $(this).removeClass('selected');
            
            $(this).find('.location-selected-icon').removeClass('fa-check-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-times-circle-o');
            
            
            var settingAsAnArray = valueOfSetting.split(',');
            var positionInArray = settingAsAnArray.indexOf(locationId);
            if (positionInArray > -1) {
                settingAsAnArray.splice(positionInArray, 1);
            }
            
            var newSettingValue = settingAsAnArray.join(",");
            
            
            $('#wp_google_my_business_auto_publish_location_selection').val(newSettingValue);
            
        } else {
            
            //we need to add the item
            
            var itemSelected = false;  
            
            $(this).addClass('selected');
            $(this).find('.location-selected-icon').removeClass('fa-times-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-check-circle-o');
            
            if(valueOfSetting == ''){
                $('#wp_google_my_business_auto_publish_location_selection').val(locationId);    
            } else {
                $('#wp_google_my_business_auto_publish_location_selection').val(valueOfSetting+','+locationId);      
            }
            

            
        }
        
        //lets change the class
        
        
        //console.log(itemSelected);
        
        
    });
    
    
    //toggle locations for default location selection
    $('#wpwrap').on("click","#default-locations-list .location-list-item-small", function(event){
        event.preventDefault();
        
        var valueOfSetting = $('#wp_google_my_business_auto_publish_default_locations').val();
        
        var locationId = $(this).attr('data');
        
        if($(this).hasClass('selected')){
            
            //we need to remove the item
            
            var itemSelected = true;
            
            $(this).removeClass('selected');
            
            $(this).find('.location-selected-icon').removeClass('fa-check-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-times-circle-o');
            
            
            var settingAsAnArray = valueOfSetting.split(',');
            var positionInArray = settingAsAnArray.indexOf(locationId);
            if (positionInArray > -1) {
                settingAsAnArray.splice(positionInArray, 1);
            }
            
            var newSettingValue = settingAsAnArray.join(",");
            
            
            $('#wp_google_my_business_auto_publish_default_locations').val(newSettingValue);
            
        } else {
            
            //we need to add the item
            
            var itemSelected = false;  
            
            $(this).addClass('selected');
            $(this).find('.location-selected-icon').removeClass('fa-times-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-check-circle-o');
            
            if(valueOfSetting == ''){
                $('#wp_google_my_business_auto_publish_default_locations').val(locationId);    
            } else {
                $('#wp_google_my_business_auto_publish_default_locations').val(valueOfSetting+','+locationId);      
            }
            

            
        }
        
    });
    
    
    
    //toggle locations for post now location selection
    $('#wpwrap').on("click","#post-now-locations-list .location-list-item-small", function(event){
        event.preventDefault();
                
        var locationId = $(this).attr('data');
        
        if($(this).hasClass('selected')){
            
            //we need to remove the item
            $(this).removeClass('selected');
            $(this).find('.location-selected-icon').removeClass('fa-check-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-times-circle-o');
             
        } else {
            
            //we need to add the item
            $(this).addClass('selected');
            $(this).find('.location-selected-icon').removeClass('fa-times-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-check-circle-o');

        }


    });


    //toggle location of reviews
    $('#wpwrap').on("click",".review-list-item", function(event){
        event.preventDefault();
        
        var valueOfSetting = $('#wp_google_my_business_auto_publish_hide_reviews').val();
        
        var reviewId = $(this).attr('data');
        
        if(!$(this).hasClass('selected')){
            
            //the item is currently hidden so we need to remove the review from the array
            
            var itemSelected = true;
            
            $(this).addClass('selected');
            

      

            $(this).find('.review-selected-icon').removeClass('fa-eye-slash');
            $(this).find('.review-selected-icon').addClass('fa-eye');
            
            
            var settingAsAnArray = valueOfSetting.split(',');
            var positionInArray = settingAsAnArray.indexOf(reviewId);
            if (positionInArray > -1) {
                settingAsAnArray.splice(positionInArray, 1);
            }
            
            var newSettingValue = settingAsAnArray.join(",");
            
            
            $('#wp_google_my_business_auto_publish_hide_reviews').val(newSettingValue);
            
        } else {
            
            //we need to add the item
            
            //console.log('I was ran');

            var itemSelected = false;  
            
            
            $(this).removeClass('selected');
            $(this).find('.review-selected-icon').removeClass('fa-eye');
            $(this).find('.review-selected-icon').addClass('fa-eye-slash');
            
            if(valueOfSetting == ''){
                $('#wp_google_my_business_auto_publish_hide_reviews').val(reviewId);    
            } else {
                $('#wp_google_my_business_auto_publish_hide_reviews').val(valueOfSetting+','+reviewId);      
            }

        }
        
        //lets change the class
        
        
        //console.log(itemSelected);
        
        
    });
    
   
    
    function readmoreActivationSlick(){
        $('.review-comment-readmore').readmore({
                moreLink: '<a class="read-more-link" href="#">Read more...</a>', // (raw HTML)
                lessLink: '<a class="read-less-link" href="#">Read less</a>', // (raw HTML)
//                sectionCSS: 'display: block; width: 100%;', // (sets the styling of the blocks)
                heightMargin: 16, // (in pixels, avoids collapsing blocks that are only slightly larger than maxHeight)
                collapsedHeight: 36

        }); 
    }
    

    
    

    function shortcodeConstruction(){

        //location
        var location = $('.options .location').val();
        //type
        var type = $('.options .type').val();
        //minimum stars
        var minimumStars = $('.options .minimum-stars').val();
        //sort by
        var sortBy = $('.options .sort-by').val();
        //sort order
        var sortOrder = $('.options .sort-order').val();
        //amount of reviews
        var amountOfReviews = $('.options .review-amount').val();
        //visible slides
        var visibleSlides = $('.options .slides-page').val();
        //slides to scroll
        var slidesScroll = $('.options .slides-scroll').val();
        //autoplay
        if($('.options .autoplay').is(':checked')){
            var autoplay = 'true';
        } else {
            var autoplay = 'false';
        }
        //autoplay speed
        var autoplaySpeed = $('.options .speed').val();

        //transition
        var transition = $('.options .transition').val();



        //read more
        if($('.options .read-more').is(':checked')){
            var readMore = 'true';
        } else {
            var readMore = 'false';
        }

        //show stars
        if($('.options .show-stars').is(':checked')){
            var showStars = 'true';
        } else {
            var showStars = 'false';
        }

        //show date
        if($('.options .show-date').is(':checked')){
            var showDate = 'true';
        } else {
            var showDate = 'false';
        }
        //show quote
        if($('.options .show-quotes').is(':checked')){
            var showQuote = 'true';
        } else {
            var showQuote = 'false';
        }


        //put options into input field
        $('.shortcode-input').val('[gmb-review location="'+location+'" type="'+type+'" minimum-stars="'+minimumStars+'" sort-by="'+sortBy+'" sort-order="'+sortOrder+'" review-amount="'+amountOfReviews+'" slides-page="'+visibleSlides+'" slides-scroll="'+slidesScroll+'" autoplay="'+autoplay+'" speed="'+autoplaySpeed+'" transition="'+transition+'" read-more="'+readMore+'" show-stars="'+showStars+'" show-date="'+showDate+'" show-quotes="'+showQuote+'"]');


        //$location,$type,$minimumStars,$sortBy,$sortOrder,$reviewAmount,$slidesPage,$slidesScroll,$autoplay,$speed,$transition,$readMore,$showStars,$showDate,$showQuotes

        //do ajax call to update preview
        var data = {
            'action': 'update_shortcode_preview',
            'location': location,
            'type': type,
            'minimumStars': minimumStars,
            'sortBy': sortBy,
            'sortOrder': sortOrder,
            'reviewAmount': amountOfReviews,
            'slidesPage': visibleSlides,
            'slidesScroll': slidesScroll,
            'autoplay': autoplay,
            'speed': autoplaySpeed,
            'transition': transition,
            'readMore': readMore,
            'showStars': showStars,
            'showDate': showDate,
            'showQuotes': showQuote,
            };

            jQuery.post(ajaxurl, data, function (response) {

                // console.log(response);

                $('#shortcode-preview').empty();

                $('#shortcode-preview').append(response);

                //instantiate slick slider
                $('.make-me-slick').slick();
                //instantiate readmore
                readmoreActivationSlick();

            });



        

    }

    //run function when any input is changed
    $(document).on('change', '.options select, .options input', function(event) { 
        shortcodeConstruction();   
    });
    

    //copy shortcode to clipboard on click
    $(document).on("click", "#copy-shortcode", function (event) {
        event.preventDefault();
        
        /* Get the text field */
        var copyText = document.getElementById("shortcode-input");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");

        $('#copy-shortcode').html('<i class="fa fa-check-circle-o" aria-hidden="true"></i> Copied'); 
        
        setTimeout(function() {
            
            $('#copy-shortcode').html('<i class="fa fa-clipboard" aria-hidden="true"></i> Copy Shortcode');     
        }, 2000);
  

    });

    //creates taxonomy item when add button is clicked
    $('.wrap').on("click","#clear-all-settings-button",function(event) {
        event.preventDefault();

        // console.log('hey');

        var confirmDeleteSettings = confirm("Are you sure you want to delete all plugin settings?");
        if (confirmDeleteSettings == true) {
            // console.log('continue');

            var data = {
                'action': 'delete_gmb_settings',
                };

            jQuery.post(ajaxurl, data, function (response) {

                console.log(response);

            });

        }



    });   
    
    
    

    
    
});