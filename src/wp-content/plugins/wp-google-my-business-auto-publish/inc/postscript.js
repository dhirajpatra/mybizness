jQuery(document).ready(function ($) {
    
    
    //make event start and end a time and date picker    
    $('.gmb-timedatepicker').datetimepicker({
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
    
    
    
    function hideAndShowEventOptionsOnPostPage(){
        if(!$("#make-an-event-checkbox").prop('checked') == true){
            $('.gmb-event').hide(); 
        } else {
            $('.gmb-event').show();       
        }       
    }
    
    
    
    //if checkbox is unchecked hide
    if($('#make-an-event-checkbox').length){
        hideAndShowEventOptionsOnPostPage();
    }
    
    
    
    $('#make-an-event-checkbox').change(function(){    
        hideAndShowEventOptionsOnPostPage();  
    });
    
    
    
    function hideAndShowAllOptionsOnPostPage(){
        if($("#dont-sent-to-google-checkbox").prop('checked') == true){
            $('.custom-google-metabox-setting').hide(); 
            $('.gmb-event').hide();
        } else {
            $('.custom-google-metabox-setting').show();
            hideAndShowEventOptionsOnPostPage();
        }      
    }
    
    
    //hide all share options if dont share this post is checked
    if($('#dont-sent-to-google-checkbox').length){
        hideAndShowAllOptionsOnPostPage();
    }
    
    
    
    $('#dont-sent-to-google-checkbox').change(function(){
        
        hideAndShowAllOptionsOnPostPage();
        
    });
    
    
    
    
    
//    //this below script makes sure to not share the post. this is activated when someone has configured the plugin settings this way
    if($('#dont-sent-to-google-checkbox').length){
        
        if($('#dont-sent-to-google-checkbox').attr('data') == 'dont-publish-by-default'){
            
            
            $('#dont-sent-to-google-checkbox').prop('checked', true);
            
            hideAndShowAllOptionsOnPostPage();
            
            var postID = $('.send-to-google').attr("data");
        
            var updatedShareMessage = $('#custom-share-message-google').val();

            var updatedButton = $('#custom-button').val();

            if ($('#dont-sent-to-google-checkbox').is(':checked')) {
                var updatedDontShareAction = "update";
            } else {
                var updatedDontShareAction = "delete";
            }


            var data = {
            'action': 'update_google_post_meta',
            'postID': postID,
            'updatedShareMessage': updatedShareMessage,
            'updatedButton': updatedButton,
            'updatedDontShareAction': updatedDontShareAction,
            };

            jQuery.post(ajaxurl, data, function(response) { 
            }); //end response 
            
            
            
            

        }
    }
 
    
    
    
    //share to google instantly when link is clicked
    $(document).on('click', '.send-to-google', function(event) { 
        event.preventDefault(); 
            
            $(this).after('<p style="color: blue; font-weight: bold;" class="google-share-sending-message">Sending...Please wait...</p>');
        
            //share to linkedin
            var thisLink = $(this);
            var postID = $(this).attr("data");
            
//            console.log(postID);
        
            //do request    
            var data = {
            'action': 'post_to_google',
            'postID': postID, 
            };

            jQuery.post(ajaxurl, data, function(response) {
                
                console.log(response);

                $('.google-share-sending-message').remove();
                
                if(response == "success"){
                    thisLink.after('<p style="color: green; font-weight: bold;" class="google-share-success-message">Successfully Shared!</p>');
                } else if(response == "no profile"){
                    thisLink.after('<p style="color: red; font-weight: bold;" class="google-share-success-message">We tried to send the post but no location is selected for this post.</p>');
                } else {
                    thisLink.after('<p style="color: orange; font-weight: bold;" class="google-share-success-message">There was an error: '+response+'</p>');
                }

                setTimeout(function() {
                    $('.google-share-success-message').slideUp();
                    }, 4000);


            }); //end response   
            

    }); //end button click
    
    
    
    
    
    
    //common function if any option changes
    $(document).on('change dp.change', '#custom-share-message-google, #custom-button, #dont-sent-to-google-checkbox, #make-an-event-checkbox, #event-title-google, #event-start-date-time, #event-end-date-time, #location-selection-google', function(event) { 
        

        //var itemChanged = $(this);
        
        var postID = $('.send-to-google').attr("data");
        
        var updatedShareMessage = $('#custom-share-message-google').val();
        
        var updatedButton = $('#custom-button').val();
        
        if ($('#dont-sent-to-google-checkbox').is(':checked')) {
            var updatedDontShareAction = "update";
        } else {
            var updatedDontShareAction = "delete";
        }
        
        
        if ($('#make-an-event-checkbox').is(':checked')) {
            var makeAnEventAction = "update";
        } else {
            var makeAnEventAction = "delete";
        }
        
        
        var eventTitle = $('#event-title-google').val();
        var eventStartDateTime = $('#event-start-date-time').val();
        var eventEndDateTime = $('#event-end-date-time').val();
        var locations = $('#location-selection-google').val();
        
        
        //console.log(eventStartDateTime);
        

        var data = {
        'action': 'update_google_post_meta',
        'postID': postID,
        'updatedShareMessage': updatedShareMessage,
        'updatedButton': updatedButton,
        'updatedDontShareAction': updatedDontShareAction,
        'makeAnEventAction': makeAnEventAction,
        'eventTitle': eventTitle,
        'eventStartDateTime': eventStartDateTime,
        'eventEndDateTime': eventEndDateTime,
        'locations': locations,
        };

        jQuery.post(ajaxurl, data, function(response) { 
            if(response == "success"){
                
                //$('.success-save-google').remove();
                
                $('.gmb-settings-saved').slideDown();
                
                //itemChanged.after('<p style="color: green; font-weight: bold;" class="success-save-google">Option updated!</p>');

                setTimeout(function() {
                $('.gmb-settings-saved').slideUp();
                }, 3000);

            }
        }); //end response  
  
        
    });
    
    
    
    
    
    //toggle locations for default location selection
    $('#wpwrap').on("click","#post-meta-locations-list .location-list-item-small", function(event){
        event.preventDefault();
        
        var valueOfSetting = $('#location-selection-google').val();
        
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
            
            
            $('#location-selection-google').val(newSettingValue).change();
            
        } else {
            
            //we need to add the item
            
            var itemSelected = false;  
            
            $(this).addClass('selected');
            $(this).find('.location-selected-icon').removeClass('fa-times-circle-o');
            $(this).find('.location-selected-icon').addClass('fa-check-circle-o');
            
            if(valueOfSetting == ''){
                $('#location-selection-google').val(locationId).change();   
            } else {
                $('#location-selection-google').val(valueOfSetting+','+locationId).change();      
            }
  
        }
        
        
        
    });
    
    
    
    
    
    
    

    
});



