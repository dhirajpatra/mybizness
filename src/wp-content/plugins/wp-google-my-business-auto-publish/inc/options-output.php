<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

//define all the settings in the plugin
function wp_google_my_business_auto_publish_settings_init() { 
    
    //start connect section
	register_setting( 'googleConnect', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_connect','', 
		'wp_google_my_business_auto_publish_settings_connect_callback', 
		'googleConnect'
	);

    add_settings_field( 
		'wp_google_my_business_auto_publish_tab_memory','', 
		'wp_google_my_business_auto_publish_tab_memory_render', 
		'googleConnect', 
		'wp_google_my_business_auto_publish_connect' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_dismiss_welcome_message','', 
		'wp_google_my_business_auto_publish_dismiss_welcome_message_render', 
		'googleConnect', 
		'wp_google_my_business_auto_publish_connect' 
	);
    
    
    //start account select section
	register_setting( 'googleAccountSelect', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_account_select','', 
		'wp_google_my_business_auto_publish_account_select_callback', 
		'googleAccountSelect'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_account_selection','', 
		'wp_google_my_business_auto_publish_account_selection_render', 
		'googleAccountSelect', 
		'wp_google_my_business_auto_publish_account_select' 
	);
    
    
    //start location select section
	register_setting( 'googleLocationSelect', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_location_select','', 
		'wp_google_my_business_auto_publish_location_select_callback', 
		'googleLocationSelect'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_location_selection','', 
		'wp_google_my_business_auto_publish_location_selection_render', 
		'googleLocationSelect', 
		'wp_google_my_business_auto_publish_location_select' 
	);
    
    
     //start sharing options section
	register_setting( 'googleBusinessSharingOptionsPage', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_sharing_options','', 
		'wp_google_my_business_auto_publish_sharing_options_callback', 
		'googleBusinessSharingOptionsPage'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_default_share_message','', 
		'wp_google_my_business_auto_publish_default_share_message_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_default_action_type','', 
		'wp_google_my_business_auto_publish_default_action_type_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_default_locations','', 
		'wp_google_my_business_auto_publish_default_locations_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_dont_share_categories','', 
		'wp_google_my_business_auto_publish_dont_share_categories_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_dont_share_types','', 
		'wp_google_my_business_auto_publish_dont_share_types_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    
    //start additional options section
	register_setting( 'googleBusinessAdditionalOptionsPage', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_additional_options','', 
		'wp_google_my_business_auto_publish_additional_options_callback', 
		'googleBusinessAdditionalOptionsPage'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_hide_posts_column','', 
		'wp_google_my_business_auto_publish_hide_posts_column_render', 
		'googleBusinessAdditionalOptionsPage', 
		'wp_google_my_business_auto_publish_additional_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_default_share','', 
		'wp_google_my_business_auto_publish_default_share_render', 
		'googleBusinessAdditionalOptionsPage', 
		'wp_google_my_business_auto_publish_additional_options' 
    );

    // add_settings_field( 
	// 	'wp_google_my_business_auto_publish_disable_frontend','', 
	// 	'wp_google_my_business_auto_publish_disable_frontend_render', 
	// 	'googleBusinessAdditionalOptionsPage', 
	// 	'wp_google_my_business_auto_publish_additional_options' 
    // );
    
    //start reviews options section
	register_setting( 'googleBusinessReviews', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_review_options','', 
		'wp_google_my_business_auto_publish_review_options_callback', 
		'googleBusinessReviews'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_hide_reviews','', 
		'wp_google_my_business_auto_publish_hide_reviews_render', 
		'googleBusinessReviews', 
		'wp_google_my_business_auto_publish_review_options' 
	);
    

    
    

    
    
    
    
    
    

}

/**
* 
*
*
* The following functions output the callback of the sections
*/
function wp_google_my_business_auto_publish_settings_connect_callback(){
    
    $adminUrl = urlencode (get_admin_url()); 
    
    echo '<tr class="google_business_settings_row" valign="top">
        <td scope="row" colspan="2">';
    
    echo '<a style="margin-top:20px;" href="https://accounts.google.com/o/oauth2/v2/auth?scope=https://www.googleapis.com/auth/plus.business.manage&access_type=offline&include_granted_scopes=true&state='.$adminUrl.'&redirect_uri=https://northernbeacheswebsites.com.au/redirectgoogle/&response_type=code&client_id=979275334189-mqphf6kpvpji9km7i6pm0sq5ddvfoa60.apps.googleusercontent.com&prompt=consent" id="gmb-authentication" class="button-secondary"><i style="color: #4a8af4;" class="fa fa-google" aria-hidden="true"></i> '.__('Connect with Google My Business', 'wp-google-my-business-auto-publish' ).'</a>';
    
    
    echo '</td></tr>';
    
}


function wp_google_my_business_auto_publish_account_select_callback(){
    
    $pluginSettings = get_option('wp_google_my_business_auto_publish_auth_settings');
    $accessToken = $pluginSettings['access_token'];
    
    if(!isset($accessToken)){
    
    ?>
    <tr class="google_business_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                
                
                <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php _e( 'If you just authenticated for the first time you may not see your accounts here, if so please refresh the page and they should appear.', 'wp-google-my-business-auto-publish' ); ?></p>
                </div>
            </div>
        </td>
    </tr>
    <?php
        
    }
    
}

function wp_google_my_business_auto_publish_location_select_callback(){
    
    $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
    $accountName = $pluginSettings['wp_google_my_business_auto_publish_account_selection'];
    
    if(!isset($accountName)){
    
    ?>
    <tr class="google_business_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                
                
                <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php _e( 'Please make sure you select an account first.', 'wp-google-my-business-auto-publish' ); ?></p>
                </div>
            </div>
        </td>
    </tr>
    <?php
        
    }
    
}


function wp_google_my_business_auto_publish_review_options_callback(){
    

    ?>
    <tr class="google_business_settings_row" valign="top">
        <td style="vertical-align: top;" scope="row">
            <div class="inside shortcode-builder">
            <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php  _e('Select the options for the shortcode', 'wp-google_business-auto_publish' ); ?></p>
            </div>    

            <?php

                //types boolean, select, number, checkbox
                function wp_google_my_business_auto_publish_shortcode_builder_select($class,$default,$values){
                    
                    $html = '<select class="'.$class.'">';

                        foreach($values as $key=>$value){
                            
                            if($key == $default){
                                $selected = 'selected="selected"';       
                            } else {
                                $selected = '';    
                            }
                            $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        }

                    $html .= '</select>';

                    return $html; 

                }

                function wp_google_my_business_auto_publish_shortcode_builder_number($class,$default,$min,$max){
                    
                    $html = '<input class="'.$class.'" type="number" min="'.$min.'" max="'.$max.'" value="'.$default.'">';

                    return $html; 

                }

                function wp_google_my_business_auto_publish_shortcode_builder_checkbox($class,$default){
                    
                    if($default=='checked'){
                        $checked = 'checked';
                    } else {
                        $checked = '';    
                    }

                    $html = '<input class="'.$class.'" type="checkbox" '.$checked .'>';
                    return $html; 

                }








                
                

                $html = '';
                $html .= '<table>';



                // 'location' => '', 
                $options = get_option('wp_google_my_business_auto_publish_settings');
                $enabledLocations = $options['wp_google_my_business_auto_publish_location_selection'];
                $locationNames = wp_google_my_business_auto_publish_get_specific_location();
                
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Select Location', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                
                if(isset($enabledLocations)){
                    $locationValues = array();
                    $enabledLocationsAsArray = explode(",",$enabledLocations);
                    foreach($enabledLocationsAsArray as $location){
                        
                        $locationValues[$location] = $locationNames[$location];

                    } 

                    $html .= wp_google_my_business_auto_publish_shortcode_builder_select('location','',$locationValues); 

                }  
                
                $html .= '</td>';

                $html .= '</tr>';

                

                
                

                // 'type' => 'slider', //also accepts grid 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Type', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_select('type','slider',array('slider'=>'Slider','grid'=>'Grid')); 
                $html .= '</td>';

                $html .= '</tr>';


                
                

                // 'minimum-stars' => 5,
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Minimum Stars', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_number('minimum-stars','5','1','5');
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'sort-by' => 'date', //also accepts random and stars
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Sort By', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_select('sort-by','date',array('date'=>'Date','random'=>'Random','stars'=>'Stars')); 
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'sort-order' => 'desc', //also accepts asc
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Sort Order', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_select('sort-order','desc',array('desc'=>'Descending','asc'=>'Ascending'));
                $html .= '</td>';

                $html .= '</tr>';


                
                

                // 'review-amount' => 200,
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Amount of Reviews', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_number('review-amount','200','1','200');
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'slides-page' => 1, 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Visible Slides', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_number('slides-page','1','1','12');
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'slides-scroll' => 1, 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Slides to Scroll', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_number('slides-scroll','1','1','12');
                $html .= '</td>';

                $html .= '</tr>';

                
                


                // 'autoplay' => 'false', //also accepts true 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Autoplay', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_checkbox('autoplay','');
                $html .= '</td>';

                $html .= '</tr>';

                
                
                
                // 'speed' => 5000,
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Slide Autoplay Speed (Milliseconds)', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_number('speed','5000','1','60000');
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'transition' => 'slide', //also accepts fade
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Slide Transition', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_select('transition','slide',array('slide'=>'Slide','fade'=>'Fade'));
                $html .= '</td>';

                $html .= '</tr>';


                
                

                // 'read-more' => 'true', //also accepts false 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Read More', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_checkbox('read-more','');
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'show-stars' => 'true', //also accepts false 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Show Stars', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_checkbox('show-stars','checked');
                $html .= '</td>';

                $html .= '</tr>';

                
                

                // 'show-date' => 'true', //also accepts false 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Show Date', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_checkbox('show-date','checked');
                $html .= '</td>';

                $html .= '</tr>';


                
                

                // 'show-quotes' => 'true', //also accepts false 
                $html .= '<tr>';

                $html .= '<td class="label">';
                $html .= '<label>'.__('Show Quote Symbols', 'wp-google_business-auto_publish' ).'</label>';
                $html .= '</td>';

                $html .= '<td class="options">';
                $html .= wp_google_my_business_auto_publish_shortcode_builder_checkbox('show-quotes','checked');
                $html .= '</td>';

                $html .= '</tr>';

                

                $html .= '</table>';
                


                echo $html;

            
            ?>
            
                
            
               

            </div>
        </td>

        <td style="vertical-align: top;">

            <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php  _e('Put the following shortcode on any post, page or widget!', 'wp-google_business-auto_publish' ); ?></p>
            </div>
            <input id="shortcode-input" class="shortcode-input" type="text" value=""><button style="margin-left: 10px;" type="button" id="copy-shortcode" class="button-secondary"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Shortcode</button>
            
            <em style="display:block;margin-top:45px;text-align: center;font-size: smaller; opacity: .5;"><?php  _e('Note this preview may not represent the frontend implementation of the shortcode due to different theme/plugin styles that may be present.', 'wp-google_business-auto_publish' ); ?></em>
            <div style="margin-top: 20px;" id="shortcode-preview"></div>


        </td>
    </tr>
    <?php
        

    
}




function wp_google_my_business_auto_publish_additional_options_callback(){}
function wp_google_my_business_auto_publish_sharing_options_callback(){}

function wp_google_my_business_auto_publish_tab_memory_render() {    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_tab_memory','Tab Memory','Remembers the last settings tab','text','','','','hidden-row');   
}

function wp_google_my_business_auto_publish_dismiss_welcome_message_render() {    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_dismiss_welcome_message','Dismiss Welcome Message','','text','','','','hidden-row');   
}



//the following functions output the option html
function wp_google_my_business_auto_publish_account_selection_render() { 
        
    //create an empty array
    $values = array();
    
    $getAccounts = wp_google_my_business_auto_publish_get_accounts();
    
    if($getAccounts !== "ERROR"){
        foreach($getAccounts as $account){
            $values[$account['name']] = $account['accountName'];
        }        
        
    }

    //$values = array('Business 1' => 'Business 1','Business 2' => 'Business 2','Business 3' => 'Business 3');
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_account_selection','Select Account','','select','',$values,'','');  
    
}



//the following functions output the option html
function wp_google_my_business_auto_publish_location_selection_render() { 

    
    $getLocations = wp_google_my_business_auto_publish_get_locations();
    $getLocationImages = wp_google_my_business_auto_publish_get_location_images();
    
    
    //get existing settings
    $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
    $existingSetting = $pluginSettings['wp_google_my_business_auto_publish_location_selection'];
    
    if(isset($existingSetting)){
        $settingToArray = explode(",",$existingSetting);     
    } else {
        $settingToArray = array();    
    }
    
    
    if($getLocations !== "ERROR"){
        
        $html = '<tr class="google_business_settings_row" valign="top"><td scope="row" colspan="2"><div class="inside">';
        
        $html .= '<h3 style="margin-bottom: 20px;">'.__('Select the locations you want to use with the plugin', 'wp-google_business-auto_publish' ).'</h3>';
        
        $html .= '<ul class="google-locations">';
        
        foreach($getLocations as $location){
            
            //lets check if the api is enabled
            if(isset($location['locationState']['isLocalPostApiDisabled']) && $location['locationState']['isLocalPostApiDisabled'] == true){
                //do nothing
            } else {
                $locationId = $location['name'];
                $locationName = $location['locationName'];
                
                //check if list item is in setting
                if(in_array($locationId, $settingToArray)){
                    $listClass = 'selected';
                    $iconClass = 'fa-check-circle-o';
                } else {
                    $listClass = ''; 
                    $iconClass = 'fa-times-circle-o';
                }
                
                $locationAddressLines = $location['address']['addressLines'];
                
                $locationStreet = '';
                foreach($locationAddressLines as $addressLine){
                    $locationStreet .= $addressLine.', ';    
                }
                    
                $locationAddress = $locationStreet.$location['address']['locality'].', '.$location['address']['administrativeArea'].', '.$location['address']['postalCode'].', '.$location['address']['regionCode'];
                    
                    
                
                $html .= '<li class="location-list-item '.$listClass.'" data="'.$locationId.'">';
                
                    //image 
                    //only do image if image exists

                    if(is_array($getLocationImages)){
                        if(array_key_exists($locationId,$getLocationImages)){
                            $locationImage = $getLocationImages[$locationId];
                            $html .= '<img src="'.$locationImage.'" class="location-image" height="42" width="42">';
                        }
                    }

                    //location information
                    $html .= '<div class="location-information">';
                    
                        //name
                        $html .= '<span class="location-name">'.$locationName.'</span>';

                        //address
                        $html .= '<span class="location-address">'.$locationAddress.'</span>';
                
                    $html .= '</div>';
                
                    //render appropriate icon
                    $html .= '<i class="location-selected-icon fa '.$iconClass.'" aria-hidden="true"></i>';
                    
                
                $html .= '</li>';
            }
            
        }  
        
        $html .= '</ul>';
        $html .= '</div></td></tr>';
        
        echo $html;
        
    }    
    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_location_selection','Select Location(s)','','text','','','','hidden-row');  
    
}





//the following functions output the option html
function wp_google_my_business_auto_publish_hide_reviews_render() { 

    
    $options = get_option('wp_google_my_business_auto_publish_settings');
    
    $enabledLocations = $options['wp_google_my_business_auto_publish_location_selection'];

    $existingSetting = $options['wp_google_my_business_auto_publish_hide_reviews'];

    if(isset($existingSetting)){
        $settingToArray = explode(",",$existingSetting);     
    } else {
        $settingToArray = array();    
    }

    $enabledLocationsAsArray = explode(",",$enabledLocations);          

    $locationData = wp_google_my_business_auto_publish_get_specific_location();

    $ratingTranslated = array('FIVE'=>5,'FOUR'=>4,'THREE'=>3,'TWO'=>2,'ONE'=>1);
    
    //start output
    $html = '<tr class="google_business_settings_row" valign="top"><td scope="row" colspan="2"><div class="inside">';
    

    $html .= '<div style="font-weight: 600;" class="notice notice-info inline">
                    <p>'.__('Select the reviews you want to manually exclude from the display', 'wp-google_business-auto_publish' ).'</p>
            </div>';

    foreach($enabledLocationsAsArray as $location){
        $html .= '<h3 style="margin-top:30px;">'.$locationData[$location].'</h3>';

        $reviews = wp_google_my_business_auto_publish_get_reviews($location);

        if($reviews !== 'ERROR'){
            //start printing review rows
            
            $html .= '<ul class="google-reviews">';

            foreach($reviews as $review){
                //establish the right class
                if(in_array($review['name'], $settingToArray)){
                    $listClass = '';
                    $iconClass = 'fa-eye-slash';
                } else {
                    $listClass = 'selected'; 
                    $iconClass = 'fa-eye';
                }

                $html .= '<li class="review-list-item '.$listClass.'" data="'.$review['name'].'">';

                    $html .= '<div class="review-information">';
                
                    //name
                    $html .= '<span class="review-reviewer">'.$review['reviewer']['displayName'].'</span>';

                    //date
                    $niceDate = strtotime($review['createTime']);
                    $niceDate = date(get_option('date_format'),$niceDate);
                    $html .= '<span class="review-date">'.$niceDate.'</span>';

                    //stars

                    $amountOfStars = $ratingTranslated[$review['starRating']];
                    $stars = '';

                    for ($i = 0 ; $i < $amountOfStars; $i++){ 
                        $stars .= '<i class="fa fa-star" aria-hidden="true"></i>'; 
                    }

                    $html .= '<span class="review-rating">'.$stars.'</span>';


                    //comment
                    $html .= '<span class="review-comment">'.$review['comment'].'</span>';
            
                    $html .= '</div>';
            
                    //render appropriate icon
                    $html .= '<i class="review-selected-icon fa '.$iconClass.'" aria-hidden="true"></i>';


                $html .= '</li>';




            }

            $html .= '</ul>';     



        }    

    }    
                        
    
    $html .= '</div></td></tr>';
    echo $html;

    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_hide_reviews','Hide Reviews','','text','','','','hidden-row');  
    
}






























//the following functions output the option html
function wp_google_my_business_auto_publish_hide_posts_column_render() { 
    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_hide_posts_column','Hide Posts Column','On your <a href="'. wp_google_my_business_auto_publish_posts_page_url().'">posts</a> page by default there\'s a handy new column which shows which posts have been shared and which ones haven\'t. You can use this setting to hide this column.','checkbox','','','','');  
    
}

//the following functions output the option html
function wp_google_my_business_auto_publish_default_share_render() { 
    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_default_share','By default don\'t share posts on my Google My Business Page','','checkbox','','','','');  
    
}

// //the following functions output the option html
// function wp_google_my_business_auto_publish_disable_frontend_render() { 
//     wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_disable_frontend','Disable frontend styles and scripts','This is used for the reviews part of the plugin.','checkbox','','','','');  
// }






//the following functions output the option html
function wp_google_my_business_auto_publish_default_share_message_render() { 

    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_default_share_message"><?php _e('Default Share Message', 'wp-google-my-business-auto-publish' ); ?></label>
            </br>
            <a style="margin-top: 5px;" value="[POST_TITLE]" class="button-secondary google_business_append_buttons">[POST_TITLE]</a>
<!--            <a style="margin-top: 5px;" value="[POST_LINK]" class="button-secondary google_business_append_buttons">[POST_LINK]</a>-->
            <a style="margin-top: 5px;" value="[POST_EXCERPT]" class="button-secondary google_business_append_buttons">[POST_EXCERPT]</a>
            <a style="margin-top: 5px;" value="[POST_CONTENT]" class="button-secondary google_business_append_buttons">[POST_CONTENT]</a>
            <a style="margin-top: 5px;" value="[POST_AUTHOR]" class="button-secondary google_business_append_buttons">[POST_AUTHOR]</a>
            <a style="margin-top: 5px;" value="[WEBSITE_TITLE]" class="button-secondary google_business_append_buttons">[WEBSITE_TITLE]</a>
        </td>
        <td>   
            <textarea cols="46" rows="14" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_default_share_message]" id="wp_google_my_business_auto_publish_default_share_message"><?php if(isset($options['wp_google_my_business_auto_publish_default_share_message'])) { echo esc_attr($options['wp_google_my_business_auto_publish_default_share_message']); } else {echo 'New Post: [POST_TITLE] - [POST_CONTENT]';} ?></textarea>
        </td>
    </tr>
	<?php
    
}




//the following functions output the option html
function wp_google_my_business_auto_publish_default_action_type_render() { 
    
    $values = array('LEARN_MORE' => 'Learn More','BOOK' => 'Book','ORDER' => 'Order','SHOP' => 'Shop','SIGN_UP' => 'Sign Up');
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_default_action_type','Default Action Type','Each post on Google My Business has a call to action button, set the default action here.','select','',$values,'','');  
 
    
}




//the following functions output the option html
function wp_google_my_business_auto_publish_default_locations_render() { 
 $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_default_locations"><?php _e('The default locations you want to share with', 'wp-google-my-business-auto-publish' ); ?></label>
        </td>
        <td>   
            <?php
                                            
           

            echo '<ul id="default-locations-list">';                                             
            
            //select items
            if(isset($options['wp_google_my_business_auto_publish_default_locations'])){
                $selectedItems = explode(",",$options['wp_google_my_business_auto_publish_default_locations']);    
            } else {
                $selectedItems = array();    
            }
            
            echo wp_google_my_business_auto_publish_render_location_list_items($selectedItems);        
    
            echo '</ul>';                                              
            ?>
            
            
            

            <input style="display:none;" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_default_locations]" id="wp_google_my_business_auto_publish_default_locations" type="text" value="<?php if(isset($options['wp_google_my_business_auto_publish_default_locations'])) { echo esc_attr($options['wp_google_my_business_auto_publish_default_locations']); } ?>" class="regular-text" />    
            
        </td>
    </tr>
	<?php   
    
}






//the following functions output the option html
function wp_google_my_business_auto_publish_dont_share_categories_render() { 
 $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_dont_share_categories"><?php _e('Don\'t Share Select Post Categories to my Google Business page', 'wp-google-my-business-auto-publish' ); ?></label>
        </td>
        <td>   
            <?php
                                            
            $categories = get_categories( array(
            'hide_empty'   => 0,
            ));

            echo '<ul id="category-listing">';                                                
            foreach ($categories as $category) {
                    echo '<li><input class="dont-share-checkbox" type="checkbox" id="'.esc_attr($category->name).'">' . esc_attr($category->name). '</li>';
            }
            echo '</ul>';                                              
            ?>

            <input style="display:none;" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_dont_share_categories]" id="wp_google_my_business_auto_publish_dont_share_categories" type="text" value="<?php if(isset($options['wp_google_my_business_auto_publish_dont_share_categories'])) { echo esc_attr($options['wp_google_my_business_auto_publish_dont_share_categories']); } ?>" class="regular-text" />    
            
        </td>
    </tr>
	<?php   
    
}

//the following functions output the option html
function wp_google_my_business_auto_publish_dont_share_types_render() { 
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_dont_share_types"><?php _e('Share the following: Posts, Pages and Custom Post Types', 'wp-google-my-business-auto-publish' ); ?></label>
        </td>
        <td>   
            <?php
            
            $args = array(
               'public'   => true,
               '_builtin' => false
            );

            $output = 'names'; // 'names' or 'objects' (default: 'names')
            $operator = 'and'; // 'and' or 'or' (default: 'and')

            $post_types = get_post_types( $args, $output, $operator );
    
            echo '<ul id="category-listing">';
            echo '<li><input class="post-type-checkbox" type="checkbox" id="Post">Post</li>';
            echo '<li><input class="post-type-checkbox" type="checkbox" id="Page">Page</li>'; 
            foreach ($post_types as $item) {
                $item = ucwords($item);
                echo '<li><input class="post-type-checkbox" type="checkbox" id="'.esc_attr($item).'">' . esc_attr($item). '</li>';    
            }
            echo '</ul>';                                              
            ?>

            <input style="display:none;" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_dont_share_types]" id="wp_google_my_business_auto_publish_dont_share_types" type="text" value="<?php if(isset($options['wp_google_my_business_auto_publish_dont_share_types'])) { echo esc_attr($options['wp_google_my_business_auto_publish_dont_share_types']); } else {echo ",Post";} ?>" class="regular-text" />    
            
        </td>
    </tr>
	<?php
    
}









//function to generate settings rows
function wp_google_my_business_auto_publish_settings_code_generator($id,$label,$description,$type,$default,$parameter,$importantNote,$rowClass) {
    
    //get options
    $options = get_option('wp_google_my_business_auto_publish_settings');
    
    //value
    if(isset($options[$id])){  
        $value = $options[$id];    
    } elseif(strlen($default)>0) {
        $value = $default;   
    } else {
        $value = '';
    }
    
    
    //the label
    echo '<tr class="google_business_settings_row '.$rowClass.'" valign="top">';
    echo '<td scope="row">';
    echo '<label for="'.$id.'">'.__($label, 'wp-google_business-auto_publish' );
    if(strlen($description)>0){
        echo ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
        echo '<p class="hidden"><em>'.$description.'</em></p>';
    }
    if(strlen($importantNote)>0){
        echo '</br><span style="color: #CC0000;">';
        echo $importantNote;
        echo '</span>';
    } 
    echo '</label>';
    
    
    
    if($type == 'shortcode') {
        echo '</br>';
        
        foreach($parameter as $shortcodevalue){
            echo '<a value="['.$shortcodevalue.']" class="google_business_append_buttons">['.$shortcodevalue.']</a>';
        }       
    }
    
    if($type == 'textarea-advanced') {
        echo '</br>';
        
        foreach($parameter as $shortcodevalue){
            echo '<a value="['.$shortcodevalue.']" data="'.$id.'" class="google_business_append_buttons_advanced">['.$shortcodevalue.']</a>';
        }       
    }
    
    
    if($type == 'shortcode-advanced') {
        echo '</br>';
        
        foreach($parameter as $shortcodevalue){
            echo '<a value="'.$shortcodevalue[1].'" class="google_business_append_buttons">'.$shortcodevalue[0].'</a>';
        }       
    }
    
    

    //the setting    
    echo '</td><td>';
    
    //text
    if($type == "text"){
        echo '<input type="text" class="regular-text" name="wp_google_my_business_auto_publish_settings['.$id.']" id="'.$id.'" value="'.$value.'">';     
    }
    
    //select
    if($type == "select"){
        echo '<select name="wp_google_my_business_auto_publish_settings['.$id.']" id="'.$id.'">';
        
        foreach($parameter as $x => $xvalue){
            echo '<option value="'.$x.'" ';
            if($x == $value) {
                echo 'selected="selected"';    
            }
            echo '>'.$xvalue.'</option>';
        }
        echo '</select>';
    }
    
    
    //checkbox
    if($type == "checkbox"){
        echo '<label class="switch">';
        echo '<input type="checkbox" id="'.$id.'" name="wp_google_my_business_auto_publish_settings['.$id.']" ';
        echo checked($value,1,false);
        echo 'value="1">';
        echo '<span class="slider round"></span></label>';
    }
        
    //color
    if($type == "color"){ 
        echo '<input name="wp_google_my_business_auto_publish_settings['.$id.']" id="'.$id.'" type="text" value="'.$value.'" class="my-color-field" data-default-color="'.$default.'"/>';    
    }
    
    //page
    if($type == "page"){
        $args = array(
            'echo' => 0,
            'selected' => $value,
            'name' => 'wp_google_my_business_auto_publish_settings['.$id.']',
            'id' => $id,
            'show_option_none' => $default,
            'option_none_value' => "default",
            'sort_column'  => 'post_title',
            );
        
            echo wp_dropdown_pages($args);     
    }
    
    //textarea
    if($type == "textarea" || $type == "shortcode" || $type == "shortcode-advanced"){
        echo '<textarea cols="46" rows="3" name="wp_google_my_business_auto_publish_settings['.$id.']" id="'.$id.'">'.$value.'</textarea>';
    }
    
    
    //textarea-advanced
//    if($type == "textarea-advanced"){
//        wp_editor(html_entity_decode(stripslashes($value)), $id, $settings = array(
//            'textarea_name' => 'idea_push_settings['.$id.']',
//            'drag_drop_upload' => true,
//            'textarea_rows' => 7,  
//            )
//        );
//    }  
    
    
    if($type == "textarea-advanced"){
        if(isset($value)){    
            wp_editor(html_entity_decode(stripslashes($value)), $id, $settings = array(
            'wpautop' => false,    
            'textarea_name' => 'wp_google_my_business_auto_publish_settings['.$id.']',
            'drag_drop_upload' => true,
            'textarea_rows' => 7, 
            ));    
        } else {
            wp_editor("", $id, $settings = array(
            'wpautop' => false,    
            'textarea_name' => 'wp_google_my_business_auto_publish_settings['.$id.']',
            'drag_drop_upload' => true,
            'textarea_rows' => 7,
            ));         
        }
    }
    
    //number
    if($type == "number"){
        echo '<input type="number" class="regular-text" name="wp_google_my_business_auto_publish_settings['.$id.']" id="'.$id.'" value="'.$value.'">';     
    }

    echo '</td></tr>';

}









?>