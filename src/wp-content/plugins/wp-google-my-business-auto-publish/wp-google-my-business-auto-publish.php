<?php

/*
*		Plugin Name: WP Google My Business Auto Publish
*		Plugin URI: https://www.northernbeacheswebsites.com.au
*		Description: Publish your latest posts to Google My Business automatically. 
*		Version: 2.18
*		Author: Martin Gibson
*		Text Domain: wp-google-my-business-auto-publish   
*		Support: https://www.northernbeacheswebsites.com.au/contact
*		Licence: GPL2
*/

/**
* 
*
*
* Gets version number of plugin
*/
function wp_google_my_business_auto_publish_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}
/**
* 
*
*
* Create admin menu and add it to a global variable so that admin styles/scripts can hook into it
*/
add_action( 'admin_menu', 'wp_google_my_business_auto_publish_add_admin_menu' );
add_action( 'admin_init', 'wp_google_my_business_auto_publish_settings_init' );

function wp_google_my_business_auto_publish_add_admin_menu(  ) { 
    $menu_icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMiIgYmFzZVByb2ZpbGU9InRpbnkiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cGF0aCBmaWxsPSIjOUVBM0E3IiBkPSJNMTcuNywxSDIuM0MxLjYsMSwxLDEuNiwxLDIuM3YxNS40QzEsMTguNCwxLjYsMTksMi4zLDE5aDE1LjNjMC43LDAsMS4zLTAuNiwxLjMtMS4zVjIuM0MxOSwxLjYsMTguNCwxLDE3LjcsMXogTTE1LjksMTIuNmMtMC4zLDAuNy0wLjcsMS4zLTEuMiwxLjljLTAuOSwxLTIsMS42LTMuNCwxLjhjLTAuMiwwLTAuNCwwLTAuNiwwLjFjLTAuMywwLTAuNiwwLTAuOCwwYy0xLjMsMC0yLjUtMC40LTMuNi0xLjJjLTAuOS0wLjctMS42LTEuNS0yLjEtMi41Yy0wLjMtMC41LTAuNC0xLjEtMC41LTEuN0MzLjYsOS44LDMuNyw4LjcsNC4yLDcuNmMwLjMtMC43LDAuNy0xLjQsMS4zLTJjMC40LTAuNCwwLjgtMC44LDEuMi0xLjFjMC44LTAuNSwxLjctMC45LDIuNi0xYzEuNS0wLjIsMi45LDAsNC4yLDAuOEMxMy44LDQuNiwxNCw0LjcsMTQuMyw1YzAuMSwwLjEsMC4xLDAuMiwwLDAuM2MwLDAtMC4xLDAuMS0wLjEsMC4xYy0wLjQsMC40LTAuOCwwLjgtMS4zLDEuM2MtMC4yLDAuMi0wLjIsMC4yLTAuNCwwYy0wLjctMC41LTEuNC0wLjgtMi4yLTAuOGMtMSwwLTEuOSwwLjMtMi42LDFjLTAuNiwwLjUtMSwxLjEtMS4yLDEuOEM2LjMsOSw2LjMsOS4zLDYuMyw5LjZjMCwwLjIsMCwwLjMsMCwwLjVjMCwxLDAuNCwxLjgsMSwyLjVjMC40LDAuNSwxLDAuOSwxLjYsMS4xYzAuNiwwLjIsMS4yLDAuMywxLjgsMC4yYzAuNC0wLjEsMC44LTAuMSwxLjItMC4zYzAuOC0wLjMsMS40LTAuOSwxLjctMS43YzAuMS0wLjIsMC4xLTAuMywwLjEtMC41YzAtMC4xLDAtMC4yLTAuMi0wLjNjLTAuMSwwLTAuMywwLTAuNCwwYy0wLjgsMC0xLjUsMC0yLjMsMGMtMC4xLDAtMC4zLDAtMC40LDBjLTAuMSwwLTAuMi0wLjEtMC4yLTAuMmMwLTAuMSwwLTAuMiwwLTAuM2MwLTAuNSwwLTEsMC0xLjVjMC0wLjEsMC0wLjEsMC0wLjJjMC0wLjIsMC4xLTAuMywwLjMtMC4zYzAuMSwwLDAuMiwwLDAuNCwwYzAuOCwwLDEuNiwwLDIuNCwwYzAuOCwwLDEuNSwwLDIuMywwYzAuMSwwLDAuMiwwLDAuMywwYzAuMywwLDAuNCwwLjEsMC40LDAuNEMxNi40LDEwLjQsMTYuNCwxMS41LDE1LjksMTIuNnoiLz48L3N2Zz4=';
    
    global $wp_google_my_business_auto_publish_settings_page;
    
	$wp_google_my_business_auto_publish_settings_page = add_menu_page( 'WP GMB Auto Publish', 'WP GMB Auto Publish', 'manage_options', 'wp_google_my_business_auto_publish', 'wp_google_my_business_auto_publish_options_page',$menu_icon_svg);
}
/**
* 
*
*
* Gets, sets and renders options
*/
require('inc/options-output.php');
/**
* 
*
*
* Output the wrapper of the settings page and call the sections
*/
function wp_google_my_business_auto_publish_options_page() { 
    require('inc/options-page-wrapper.php');
}
/**
* 
*
*
* Include review shortcode
*/
require('inc/review-shortcode.php');
/**
* 
*
*
* Function that displays settings tab content
*/
function wp_google_my_business_auto_publish_tab_content($tabName) {
    
    //get options    
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );  
    
    ?>
    <div class="tab-content" id="<?php echo $tabName; ?>">
        <div class="meta-box-sortables ui-sortable">
            <div class="postbox">
                <div class="inside">
                    
                    
                    <?php if($tabName == 'googleBusinessHelpPage') { ?>
                
                <div id="accordion">
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('How do I connect my website to my Gooogle My Business page', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('First, go to the <a class="open-tab" href="#googleConnect">Connect</a> tab to and click the "Connect with Google My Business" button. You will be prompted to give your website permission to send posts to Google My Business, please click allow. The page will refresh and go to the <a class="open-tab" href="#googleAccountSelect">Account Select</a> tab. Here you need to select the account you want to share to - most people will only have just 1 account so just select this one and press "Save All Settings". Now you will need to actually select the location/business on the <a class="open-tab" href="#googleLocationSelect">Location Select</a> tab. Once you have done this press the "Save All Settings" button and that\'s it!', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('How do posts/pages/custom post types get shared to Google My Business', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('Firstly, make sure that on the <a class="open-tab" href="#googleBusinessSharingOptionsPage">Sharing Options</a> tab that you have enabled the post/page/custom post types you want to share with Google My Business. When you publish/schedule a post/page/custom post from WordPress the content sent to Google is the "Default Share Message" set in the <a class="open-tab" href="#googleBusinessSharingOptionsPage">plugin settings</a>, you can change this to whatever you want and you can use the handy shortcodes as well which insert dynamic post content. This can be overided on the actual post/page/custom post page as well using the meta box. The post/page/custom post link is sent to Google My Business as the call to action, this call to action button text can be changed as well. If your post has a featured image (the featured image is not an image in your post content it is the WordPress featured image) this will be sent to Google as well. If a post has been successfully shared you will see the share event in the metabox or on the all post/page/custom post page. You can also view and remove Google My Business posts from the <a class="open-tab" href="#googleBusinessManagePosts">Manage Posts</a> tab.', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>
                    
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('Can I share stuff on Google Plus', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('No, this plugin is specifically for Google My Business. There are good plugins already available on <a target="_blank" href="https://wordpress.org/plugins/">WordPress</a> which enable you to share on Google Plus.', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('I just added an account or location but it is not showing on the account or location tab', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('To make things load faster we temporarily store your location and account data for 7 days. If you just recently added a location or account, please re-authenticate from the <a class="open-tab" href="#googleConnect">Connect</a> tab and you should see your new accounts/locations.', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('How do I delete all the plugin settings?', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('Please click <a id="clear-all-settings-button" href="#">here</a>.', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>



                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('How do I make reviews left aligned using the shortcode?', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('You need to insert CSS onto your site to make it left aligned. The following code should do it: <code>.gmb-review {text-align: left !important;} .gmb-reviews .slick-dots {text-align: left !important;}</code> additional styling may be necessary depending on your theme.', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>


                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('Images are not being shared to Google My Business?', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        <?php _e('Google My Business has an unusual image requirements and we enforce these requirements in the plugin. If your image does not meet Google\'s standards we will still share the post but it will just have no image. You need to make sure that the width AND height of the image is at least 250px AND the file size is greater then 10kb. Please ensure the file type of your image is standard like a jpg or png. If you are absolutely sure your image meets these requirements and it still is not being shared please raise a support request.', 'wp-google-my-business-auto-publish' ); ?>
                        
                    </div>



                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e('I am having issues with the plugin, what can I Do', 'wp-google-my-business-auto-publish' ); ?></h3>
                    <div>
                        
                        
                        <?php _e('Please visit the <a target="_blank" href="https://wordpress.org/support/plugin/wp-google-my-business-auto-publish">forum</a>. <strong style="color: red !important;">Before writing on the forum make sure you have the latest version of this plugin installed and it would be a good idea to also make sure you have the latest version of WordPress and make sure your post has the below diagnostic information otherwise I won\'t respond.</strong> Please be specific and screenshots often say a thousand words so please try and do this. I will try and resolve your issue from my end however sometimes I can\'t replicate every issue and in these circumstances I may ask you to provide access to your WordPress install so I can properly diagnose things. ', 'wp-google-my-business-auto-publish' ); ?>
                        
                        
                        
                        
                        
                        
                        <p><code><?php echo 'PHP Version: <strong>'.phpversion().'</strong>'; ?></br>
                        <?php echo 'Wordpress Version: <strong>'.get_bloginfo('version').'</strong>'; ?></br>
                        Plugin Version: <strong><?php echo wp_google_my_business_auto_publish_get_version(); ?></strong></br>
                    
                        <?php if(isset($_SERVER['HTTPS'])) {
                            echo 'HTTP OR HTTPS: <strong>HTTPS</strong></br>';   
                        } else {
                            echo 'HTTP OR HTTPS: <strong>HTTP</strong></br>';     
                        }
                        
                        ?>
                                                  
                                                  
                        
            

                        Active Plugins:</br> 
                        <?php 
                        $active_plugins=get_option('active_plugins');
                        $plugins=get_plugins();
                        $activated_plugins=array();
                        foreach ($active_plugins as $plugin){           
                        array_push($activated_plugins, $plugins[$plugin]);     
                        } 

                        foreach ($activated_plugins as $key){  
                        echo '<strong>'.$key['Name'].'</strong></br>';
                        }


                        //lets try and output location info
                        if( get_transient('wp_google_my_business_auto_publish_locations') ){

                            echo 'Location Information:</br>';

                            $locations = get_transient('wp_google_my_business_auto_publish_locations');

                            foreach($locations as $location){
                                echo '<strong>'.$location['locationName'].'</strong> '.json_encode($location['locationState']).'</br>';
                            }

                            // var_dump(get_transient('wp_google_my_business_auto_publish_locations'));

                        }

                        ?></code></p>
                        
                       


                    </div>
                    
                </div>
                
                <?php } elseif($tabName == 'googleBusinessPostNow'){ ?>
                    
                    <div class="post-to-google-form">
                    
                        <?php echo wp_google_my_business_auto_publish_create_edit_form('','https://','LEARN_MORE','',"STANDARD",'','',''); ?>
                        
                        
                        <ul id="post-now-locations-list">

                        <?php
                        
                        if(isset($options['wp_google_my_business_auto_publish_default_locations'])){
                            $selectedItems = $options['wp_google_my_business_auto_publish_default_locations'];        
                            $selectedItems = explode(",",$selectedItems);     
                        } else {
                            $selectedItems = array();    
                        }                                            
                                                                                                        
                                                                    
                        echo wp_google_my_business_auto_publish_render_location_list_items($selectedItems);     
                                         
                        ?>                                            
                        </ul>                                           
                        
                        <p><button id="post-now" class="button button-primary google-business-save-settings"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php _e('Post Now', 'wp-google_business-auto_publish' ); ?></button></p>
                        
                    </div>    
                
                <?php } elseif($tabName == 'googleBusinessManagePosts'){ ?>
                    <div class="manage-google-posts">
                        
                        
                        
                        <?php
                        //loop through manage posts
                        $enabledLocations = $options['wp_google_my_business_auto_publish_location_selection'];
                        $enabledLocationsAsArray = explode(",",$enabledLocations);                                                                        
                        $locationData = wp_google_my_business_auto_publish_get_specific_location();
                        
                        foreach($enabledLocationsAsArray as $location){
                        
                        ?>                                                
                        
                        
                        
                            <h3><?php echo $locationData[$location]; ?></h3>
                        
                        
                        
                            <table class="manage-google-posts-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('Post Content', 'wp-google_business-auto_publish' ); ?></th>
                                        <th><?php _e('Google Post Link', 'wp-google_business-auto_publish' ); ?></th>

                                        <th><?php _e('Call To Action Link', 'wp-google_business-auto_publish' ); ?></th>

                                        <th><?php _e('Post Image', 'wp-google_business-auto_publish' ); ?></th>

                                        <th><?php _e('Post Created', 'wp-google_business-auto_publish' ); ?></th>
                                        <th><?php _e('Post Updated', 'wp-google_business-auto_publish' ); ?></th>
                                        <th><?php _e('Manage Post', 'wp-google_business-auto_publish' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php

                                //get posts
                                $response = wp_remote_get( 'https://mybusiness.googleapis.com/v4/'.$location.'/localPosts', array(
                                    'headers' => array(
                                        'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
                                    ),
                                ));


                                if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
                                    $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true); 


                                    if(!empty($decodedBody['localPosts'])){
                                        foreach($decodedBody['localPosts'] as $googlePost){

                                            echo '<tr>';
    
    
                                                $createdDateTime = $googlePost['createTime'];
                                                $createdDateTime = strtotime($createdDateTime);
                                                $createdDateTime = date(get_option('date_format').' '.get_option('time_format'),$createdDateTime);
    
                                                $updatedDateTime = $googlePost['updateTime'];
                                                $updatedDateTime = strtotime($updatedDateTime);
                                                $updatedDateTime = date(get_option('date_format').' '.get_option('time_format'),$updatedDateTime);
    
                                                echo '<td class="post-content">'.$googlePost['summary'].'</td>';
                                                echo '<td class="post-link"><a target="_blank" href="'.$googlePost['searchUrl'].'"><i class="fa fa-link" aria-hidden="true"></i></a></td>';
    
                                                echo '<td class="cta-link">';
                                                if(isset($googlePost['callToAction'])){
    
                                                    echo '<a target="_blank" href="'.$googlePost['callToAction']['url'].'"><i class="fa fa-link" aria-hidden="true"></i></a>';
    
                                                    $postLinkData = $googlePost['callToAction']['url'];
                                                    $postActionData = $googlePost['callToAction']['actionType'];    
    
                                                } else {
                                                    $postLinkData = '';
                                                    $postActionData = '';    
                                                } 
    
    
                                                echo '</td>';
    
    
                                                echo '<td class="post-image">';
                                                if(isset($googlePost['media']) && $googlePost['media'][0]['mediaFormat'] == 'PHOTO'){
    
                                                    echo '<a target="_blank" href="'.$googlePost['media'][0]['googleUrl'].'"><img style="object-fit: cover;" src="'.$googlePost['media'][0]['googleUrl'].'" height="50" width="50"></a>'; 
    
                                                    $postImage = $googlePost['media'][0]['googleUrl'];
    
                                                } else {
                                                    $postImage = '';    
                                                }
    
                                                echo '</td>';
    
                                                echo '<td class="post-created">'.$createdDateTime.'</td>';
                                                echo '<td class="post-updated">'.$updatedDateTime.'</td>';
    
                                                echo '<td class="manage-post">';
    
                                                echo '<a style="margin-right:10px;" class="delete-google-post" data="'.$googlePost['name'].'" href="#"><i  class="fa fa-trash" aria-hidden="true"></i></a>';
    
                                                //do event check
                                                if(isset($googlePost['event'])){
    
                                                    $postEventName = $googlePost['event']['title'];
    
                                                    //May 9, 2018 03:47
                                                    //strtotime("2014-01-01 00:00:01")
    
                                                    $postEventStart = strtotime($googlePost['event']['schedule']['startDate']['year'].'-'.$googlePost['event']['schedule']['startDate']['month'].'-'.$googlePost['event']['schedule']['startDate']['day'].' '.$googlePost['event']['schedule']['startTime']['hours'].':'.$googlePost['event']['schedule']['startTime']['minutes']);
    
                                                    $postEventEnd = strtotime($googlePost['event']['schedule']['endDate']['year'].'-'.$googlePost['event']['schedule']['endDate']['month'].'-'.$googlePost['event']['schedule']['endDate']['day'].' '.$googlePost['event']['schedule']['endTime']['hours'].':'.$googlePost['event']['schedule']['endTime']['minutes']);
    
    
                                                    $postEventStart = date('F j, Y h:i',$postEventStart);
                                                    $postEventEnd = date('F j, Y h:i',$postEventEnd);
    
                                                } else {
                                                    $postEventName = '';
                                                    $postEventStart = '';
                                                    $postEventEnd = '';    
                                                }
    
    
    
                                                echo '<a class="edit-google-post" data="'.$googlePost['name'].'"         
    
                                                data-postContent="'.$googlePost['summary'].'"
                                                data-postLink="'.$postLinkData.'"
                                                data-postAction="'.$postActionData.'"
                                                data-postImage="'.$postImage.'"
                                                data-makeAnEvent="'.$googlePost['topicType'].'"
                                                data-eventName="'.$postEventName.'"
                                                data-eventStart="'.$postEventStart.'"
                                                data-eventEnd="'.$postEventEnd.'"
    
                                                href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
    
                                                echo '</td>';
    
                                            echo '</tr>';    
    
                                        }
                                    }


                                }                         


                                ?>                                            


                                </tbody>

                            </table>
                        
  
                        <?php } //end for each account ?>      
                        
                        
     
                    </div>
                <?php } else {  ?>
                    
                    
                    

                    <!--table-->
                    <table class="form-table">


                        <!--fields-->
                        <?php
                        settings_fields($tabName);
                        do_settings_sections($tabName);
                        ?>

                        <button type="submit" name="submit" id="submit" class="button button-primary google-business-save-settings"><i class="fa fa-check-square" aria-hidden="true"></i> <?php _e('Save All Settings', 'wp-google_business-auto_publish' ); ?></button>


                    </table>

                <?php } ?>

                </div> <!-- .inside -->
            </div> <!-- .postbox -->                      
        </div> <!-- .meta-box-sortables --> 
    </div> <!-- .tab-content -->     
    <?php
}
/**
* 
*
*
* Load admin styles and scripts
*/
function wp_google_my_business_auto_publish_register_admin($hook)
{
    
    //get settings page
    global $wp_google_my_business_auto_publish_settings_page;
    
    
    if(in_array($hook, array('post.php', 'post-new.php' , 'edit.php') )){
        
        //scripts
        wp_enqueue_script( 'moment-wp-google-auto-publish', plugins_url( '/inc/external/moment.min.js', __FILE__ ), array( 'jquery'));
        wp_enqueue_script( 'timedatepicker-script', plugins_url( '/inc/external/bootstrap-datetimepicker.min.js', __FILE__ ), array( 'jquery'),'4.17.47',true);
        wp_enqueue_script( 'custom-admin-post-script', plugins_url( '/inc/postscript.js', __FILE__ ), array( 'jquery'),wp_google_my_business_auto_publish_get_version());
        
        //styles
        wp_enqueue_style( 'timedatepicker-style', plugins_url( '/inc/external/bootstrap-datetimepicker.min.css', __FILE__ ));
        wp_enqueue_style( 'font-awesome-icons', plugins_url( '/inc/external/font-awesome.min.css', __FILE__ ));
        wp_enqueue_style( 'custom-style', plugins_url( '/inc/poststyle.css', __FILE__ ), array(),wp_google_my_business_auto_publish_get_version()); 

        
        
        
        
    } elseif($wp_google_my_business_auto_publish_settings_page == $hook){
        
        //scripts
        wp_enqueue_script( 'slick', plugins_url( '/inc/external/slick.min.js', __FILE__ ), array( 'jquery'),1.9);
        wp_enqueue_script( 'custom-admin-script', plugins_url( '/inc/adminscript.js', __FILE__ ), array( 'jquery','wp-color-picker' ),wp_google_my_business_auto_publish_get_version());
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-form');
        wp_enqueue_script( 'moment-wp-google-auto-publish', plugins_url( '/inc/external/moment.min.js', __FILE__ ), array( 'jquery'));
        wp_enqueue_script( 'timedatepicker-script', plugins_url( '/inc/external/bootstrap-datetimepicker.min.js', __FILE__ ), array( 'jquery'));
        
        wp_enqueue_media(); 
        wp_enqueue_script('read-more-gmb', plugins_url('/inc/external/readmore.min.js', __FILE__ ), array( 'jquery'));
        

        //styles
        wp_enqueue_style( 'custom-admin-style', plugins_url( '/inc/adminstyle.css', __FILE__ ),array(),wp_google_my_business_auto_publish_get_version());
        wp_enqueue_style( 'custom-frontend-style', plugins_url( '/inc/frontendstyle.css', __FILE__ ),array(),wp_google_my_business_auto_publish_get_version());
        wp_enqueue_style( 'font-awesome-icons', plugins_url( '/inc/external/font-awesome.min.css', __FILE__ ));
        wp_enqueue_style( 'timedatepicker-style', plugins_url( '/inc/external/bootstrap-datetimepicker.min.css', __FILE__ )); 
        wp_enqueue_style( 'slick-style', plugins_url( '/inc/external/slick.css', __FILE__ ));

        
    } else {
        
        return;
    }
    
    

    
    
}
add_action( 'admin_enqueue_scripts', 'wp_google_my_business_auto_publish_register_admin' );
/**
* 
*
*
* Load frontend styles and scripts
*/
function wp_google_my_business_auto_publish_register_frontend(){


    // //get options
    // $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    // $disableFrontend = $options['wp_google_my_business_auto_publish_disable_frontend'];

    // if(!isset($disableFrontend)){
        //scripts
        wp_register_script( 'slick', plugins_url( '/inc/external/slick.min.js', __FILE__ ), array( 'jquery'),1.9);
        wp_register_script( 'custom-frontend-script', plugins_url( '/inc/frontendscript.js', __FILE__ ), array( 'jquery'),wp_google_my_business_auto_publish_get_version());
        wp_register_script('read-more-gmb', plugins_url('/inc/external/readmore.min.js', __FILE__ ), array( 'jquery'));

        //styles
        wp_register_style( 'custom-frontend-style', plugins_url( '/inc/frontendstyle.css', __FILE__ ),array(),wp_google_my_business_auto_publish_get_version());
        wp_register_style( 'font-awesome-icons', plugins_url( '/inc/external/font-awesome.min.css', __FILE__ ));
        wp_register_style( 'slick-style', plugins_url( '/inc/external/slick.css', __FILE__ ));

    // }

    



}
add_action( 'wp_enqueue_scripts', 'wp_google_my_business_auto_publish_register_frontend' );
/**
* 
*
*
* Add custom links to plugin on plugins page
*/
function wp_google_my_business_auto_publish_plugin_links( $links, $file ) {
   if ( strpos( $file, 'wp-google-my-business-auto-publish.php' ) !== false ) {
      $new_links = array(
               '<a href="https://northernbeacheswebsites.com.au/product/donate-to-northern-beaches-websites/" target="_blank">' . __('Donate') . '</a>',
               '<a href="https://wordpress.org/support/plugin/wp-google-my-business-auto-publish" target="_blank">' . __('Support Forum') . '</a>',
            );
      $links = array_merge( $links, $new_links );
   }
   return $links;
}
add_filter( 'plugin_row_meta', 'wp_google_my_business_auto_publish_plugin_links', 10, 2 );
/**
* 
*
*
* Add settings link to plugin on plugins page
*/
function wp_google_my_business_auto_publish_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wp_google_my_business_auto_publish">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wp_google_my_business_auto_publish_settings_link' );
/**
* 
*
*
* Function to get the posts URL
*/
function wp_google_my_business_auto_publish_posts_page_url() {

    $currentPageUrl = $_SERVER['REQUEST_URI']; 

    $findCurrentPageUrl = strpos($currentPageUrl,"admin.php");

    $trimCurrentPageUrl = substr($currentPageUrl,0,$findCurrentPageUrl)."edit.php";
    
    return $trimCurrentPageUrl;
}

/**
* 
*
*
* Add translation
*/
add_action('plugins_loaded', 'wp_google_my_business_auto_publish_translations');
function wp_google_my_business_auto_publish_translations() {
	load_plugin_textdomain( 'wp-google-my-business-auto-publish', false, dirname( plugin_basename(__FILE__) ) . '/inc/lang/' );
}
/**
* 
*
*
* Add metabox to post
*/
function wp_google_my_business_auto_publish_metabox($postType){
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    $explodedPostTypes = explode(",",$options['wp_google_my_business_auto_publish_dont_share_types']);
    $explodedPostTypes = array_map('strtolower', $explodedPostTypes);
    
    if(in_array($postType,$explodedPostTypes)) {
        add_meta_box( 'wp_google_my_business_auto_publish_meta_box',__('WP Google My Business Auto Publish Settings', 'wp-google-my-business-auto-publish' ), 'wp_google_my_business_auto_publish_build_meta_box',$postType,'side','high');      
    } 
}
add_action( 'add_meta_boxes', 'wp_google_my_business_auto_publish_metabox' );
/**
* 
*
*
* Add callback function to metabox content
*/
function wp_google_my_business_auto_publish_build_meta_box($post) {
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    wp_nonce_field( basename( __FILE__ ), 'wp_google_my_business_auto_publish_meta_box_nonce' );

    $current_custom_google_share_message = get_post_meta( $post->ID, '_custom_google_share_message', true );

    $current_custom_google_button = get_post_meta( $post->ID, '_custom_google_button', true );

    $current_dont_share_post_google = get_post_meta( $post->ID, '_dont_share_post_google', true );  

    $current_make_an_event = get_post_meta( $post->ID, '_make_an_event', true );
    
    $current_event_title = get_post_meta( $post->ID, '_event_title', true );
    
    $current_event_start_date_time = get_post_meta( $post->ID, '_event_start_date_time', true );
    
    $current_event_end_date_time = get_post_meta( $post->ID, '_event_end_date_time', true );
    
    
    $current_location_selection_google = get_post_meta( $post->ID, '_location_selection_google', true );
    
      
    
?>
<div class='inside'>
    
    
    


    <p>        
    <?php if($current_dont_share_post_google == "yes") $current_dont_share_post_google_checked = 'checked="checked"'; ?>
    <div id="dont-sent-to-google-checkbox-line">   
    <input id="dont-sent-to-google-checkbox" <?php if(isset($options['wp_google_my_business_auto_publish_default_share'])){echo 'data="dont-publish-by-default"';}?> type="checkbox" name="dont-share-post-google" value="yes" <?php if(isset($current_dont_share_post_google_checked)){ echo esc_attr($current_dont_share_post_google_checked);} ?>> <?php echo __( 'Don\'t share this post', 'wp-google-my-business-auto-publish' ); ?></div>
    </p>
    
    
	<p class="custom-google-metabox-setting"><?php echo __( 'Custom Share Message:', 'wp-google-my-business-auto-publish' ); ?><br>
        <textarea cols="29" rows="3" name="custom-google-share-message" id="custom-share-message-google"><?php
    
        if(strlen($current_custom_google_share_message)>0) {
           echo esc_attr($current_custom_google_share_message); 
        } elseif (isset($options['wp_google_my_business_auto_publish_default_share_message'])) {
            echo esc_attr($options['wp_google_my_business_auto_publish_default_share_message']);
        } else {
            echo '';
        }  
    
        ?></textarea>
	</p>
  
    
    
    <p class="custom-google-metabox-setting">        
    <?php   
    $values = array('LEARN_MORE' => 'Learn More','BOOK' => 'Book','ORDER' => 'Order','SHOP' => 'Shop','SIGN_UP' => 'Sign Up');
    
    echo __( 'Custom Button:', 'wp-google-my-business-auto-publish' );
    
    echo '<br><select name="custom-google-button" id="custom-button">';
    
    if(strlen($current_custom_google_button)>0){
        $currentButton = $current_custom_google_button; 
    } elseif (isset($options['wp_google_my_business_auto_publish_default_action_type'])){
        $currentButton = $options['wp_google_my_business_auto_publish_default_action_type'];    
    } else {
        $currentButton = 'LEARN_MORE';      
    }
    
    
    foreach($values as $key => $value){
        
        if($key == $currentButton){
            
            $selectValue = 'selected="selected"';
            
        } else {
            $selectValue = '';    
            
        }

        echo '<option value="'.$key.'" '.$selectValue.'>'.$value.'</option>';
        
    }
    
    echo '</select>';
    
    ?>

    </p>



    <p class="custom-google-metabox-setting">        
    <?php if($current_make_an_event == "yes") $current_make_an_event_checked = 'checked="checked"'; ?>
     
    <input id="make-an-event-checkbox" type="checkbox" name="make-an-event" value="yes" <?php if(isset($current_make_an_event_checked)){ echo esc_attr($current_make_an_event_checked);} ?>> <?php echo __( 'Make post an event', 'wp-google-my-business-auto-publish' ); ?>
        
    </p>



    <p class="gmb-event"><?php echo __( 'Event Title:', 'wp-google-my-business-auto-publish' ); ?><br>
        <input  name="event-title"  id="event-title-google" value="<?php
    
        if(strlen($current_event_title)>0) {
           echo esc_attr($current_event_title); 
        } else {
            echo '';
        }  
    
        ?>">
	</p>



    <p class="gmb-event"><?php echo __( 'Event Start:', 'wp-google-my-business-auto-publish' ); ?><br>
        <input class="gmb-timedatepicker" name="event-start-date-time" id="event-start-date-time" value="<?php
    
        if(strlen($current_event_start_date_time)>0) {
           echo esc_attr($current_event_start_date_time); 
        } else {
            echo '';
        }  
    
        ?>">
	</p>

    <p class="gmb-event"><?php echo __( 'Event End:', 'wp-google-my-business-auto-publish' ); ?><br>
        <input class="gmb-timedatepicker" name="event-end-date-time" id="event-end-date-time" value="<?php
    
        if(strlen($current_event_end_date_time)>0) {
           echo esc_attr($current_event_end_date_time); 
        } else {
            echo '';
        }  
    
        ?>">
	</p>


    
    

    <div style="padding-top:5px;" class="custom-google-metabox-setting"><?php echo __( 'Location selection:', 'wp-google-my-business-auto-publish' ); ?><br>
        
        <ul id="post-meta-locations-list">

            <?php
            
            if(metadata_exists('post', $post->ID, '_location_selection_google')){
                
                $selectedItems = $current_location_selection_google;        
                $selectedItems = explode(",",$selectedItems);   
                
            } elseif(isset($options['wp_google_my_business_auto_publish_default_locations'])){
                $selectedItems = $options['wp_google_my_business_auto_publish_default_locations'];        
                $selectedItems = explode(",",$selectedItems);     
            } else {
                $selectedItems = array();    
            }                                            

            echo wp_google_my_business_auto_publish_render_location_list_items($selectedItems);     

            ?>                                            
        </ul>

        
        
        <input style="display:none;" name="location-selection-google"  id="location-selection-google" value="<?php
    
        if(metadata_exists('post', $post->ID, '_location_selection_google')) {
           echo esc_attr($current_location_selection_google); 
        } elseif(isset($options['wp_google_my_business_auto_publish_default_locations'])) {
            echo $options['wp_google_my_business_auto_publish_default_locations'];     
        } else {
            echo '';    
        }  
    
        ?>">
	</div>


    

    
    <?php if(metadata_exists('post', $post->ID, '_sent_to_google')) {
    echo '<strong>Share History</strong></br>';
            
    foreach(array_reverse(get_post_meta($post->ID, '_sent_to_google', true )) as $share){
            echo $share.'</br>';
    }                    
    }
    ?>
    
    <a href="" style="margin-top: 10px;" data="<?php echo $post->ID; ?>" class="button send-to-google custom-google-metabox-setting"><?php echo __( 'Share Now', 'wp-google-my-business-auto-publish' ); ?></a>


    <div style="display: none; margin-top:15px;" data-dismissible="disable-done-notice-forever" class="notice notice-success is-dismissible inline gmb-settings-saved">
    <p><?php  _e('Settings saved', 'wp-google-my-business-auto-publish' ); ?></p>       
    </div>
    
    
</div>
<?php     
}
/**
* 
*
*
* Function to save meta box information
*/
function wp_google_my_business_auto_publish_save_meta_boxes_data($post_id,$post){
    if ( !isset( $_POST['wp_google_my_business_auto_publish_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_google_my_business_auto_publish_meta_box_nonce'], basename( __FILE__ ) ) ){
	return;
    }
    //don't do anything for autosaves 
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
    //check if user has permission to edit posts otherwise don't do anything 
    if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
    
    //get and set options
    if ( isset( $_REQUEST['custom-google-share-message'] ) ) {
		update_post_meta( $post_id, '_custom_google_share_message', sanitize_text_field( $_POST['custom-google-share-message'] ) );
	}
    
    
    if ( isset( $_REQUEST['custom-google-button'] ) ) {
		update_post_meta( $post_id, '_custom_google_button', sanitize_text_field( $_POST['custom-google-button'] ) );
	}
    
    if ( isset( $_REQUEST['dont-share-post-google'] ) ) {
		update_post_meta( $post_id, '_dont_share_post_google', sanitize_text_field( $_POST['dont-share-post-google'] ) );
	} else {
        delete_post_meta($post_id, '_dont_share_post_google');
    }
    
    if ( isset( $_REQUEST['make-an-event'] ) ) {
		update_post_meta( $post_id, '_make_an_event', sanitize_text_field( $_POST['make-an-event'] ) );
	} else {
        delete_post_meta($post_id, '_make_an_event');
    }
    
    if ( isset( $_REQUEST['event-title'] ) ) {
		update_post_meta( $post_id, '_event_title', sanitize_text_field( $_POST['event-title'] ) );
	}
    
    if ( isset( $_REQUEST['event-start-date-time'] ) ) {
		update_post_meta( $post_id, '_event_start_date_time', sanitize_text_field( $_POST['event-start-date-time'] ) );
	}
    
    if ( isset( $_REQUEST['event-end-date-time'] ) ) {
		update_post_meta( $post_id, '_event_end_date_time', sanitize_text_field( $_POST['event-end-date-time'] ) );
	}
    
    
    if ( isset( $_REQUEST['location-selection-google'] ) ) {
		update_post_meta( $post_id, '_location_selection_google', sanitize_text_field( $_POST['location-selection-google'] ) );
	}
    
    
}
add_action( 'save_post', 'wp_google_my_business_auto_publish_save_meta_boxes_data',10,2);
/**
* 
*
*
* function to save authentication details through authentication process in the plugin settings - this is used when first authenticating
*/
function wp_google_my_business_auto_publish_save_authentication_details() {
	
    //get the code field
    $accessToken = sanitize_text_field($_POST['accessToken']);
    $refreshToken = sanitize_text_field($_POST['refreshToken']);
    
    
    //lets create an array which will store our updated settings
    $pluginSettings = array();

    //lets add our fields to the array
    $pluginSettings['access_token'] = $accessToken;
    $pluginSettings['refresh_token'] = $refreshToken;

    //update the options
    update_option('wp_google_my_business_auto_publish_auth_settings', $pluginSettings);

    //set the transient
    set_transient( 'wp_google_my_business_auto_publish_auth_settings',$accessToken,MINUTE_IN_SECONDS*45);

    //delete the transients when reauthenticating in case people added new locations or accounts
    delete_transient('wp_google_my_business_auto_publish_accounts');
    delete_transient('wp_google_my_business_auto_publish_locations');
    delete_transient('wp_google_my_business_auto_publish_location_images');

    echo 'SUCCESS';

    wp_die(); 
    
    
}
add_action( 'wp_ajax_save_authentication_details', 'wp_google_my_business_auto_publish_save_authentication_details' );
/**
* 
*
*
* function to get access token when transient expires
*/
function wp_google_my_business_auto_publish_get_access_token() {
    
    
        $getTransient = get_transient('wp_google_my_business_auto_publish_auth_settings');
        
        //if the transient exists
        if ($getTransient != false){
            
            return $getTransient;
  
        } else {

            
            //the transient doesn't exist therefore do api call

            $pluginSettings = get_option('wp_google_my_business_auto_publish_auth_settings');

            //current refresh token
            $currentRefreshToken = $pluginSettings['refresh_token'];


            //do response
            $response = wp_remote_post( 'https://www.googleapis.com/oauth2/v4/token?refresh_token='.$currentRefreshToken.'&client_id=979275334189-mqphf6kpvpji9km7i6pm0sq5ddvfoa60.apps.googleusercontent.com&client_secret=hENqfr4whG7qs5QxSSzOa9_s&grant_type=refresh_token' );

            if ( ! is_wp_error( $response ) ) {
                // The request went through successfully, check the response code against
                // what we're expecting
                if ( 200 == wp_remote_retrieve_response_code( $response ) ) {

 
                    //get new acess token and refresh token
                         
                    $jsondata = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true); 

                    $newAccessToken = sanitize_text_field($jsondata['access_token']);
                    //$newRefreshToken = $jsondata['refresh_token'];

                    //now we need to update the settings
                    //set the new values from the existing array
                    $pluginSettings['access_token'] = $newAccessToken;
                    //$pluginSettings['refresh_token'] = $newRefreshToken;

                    //update the option
                    update_option('wp_google_my_business_auto_publish_auth_settings', $pluginSettings);
                    
                    //set the transient
                    //we will make this transient expire just before 60 minutes
                    set_transient( 'wp_google_my_business_auto_publish_auth_settings',$newAccessToken,MINUTE_IN_SECONDS*45);
                    
                    //return the array
                    return $newAccessToken;


                } else {
                    
                    //we can put some diagnostic info here if we wanted to
                    return 'ERROR';
                    
                }
            } else {
                return 'ERROR';
                //we can put some diagnostic info here if we wanted to
                
            } 
     
    }
    
}
/**
* 
*
*
* Function share post on google
*/
function wp_google_my_business_auto_publish_send_to_google ($new_status, $old_status, $post) {

    //if the old status isn't published and the new statusis carry out the share to google
    if ('publish' === $new_status) {
        
        //get options
        $options = get_option( 'wp_google_my_business_auto_publish_settings' );

        //get categories user has chosen not to share and separate comma values and turn it into an array
        $explodedCategories = explode(",",$options['wp_google_my_business_auto_publish_dont_share_categories']);

        //get the current category
        $thePostCategory = get_the_category($post->ID);
        $thePostCategoryArray = array();

        foreach($thePostCategory as $categoryName){
            array_push($thePostCategoryArray,$categoryName->name);       
        }

        //compare the 2 arrays and count how many duplicates there are 
        $thePostCategoryComparison = count(array_intersect($explodedCategories,$thePostCategoryArray));    

        //get the custom post types the user has nominated to share    
        $explodedPostTypes = explode(",",$options['wp_google_my_business_auto_publish_dont_share_types']);
        $explodedPostTypes = array_map('strtolower', $explodedPostTypes);
        $postType = $post->post_type;

        //first check if the user has decided to not share the post and check if the user has nominated to not share category belonging to the post and then check if the user has nominated to share the post type whether this be a post, page or custom post type
        if(get_post_meta($post->ID, '_dont_share_post_google', true ) !== "yes" && $thePostCategoryComparison == 0 && in_array($postType,$explodedPostTypes)) {    

            //call share function here
            wp_google_my_business_auto_publish_send_to_google_common($post->ID);
   

        } //end if user has decided to share post
    } //end if post transition has gone to published
}
add_action( 'transition_post_status', 'wp_google_my_business_auto_publish_send_to_google', 10, 3 );
/**
* 
*
*
* common function which actually does the api call to google
*/
function wp_google_my_business_auto_publish_send_to_google_common($postID){


    //get options
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    
    //create an associative array to be used for shortcode replacement    
    $variables = array("post_title"=>html_entity_decode(get_the_title($postID)),
                       "post_excerpt"=>get_the_excerpt($postID),
                       "post_content"=>preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '',strip_tags(get_post_field('post_content',$postID))),
                       "post_author"=>get_the_author_meta('display_name',get_post_field('post_author',$postID)),
                       "website_title"=>html_entity_decode(get_bloginfo('name'))
                      );    

    //if the custom comment has been blanked out try getting the default message otherwise get the custom comment
    if(strlen(get_post_meta($postID, '_custom_google_share_message', true ))<1) {
        $googleComment = $options['wp_google_my_business_auto_publish_default_share_message'];   
    } else {
        $googleComment = get_post_meta($postID, '_custom_google_share_message', true ); 
    }

    //for each variable used replace it with the actual value  
    foreach($variables as $key => $value){
        $googleComment = str_replace('['.strtoupper($key).']', $value, $googleComment); 
    }
    //limit the comment to 1500 characters total
    $googleComment = substr($googleComment, 0, 1499);    


    //have fallback option for custom button
    if(strlen(get_post_meta($postID, '_custom_google_button', true )) > 1) {
        $actionType = get_post_meta($postID, '_custom_google_button', true );
    } elseif(isset($options['wp_google_my_business_auto_publish_default_action_type'])) {
        $actionType = $options['wp_google_my_business_auto_publish_default_action_type'];     
    } else {
        $actionType = 'LEARN_MORE';
    }
    
    

    $eventEnabled = get_post_meta($postID, '_make_an_event', true );

    
    if($eventEnabled !== "yes"){
        //its a standard post
        $topicType = 'STANDARD';
    } else {
        $topicType = 'EVENT';    
    }
    


    //do actual API call
    // Create JSON body
    $json = array(
        'topicType' => $topicType,
        'callToAction' => array(
            'url' => get_permalink($postID),
            'actionType' => $actionType
        ),
        'languageCode' => get_locale(),
        'summary' => $googleComment,
    );


    //check to see if there's a post thumbnail
    if(has_post_thumbnail($postID)){


        if(get_the_post_thumbnail_url($postID, 'full') !== false){
            $thumbnailUrl = get_the_post_thumbnail_url($postID, 'full');
        } else {
            $thumbnailUrl = get_the_post_thumbnail_url($postID);
        } 

        if($thumbnailUrl !== false){

            //do additional check for dimensions
            $imageInfo = getimagesize($thumbnailUrl);

            //get file size
            $headers = get_headers($thumbnailUrl, true);
            
            if ( isset($headers['Content-Length']) ) {
                $imageSize = intval($headers['Content-Length']);
            } else {
                $imageSize = 10241;
            }
        

            if( $imageInfo[0] > 250 && $imageInfo[1] > 250 && $imageSize > 10240 ){
                $json['media'] = array(
                    'sourceUrl' => $thumbnailUrl,
                    'mediaFormat' => 'PHOTO',
                ); 
            }
        }
    }
    

    
    //check to see if event
    if($eventEnabled == "yes"){
        
        $eventTitle = get_post_meta($postID, '_event_title', true );
        $eventStart = get_post_meta($postID, '_event_start_date_time', true );
        $eventEnd = get_post_meta($postID, '_event_end_date_time', true );
        
        
        $startDate = strtotime($eventStart);
        $endDate = strtotime($eventEnd);
        
        $startDateYear = date('Y',$startDate);
        $endDateYear = date('Y',$endDate);
        
        $startDateMonth = date('n',$startDate);
        $endDateMonth = date('n',$endDate);
        
        $startDateDay = date('j',$startDate);
        $endDateDay = date('j',$endDate);
        
        $startDateHours = date('G',$startDate);
        $endDateHours = date('G',$endDate);
        
        $startDateMinutes = intval(date('i',$startDate));
        $endDateMinutes = intval(date('i',$endDate));
        
        
        $json['event'] = array('title'=>$eventTitle,'schedule'=> array('startDate'=>array('year'=>$startDateYear,'month'=>$startDateMonth,'day'=>$startDateDay), 'startTime'=>array('hours'=>$startDateHours,'minutes'=>$startDateMinutes),'endDate'=>array('year'=>$endDateYear,'month'=>$endDateMonth,'day'=>$endDateDay), 'endTime'=>array('hours'=>$endDateHours,'minutes'=>$endDateMinutes)));   
        

    }
        


    //encode the array 
    $json = json_encode($json);
    
    //get locations we need to share to

    if(metadata_exists('post', $postID, '_location_selection_google')){
        
        $locationsToShareTo = get_post_meta($postID, '_location_selection_google', true ); 
    } else {
        $locationsToShareTo = $options['wp_google_my_business_auto_publish_default_locations'];
    }

    
    $locationsToShareToAsArray = explode(",",$locationsToShareTo);
    
    //if the array is empty return back the number
    //$countOfItemsInArray = count($locationsToShareToAsArray)-1;

    //return $countOfItemsInArray.' '.$locationsToShareTo;

    if(strlen($locationsToShareTo)<1){
        return "no profile";
    }


    //used to get location name
    $locationData = wp_google_my_business_auto_publish_get_specific_location();
    

    //create an array which will hold statuses
    $returnStatuses = array();
    
    //loop through locations
    foreach($locationsToShareToAsArray as $location){

        $response = wp_remote_post( 'https://mybusiness.googleapis.com/v4/'.$location.'/localPosts', array(
            'headers' => array(
                'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
                'Content-Type' => 'application/json; charset=utf-8',
            ),
            'body' => $json,
        ));    

        $status = wp_remote_retrieve_response_code($response);

        if ( ! is_wp_error( $response ) ) {

            

            //only save to log if successful
            if ( 200 == $status ) {

                //save the response to a new meta option 
                //get and decode the response    
                $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true); 

                //get current date and time in the wordpress format and to the wordpress timezone    
                $dateTime = date(get_option('date_format').' '.get_option('time_format'),strtotime(get_option('gmt_offset').' hours'));    

                $sharedUrl = sanitize_text_field($decodedBody['searchUrl']);

                


                //get the current time and create a link that goes to the post    
                $googleResponse = '<a target="_blank" href="'.$sharedUrl.'">'.$dateTime.' ('.$locationData[$location].')</a>';   

                //update the post meta with time and URL        
                //if the post hasn't been shared before send an array with the data if it has been shared get the existing array and append the new item to the array
                if(metadata_exists('post',$postID,'_sent_to_google')){

                    $existingShares = array();
                    foreach(get_post_meta($postID, '_sent_to_google', true ) as $share){
                        array_push($existingShares,$share); 
                    }
                    array_push($existingShares,$googleResponse);
                    update_post_meta($postID, '_sent_to_google',$existingShares);

                } else {
                    update_post_meta($postID, '_sent_to_google',array($googleResponse));     
                }   



                //add the post meta which prevents the post being shared again
                update_post_meta($postID, '_dont_share_post_google','yes');

                //push success to array
                array_push($returnStatuses,'success');

            } else {
                $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true); 
                array_push($returnStatuses,$status.' '.$decodedBody['error']['status'].' '.$decodedBody['error']['message']);
            }
        } else {
            $error_message = wp_remote_retrieve_response_message( $response );
            array_push($returnStatuses,$status.' '.$error_message);    
        }
        
    } //end for each location

    $returnMessage = 'success';

    foreach($returnStatuses as $statusItem){
        if($statusItem !== 'success'){
            $returnMessage = $statusItem;
        }
    }

    return $returnMessage;
    
}
/**
* 
*
*
* This function shares a post to google by pressing the share to google button
*/
function wp_google_my_business_auto_publish_send_to_google_instantly(){
    

    //set php variables from ajax variables
    $postID = intval($_POST['postID']);
    

    if ( ! current_user_can( 'edit_post', $postID ) ){
		wp_die();
	}
    

    //call share method
    echo wp_google_my_business_auto_publish_send_to_google_common($postID);
    
    //return success
    //echo "success";
    wp_die(); // this is required to terminate immediately and return a proper response
    
}
add_action( 'wp_ajax_post_to_google', 'wp_google_my_business_auto_publish_send_to_google_instantly' );
/**
* 
*
*
* This function updates the post meta when changed on the post
*/
function wp_google_my_business_auto_publish_update_meta_on_post(){
    
    $post = intval($_POST['postID']);
    
    if ( ! current_user_can( 'edit_post', $post ) ){
		wp_die();
	}
    
    
    $updatedShareMessage = sanitize_text_field($_POST['updatedShareMessage']);
    $updatedButton = sanitize_text_field($_POST['updatedButton']);
    $updatedDontShareAction = sanitize_text_field($_POST['updatedDontShareAction']);
    
    $makeAnEventAction = sanitize_text_field($_POST['makeAnEventAction']);
    $eventTitle = sanitize_text_field($_POST['eventTitle']);
    $eventStartDateTime = sanitize_text_field($_POST['eventStartDateTime']);
    $eventEndDateTime = sanitize_text_field($_POST['eventEndDateTime']);
    $locations = sanitize_text_field($_POST['locations']);


    update_post_meta($post, '_event_title',$eventTitle);
    update_post_meta($post, '_event_start_date_time',$eventStartDateTime);
    update_post_meta($post, '_event_end_date_time',$eventEndDateTime);
    update_post_meta($post, '_custom_google_share_message',$updatedShareMessage);
    update_post_meta($post, '_custom_google_button',$updatedButton);
    update_post_meta($post, '_location_selection_google',$locations);
    
    if($updatedDontShareAction == "update"){
        update_post_meta($post, '_dont_share_post_google','yes');     
    } else {
        delete_post_meta($post, '_dont_share_post_google');    
    }

    if($makeAnEventAction == "update"){
        update_post_meta($post, '_make_an_event','yes');     
    } else {
        delete_post_meta($post, '_make_an_event');    
    }
    

    echo "success";
    wp_die();
    

}
add_action( 'wp_ajax_update_google_post_meta', 'wp_google_my_business_auto_publish_update_meta_on_post' );
/**
* 
*
*
* Function to prevent republishing post that has already been sent to Google by default
*/
function wp_google_my_business_auto_publish_dont_republish($post_id,$post){
if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
    
    //check to see if post is published
    if('publish' == $post->post_status) { 
        update_post_meta( $post_id, '_dont_share_post_google', 'yes');    
    }  
}
add_action( 'save_post', 'wp_google_my_business_auto_publish_dont_republish',11,2);
/**
* 
*
*
* This function makes the above function only run the first time
*/
function wp_google_my_business_auto_publish_remove_function_except_first_publish()
{
  remove_action('save_post','wp_google_my_business_auto_publish_dont_republish',11,2);
}
add_action('publish_to_publish','wp_google_my_business_auto_publish_remove_function_except_first_publish');
/**
* 
*
*
* Check if it's necessary to add a column to the all pages listing
*/
function wp_google_my_business_auto_publish_page_column_required(){
    
    //get options
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    $explodedPostTypes = explode(",",$options['wp_google_my_business_auto_publish_dont_share_types']);
    $explodedPostTypes = array_map('strtolower', $explodedPostTypes);
    if(in_array("page",$explodedPostTypes)){
        return true;    
    } else {
        return false;
    }
}
/**
* 
*
*
* Create new column on the posts page
*/
function wp_google_my_business_auto_publish_additional_posts_column($columns) {
    
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    
    if(isset($options['wp_google_my_business_auto_publish_hide_posts_column'])){
        return $columns;
    } else {
        $new_columns = array(
        'shared_on_google' => __( 'Shared on Google', 'wp-google-my-business-auto-publish' ),
        );
        $filtered_columns = array_merge( $columns, $new_columns );
        return $filtered_columns;       
    }
}
add_filter('manage_posts_columns', 'wp_google_my_business_auto_publish_additional_posts_column');
if(wp_google_my_business_auto_publish_page_column_required()==true){
    add_filter('manage_page_posts_columns', 'wp_google_my_business_auto_publish_additional_posts_column');   
}
/**
* 
*
*
* Add content to the new posts page column
*/
function wp_google_my_business_auto_publish_additional_posts_column_data( $column ) {
    
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    
    // Get the post object for this row so we can output relevant data
    global $post;
  
    // Check to see if $column matches our custom column names
    switch ( $column ) {

    case 'shared_on_google' :
    if(metadata_exists('post', $post->ID, '_sent_to_google')) {
    foreach(array_reverse(get_post_meta($post->ID, '_sent_to_google', true )) as $share){
            echo $share.'</br>';
    }   
    } else {
       
        echo 'Not shared <a class="send-to-google" href="" data="'.$post->ID.'">Share now</a>';    
                
       //edit_post_link( 'share now', 'Not shared ', '', $post->ID, '');
        
        
    } 
      break;    
    }
}
add_action( 'manage_posts_custom_column', 'wp_google_my_business_auto_publish_additional_posts_column_data' );
// if pages have been opted not to be shared hide the column on the all pages listing
if(wp_google_my_business_auto_publish_page_column_required()==true){
    add_action('manage_page_posts_custom_column', 'wp_google_my_business_auto_publish_additional_posts_column_data');
}
/**
* 
*
*
* function to do a manual post to google from the plugin settings 
*/
function wp_google_my_business_auto_publish_post_now_to_google() {
    
    //if not admin dont do anything
    if (!current_user_can('manage_options')) {
        wp_die();    
    }
    
	//get options
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    
    //get the code field
    $postContent = stripslashes(sanitize_text_field($_POST['postContent']));
    $postLink = sanitize_text_field($_POST['postLink']);
    $postAction = sanitize_text_field($_POST['postAction']);
    $postImage = sanitize_text_field($_POST['postImage']);
    $eventEnable = sanitize_text_field($_POST['eventEnable']);
    $eventName = sanitize_text_field($_POST['eventName']);
    $eventStart = sanitize_text_field($_POST['eventStart']);
    $eventEnd = sanitize_text_field($_POST['eventEnd']);
    $locations = sanitize_text_field($_POST['locations']);
    $locationsArray = explode(",",$locations);

    
    if($eventEnable !== "true"){
        //its a standard post
        $topicType = 'STANDARD';
    } else {
        $topicType = 'EVENT';    
    }
    
    
    
    
    //do actual API call
    // Create JSON body
    $json = array(
        'topicType' => $topicType,
        'languageCode' => get_locale(),
        'summary' => $postContent,
    );
    
    
    //check if we need to add a link
    if(strlen($postLink) > 7 && $postLink !== 'https://'){
        $json['callToAction'] = array(
            'url' => $postLink,
            'actionType' => $postAction,
        );      
    }
    

    //check to see if there's a post thumbnail
    if( strlen($postImage) > 7 && strpos($postImage, 'http') !== false){

        //do additional check for dimensions
        $imageInfo = getimagesize($postImage);

        //get file size
        $headers = get_headers($postImage, true);
        
        if ( isset($headers['Content-Length']) ) {
            $imageSize = intval($headers['Content-Length']);
        } else {
            $imageSize = 10241;
        }

        if( $imageInfo[0] > 250 && $imageInfo[1] > 250 && $imageSize > 10240 ){
            $json['media'] = array(
                'sourceUrl' => $postImage,
                'mediaFormat' => 'PHOTO',
            ); 
        }


    }
    

    
    //check to see if event
    if($eventEnable == "true"){

        $startDate = strtotime($eventStart);
        $endDate = strtotime($eventEnd);
        
        $startDateYear = date('Y',$startDate);
        $endDateYear = date('Y',$endDate);
        
        $startDateMonth = date('n',$startDate);
        $endDateMonth = date('n',$endDate);
        
        $startDateDay = date('j',$startDate);
        $endDateDay = date('j',$endDate);
        
        $startDateHours = date('G',$startDate);
        $endDateHours = date('G',$endDate);
        
        $startDateMinutes = intval(date('i',$startDate));
        $endDateMinutes = intval(date('i',$endDate));
        
        
        $json['event'] = array('title'=>$eventName,'schedule'=> array('startDate'=>array('year'=>$startDateYear,'month'=>$startDateMonth,'day'=>$startDateDay), 'startTime'=>array('hours'=>$startDateHours,'minutes'=>$startDateMinutes),'endDate'=>array('year'=>$endDateYear,'month'=>$endDateMonth,'day'=>$endDateDay), 'endTime'=>array('hours'=>$endDateHours,'minutes'=>$endDateMinutes)));   

    }
        

    //encode the array 
    $json = json_encode($json);

    //echo $json;
    
    
    $responseMessage = '';
    
    $locationData = wp_google_my_business_auto_publish_get_specific_location();
    
    
    //loop through each account
    foreach($locationsArray as $location){
        
        $response = wp_remote_post( 'https://mybusiness.googleapis.com/v4/'.$location.'/localPosts', array(
            'headers' => array(
                'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
                'Content-Type' => 'application/json; charset=utf-8',
            ),
            'body' => $json,
        ));    



        //only save to log if successful
        if ( 200 == wp_remote_retrieve_response_code($response) ) {

            $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true); 

            $sharedUrl = sanitize_text_field($decodedBody['searchUrl']);
            
            if($responseMessage == ''){
                $responseMessage .= $sharedUrl.'|'.$locationData[$location];    
            } else {
                $responseMessage .= '||'.$sharedUrl.'|'.$locationData[$location];    
            }

        } else {
            $responseMessage .= 'ERROR';  
            // $responseMessage .= wp_remote_retrieve_response_message( $response ).' '.wp_remote_retrieve_response_code($response);
            
        }
    
    } //end for each
    
    echo $responseMessage;
    wp_die();
}
add_action( 'wp_ajax_post_now_to_google', 'wp_google_my_business_auto_publish_post_now_to_google' );
/**
* 
*
*
* function to delete post on google
*/
function wp_google_my_business_auto_publish_delete_post() {
    
	//get options
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    
    //get the code field
    $postID = $_POST['postID'];


    $response = wp_remote_request( 'https://mybusiness.googleapis.com/v4/'.$postID, array(
        'headers' => array(
            'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
        ),
        'method' => 'DELETE',
    ));


    
    //only save to log if successful
    if ( 200 == wp_remote_retrieve_response_code($response) ) {
        echo 'SUCCESS';
        wp_die();    
    } else {
        echo 'ERROR';
        wp_die();    
    }
    

    
}
add_action( 'wp_ajax_delete_post_on_google', 'wp_google_my_business_auto_publish_delete_post' );
/**
* 
*
*
* function to dismiss welcome message for current version
*/
function wp_google_my_business_auto_publish_dismiss_welcome_message() {
    
    if (!current_user_can('manage_options')) {
        wp_die();    
    }
    
    
	//get options
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    
    $pluginVersion = sanitize_text_field($_POST['pluginVersion']);
    
    $options['wp_google_my_business_auto_publish_dismiss_welcome_message'] = $pluginVersion;
    
    //update the options
    update_option('wp_google_my_business_auto_publish_settings', $options);
    
    echo 'SUCCESS';
    wp_die();    
    
    
}
add_action( 'wp_ajax_dismiss_welcome_message', 'wp_google_my_business_auto_publish_dismiss_welcome_message' );
/**
* 
*
*
* function to get accounts array stored in transients
*/
function wp_google_my_business_auto_publish_get_accounts() {
    
    $transientName = 'wp_google_my_business_auto_publish_accounts';
    
    $getTransient = get_transient($transientName);
        
    //if the transient exists
    if ($getTransient != false){

        return $getTransient;

    } else {
        
        //there's no transient so do the api call
        $response = wp_remote_get( 'https://mybusiness.googleapis.com/v4/accounts', array(
            'headers' => array(
                'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
            ),
        ));

 
        if(200 == wp_remote_retrieve_response_code( $response )) {

            $jsondata = json_decode($response['body'],true); 

            $accounts = $jsondata['accounts'];

            set_transient($transientName,$accounts,DAY_IN_SECONDS*7);
            
            return $accounts;

        } else {
            
            return 'ERROR';
            
        }
        
    }
    
}
/**
* 
*
*
* function to get locations array stored in transients
*/
function wp_google_my_business_auto_publish_get_locations() {
    
    $transientName = 'wp_google_my_business_auto_publish_locations';
    
    $getTransient = get_transient($transientName);
        
    //if the transient exists
    if ($getTransient != false){

        return $getTransient;

    } else {
        
        //there's no transient so do the api call
        $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
        $accountName = $pluginSettings['wp_google_my_business_auto_publish_account_selection'];
        
        $response = wp_remote_get( 'https://mybusiness.googleapis.com/v4/'.$accountName.'/locations', array(
            'headers' => array(
                'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
            ),
        ));
        
 
        if(200 == wp_remote_retrieve_response_code( $response )) {

            $jsondata = json_decode($response['body'],true); 

            $locations = $jsondata['locations'];

            set_transient($transientName,$locations,DAY_IN_SECONDS*7);
            
            return $locations;

        } else {
            
            return 'ERROR';
            
        }
        
    }
    
}
/**
* 
*
*
* function to get locations as an associative array - good for getting the name of a specific location
*/
function wp_google_my_business_auto_publish_get_specific_location() {
    
    $getLocations = wp_google_my_business_auto_publish_get_locations();

    if($getLocations !== 'ERROR'){
        //turn locations data into retrievable data via an associative array
        $locationData = array();
        foreach($getLocations as $getLocation){
            $locationData[$getLocation['name']] = $getLocation['locationName'];    
        }
        
        return $locationData;

    }
    
    
    
}
/**
* 
*
*
* function to get location images, thanks Google for making this so difficult compared to API v3!
*/
function wp_google_my_business_auto_publish_get_location_images() {
    
    $transientName = 'wp_google_my_business_auto_publish_location_images';
    
    $getTransient = get_transient($transientName);
        
    //if the transient exists
    if ($getTransient != false){

        return $getTransient;

    } else {
        
        //there's no transient so do the api call
        
        $existingLocations = wp_google_my_business_auto_publish_get_locations();
        
        $locationImagesArray = array();
        
        
        if($existingLocations !== "ERROR"){
            foreach($existingLocations as $location){
                
                $locationName = $location['name'];
                
                //now lets get the profile image
                $response = wp_remote_get('https://mybusiness.googleapis.com/v4/'.$locationName.'/media', array(
                    'headers' => array(
                        'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
                    ),
                ));
                
                if(200 == wp_remote_retrieve_response_code( $response )) {

                    $jsondata = json_decode($response['body'],true); 

                    $locationImages = $jsondata['mediaItems'];

                    // var_dump($locationImages);
                    
                    //now cycle through all the images
                    foreach($locationImages as $locationImage){
                        if($locationImage['locationAssociation']['category'] == 'PROFILE'){
                            $locationProfileImage = $locationImage['thumbnailUrl'];  
                        }
                        
                    } //end foreach
                    
                    if(isset($locationProfileImage)){ 
                        $locationImagesArray[$locationName] = $locationProfileImage;    
                    }
                    
                    
                } //end if success
                
            } //end foreach location
            
            //now lets save this array as a long lasting transient
            set_transient($transientName,$locationImagesArray,DAY_IN_SECONDS*365);
            return $locationImagesArray;
            
        } //end if error 
 
    } //end if transient exists
    
}
/**
* 
*
*
* function to delete transients
*/
function wp_google_my_business_auto_publish_delete_transients() {
    
    if (!current_user_can('manage_options')) {
        wp_die();    
    }
    
    $transientName = sanitize_text_field($_POST['transientName']);
    
    //delete the transient
    delete_transient($transientName);
    echo 'SUCCESS';
    wp_die();    
    
    
}
add_action( 'wp_ajax_delete_transient', 'wp_google_my_business_auto_publish_delete_transients' );
/**
* 
*
*
* function to create common form (used for both creating and editing posts)
*/
function wp_google_my_business_auto_publish_create_edit_form($postContent,$postLink,$postAction,$postImage,$makeAnEvent,$eventName,$eventStart,$eventEnd) {
    
    $html = '';
    
    //post content
    $html .= '<p class="google-post-field">';
    $html .= '<label>'.__('Post Content', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<textarea maxlength="1500" style="width: 100%;" id="post-content" rows="6">'.$postContent.'</textarea>';
    
    $html .= '<span class="textarea-counter"><span class="counter-number">0</span>/1500</span>';
    
    $html .= '</p>';
    
    
    //post link
    $html .= '<p class="google-post-field">';
    $html .= '<label>'.__('Post Link', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<input type="text" id="post-link" value="'.$postLink.'">';
        
    $html .= '</p>';
    
    
    //post action
    $html .= '<p class="google-post-field">';
    $html .= '<label>'.__('Post Action', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<select id="post-action">';
    
        $values = array('LEARN_MORE' => 'Learn More','BOOK' => 'Book','ORDER' => 'Order','SHOP' => 'Shop','SIGN_UP' => 'Sign Up');
    
        foreach($values as $key=>$value){
            
            if($key == $postAction){
                $selected = 'selected="selected"';    
            } else {
                $selected = '';        
            }
            
            $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';   
        }

    $html .= '</select>';
    $html .= '</p>';
        
        
    //post image
    $html .= '<p class="google-post-field">';
    $html .= '<label>'.__('Post Image', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<input type="text" id="post-image" value="'.$postImage.'"> <button type="button" name="upload-btn" id="upload-btn" class="button-secondary"><i class="fa fa-picture-o" aria-hidden="true"></i> '.__('Upload Image', 'wp-google_business-auto_publish').'</button>';
    
    if($postImage == ''){
        $postImageStyle = 'display: none;';
    } else {
        $postImageStyle = '';     
    }
    
    $html .= '<img id="imagePreview" style="'.$postImageStyle.' margin-top: 10px; object-fit: cover; border-radius: 4px !important;"  src="'.$postImage.'" class="avatar avatar-96 photo" height="96" width="96">';
        
    $html .= '</p>';    
                        
   
    //make an event
    $html .= '<p class="google-post-field">';
    $html .= '<label>'.__('Make an Event', 'wp-google_business-auto_publish').'</label>';
    
    if($makeAnEvent == "EVENT"){
        $checked = 'checked';  
    } else {
        $checked = '';     
    }
    
    $html .= '<input type="checkbox" class="event-enable" id="event-enable" value="1" '.$checked.'>';
        
    $html .= '</p>';         

                      
    //event name
    $html .= '<p class="google-post-field event-option">';
    $html .= '<label>'.__('Event Name', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<input type="text" id="event-name" value="'.$eventName.'">';
        
    $html .= '</p>';
    
    //event start
    $html .= '<p class="google-post-field event-option">';
    $html .= '<label>'.__('Event Start Date/Time', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<input type="text" id="event-start" value="'.$eventStart.'" class="date-time-input">';
        
    $html .= '</p>';
    
    //event end
    $html .= '<p class="google-post-field event-option">';
    $html .= '<label>'.__('Event End Date/Time', 'wp-google_business-auto_publish').'</label>';
    
    $html .= '<input type="text" id="event-end" value="'.$eventEnd.'" class="date-time-input">';
        
    $html .= '</p>';

    return $html;
    
}
/**
* 
*
*
* function to return form via ajax for edit screen
*/
function wp_google_my_business_auto_publish_get_post_form() {
    
    if (!current_user_can('manage_options')) {
        wp_die();    
    }
    
    $postContent = sanitize_text_field($_POST['postContent']);
    $postLink = sanitize_text_field($_POST['postLink']);
    $postAction = sanitize_text_field($_POST['postAction']);
    $postImage = sanitize_text_field($_POST['postImage']);
    $makeAnEvent = sanitize_text_field($_POST['makeAnEvent']);
    $eventName = sanitize_text_field($_POST['eventName']);
    $eventStart = sanitize_text_field($_POST['eventStart']);
    $eventEnd = sanitize_text_field($_POST['eventEnd']);

    echo wp_google_my_business_auto_publish_create_edit_form($postContent,$postLink,$postAction,$postImage,$makeAnEvent,$eventName,$eventStart,$eventEnd);
    
    wp_die();    
    
}
add_action( 'wp_ajax_get_post_form', 'wp_google_my_business_auto_publish_get_post_form' );
/**
* 
*
*
* function to update the post via ajax
*/
function wp_google_my_business_auto_publish_update_google_post() {
    
    if (!current_user_can('manage_options')) {
        wp_die();    
    }
    
    $postID = sanitize_text_field($_POST['postID']);
    $postContent = sanitize_text_field($_POST['postContent']);
    $postLink = sanitize_text_field($_POST['postLink']);
    $postAction = sanitize_text_field($_POST['postAction']);
    $originalPostImage = sanitize_text_field($_POST['originalPostImage']);
    $postImage = sanitize_text_field($_POST['postImage']);
    $eventEnable = sanitize_text_field($_POST['eventEnable']);
    $eventName = sanitize_text_field($_POST['eventName']);
    $eventStart = sanitize_text_field($_POST['eventStart']);
    $eventEnd = sanitize_text_field($_POST['eventEnd']);
    
    
    if($eventEnable !== "true"){
        //its a standard post
        $topicType = 'STANDARD';
    } else {
        $topicType = 'EVENT';    
    }
    
    
    //do actual API call
    // Create JSON body
    $json = array(
        'topicType' => $topicType,
        'languageCode' => get_locale(),
        'summary' => $postContent,
    );
    
    
    //check if we need to add a link
    if(strlen($postLink) > 7 && $postLink !== 'https://'){
        $json['callToAction'] = array(
            'url' => $postLink,
            'actionType' => $postAction,
        );      
    }
    

    //check to see if there's a post thumbnail
    if( strlen($postImage) > 7 && strpos($postImage, 'http') !== false){

        //do additional check for dimensions
        $imageInfo = getimagesize($postImage);

        //get file size
        $headers = get_headers($postImage, true);
        
        if ( isset($headers['Content-Length']) ) {
            $imageSize = intval($headers['Content-Length']);
        } else {
            $imageSize = 10241;
        }


        if( $imageInfo[0] > 250 && $imageInfo[1] > 250 && $imageSize > 10240 ){
            $json['media'] = array(
                'sourceUrl' => $postImage,
                'mediaFormat' => 'PHOTO',
            ); 

             //if the original image is a good image lets put that into the update make
            if (strpos($originalPostImage, 'googleusercontent.com') !== false) {
                
                //get the image identifier
                $lastSlashInString = strrpos($originalPostImage, "/")+1;
                $imageIdentifier = substr($originalPostImage,$lastSlashInString);
                
                $imageUpdateMask = ',media.'.$imageIdentifier.'.mediaFormat,media.'.$imageIdentifier.'.googleUrl';
                
            } else {
                $imageUpdateMask = ',media.a.mediaFormat,media.a.googleUrl';    
            }
        }

             
    } else {
        $imageUpdateMask = '';     
    }
    
    //echo $imageUpdateMask;

    
    //check to see if event
    if($eventEnable == "true"){

        $startDate = strtotime($eventStart);
        $endDate = strtotime($eventEnd);
        
        $startDateYear = date('Y',$startDate);
        $endDateYear = date('Y',$endDate);
        
        $startDateMonth = date('n',$startDate);
        $endDateMonth = date('n',$endDate);
        
        $startDateDay = date('j',$startDate);
        $endDateDay = date('j',$endDate);
        
        $startDateHours = date('G',$startDate);
        $endDateHours = date('G',$endDate);
        
        $startDateMinutes = intval(date('i',$startDate));
        $endDateMinutes = intval(date('i',$endDate));
        
        
        $json['event'] = array('title'=>$eventName,'schedule'=> array('startDate'=>array('year'=>$startDateYear,'month'=>$startDateMonth,'day'=>$startDateDay), 'startTime'=>array('hours'=>$startDateHours,'minutes'=>$startDateMinutes),'endDate'=>array('year'=>$endDateYear,'month'=>$endDateMonth,'day'=>$endDateDay), 'endTime'=>array('hours'=>$endDateHours,'minutes'=>$endDateMinutes)));   

    }
        

    //encode the array 
    $json = json_encode($json);
    
    //echo $json;


    $response = wp_remote_request( 'https://mybusiness.googleapis.com/v4/'.$postID.'?updateMask=languageCode,summary,callToAction.actionType,callToAction.url,event.title,event.schedule.startDate.year,event.schedule.startDate.month,event.schedule.startDate.day,event.schedule.startTime.hours,event.schedule.startTime.minutes'.$imageUpdateMask, array(
        'headers' => array(
            'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
            'Content-Type' => 'application/json; charset=utf-8',
        ),
        'method' => 'PATCH',
        'body' => $json,
    ));


    
    //only save to log if successful
    if ( 200 == wp_remote_retrieve_response_code($response) ) {
        echo 'SUCCESS';
        wp_die();    
    } else {
        echo 'ERROR';
        wp_die();    
    }
    

}
add_action( 'wp_ajax_update_google_post', 'wp_google_my_business_auto_publish_update_google_post' );
/**
* 
*
*
* function to render location list items with particular items selected
*/
function wp_google_my_business_auto_publish_render_location_list_items($selectedItems) {
    
    
    //start output
    $html = '';
    
    //get options and variables
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
    $enabledLocations = $options['wp_google_my_business_auto_publish_location_selection'];
    $enabledLocationsAsArray = explode(",",$enabledLocations);
    $getLocationImages = wp_google_my_business_auto_publish_get_location_images();

    // print_r($getLocationImages);
    
    //turn locations data into retrievable data via an associative array
    $locationData = wp_google_my_business_auto_publish_get_specific_location();
    
    foreach($enabledLocationsAsArray as $location){
        
        $locationId = $location;
        $locationName = $locationData[$locationId];
        

        //check if list item is in setting
        if(in_array($locationId, $selectedItems) || in_array('SHOW ALL ITEMS', $selectedItems)){
            $listClass = 'selected';
            $iconClass = 'fa-check-circle-o';
        } else {
            $listClass = ''; 
            $iconClass = 'fa-times-circle-o';
        }


        $html .= '<li title="'.$locationName.'" class="location-list-item-small '.$listClass.'" data="'.$locationId.'">';


            //image
            //only do image if image exists
            if(is_array($getLocationImages)){
                if(array_key_exists($locationId,$getLocationImages)){
                    $locationImage = $getLocationImages[$locationId]; 
                    $html .= '<img src="'.$locationImage.'" class="location-image" height="20" width="20">';
                }
            }
            

            //location information
            $html .= '<div class="location-information">';

                //name
                $html .= '<span class="location-name">'.$locationName.'</span>';

            $html .= '</div>';

            //render appropriate icon
            $html .= '<i class="location-selected-icon fa '.$iconClass.'" aria-hidden="true"></i>';


        $html .= '</li>';
        
    }
    

    
    return $html;
    
    
    
}
/**
* 
*
*
* function to get reviews for a particular location
*/
function wp_google_my_business_auto_publish_get_reviews($location) {

    if($location == ''){
        return 'ERROR';
    }

    //parse location
    $locationParsed = explode('/',$location);
    $justLocation = $locationParsed[3];

    $transientName = 'reviews_'.$justLocation;
    
    $getTransient = get_transient($transientName);
        
    //if the transient exists
    if ($getTransient != false){

        return $getTransient;

    } else {
        
        //there's no transient so do the api call
        $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
        $accountName = $pluginSettings['wp_google_my_business_auto_publish_account_selection'];
        
        $response = wp_remote_get( 'https://mybusiness.googleapis.com/v4/'.$location.'/reviews?pageSize=200', array(
            'headers' => array(
                'Authorization' => 'Bearer '.wp_google_my_business_auto_publish_get_access_token(),
            ),
        ));
        
 
        if(200 == wp_remote_retrieve_response_code( $response )) {

            $jsondata = json_decode($response['body'],true); 

            $reviews = $jsondata['reviews'];

            set_transient($transientName,$reviews,DAY_IN_SECONDS*1);
            
            return $reviews;

        } else {
            
            return 'ERROR';
            
        }
        
    }



}
/**
* 
*
*
* This function updates the shortcode preview
*/
function wp_google_my_business_auto_publish_update_shortcode(){
    
    if (!current_user_can('manage_options')) {
        wp_die();    
    }

    //set php variables from ajax variables

    $location = $_POST['location'];
    $type = $_POST['type'];
    $minimumStars = intval($_POST['minimumStars']);
    $sortBy = $_POST['sortBy'];
    $sortOrder = $_POST['sortOrder'];
    $reviewAmount = intval($_POST['reviewAmount']);
    $slidesPage = intval($_POST['slidesPage']);
    $slidesScroll = intval($_POST['slidesScroll']);
    $autoplay = $_POST['autoplay'];
    $speed = intval($_POST['speed']);
    $transition = $_POST['transition'];
    $readMore = $_POST['readMore'];
    $showStars = $_POST['showStars'];
    $showDate = $_POST['showDate'];
    $showQuotes = $_POST['showQuotes'];

    //return success
    echo wp_google_my_business_auto_publish_review_shortcode_content($location,$type,$minimumStars,$sortBy,$sortOrder,$reviewAmount,$slidesPage,$slidesScroll,$autoplay,$speed,$transition,$readMore,$showStars,$showDate,$showQuotes);

    wp_die(); // this is required to terminate immediately and return a proper response
    
}
add_action( 'wp_ajax_update_shortcode_preview', 'wp_google_my_business_auto_publish_update_shortcode' );
/**
* 
*
*
* delete all plugin settings
*/
function wp_google_my_business_auto_publish_delete_all_plugin_settings() {
    
    //delete options    
    delete_option('wp_google_my_business_auto_publish_auth_settings');
    delete_option('wp_google_my_business_auto_publish_settings');

    //delete transients
    delete_transient('wp_google_my_business_auto_publish_location_images');
    delete_transient('wp_google_my_business_auto_publish_accounts');
    delete_transient('wp_google_my_business_auto_publish_locations');
    delete_transient('wp_google_my_business_auto_publish_auth_settings');

    echo 'SUCCESS';

    wp_die(); 
    
}
add_action( 'wp_ajax_delete_gmb_settings', 'wp_google_my_business_auto_publish_delete_all_plugin_settings' );



?>