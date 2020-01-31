<?php
wp_nonce_field('b2s_security_nonce', 'b2s_security_nonce');
require_once B2S_PLUGIN_DIR . 'includes/B2S/Settings/Item.php';
require_once B2S_PLUGIN_DIR . 'includes/Options.php';
$settingsItem = new B2S_Settings_Item();
?>

<div class="b2s-container">
    <div class=" b2s-inbox col-md-12 del-padding-left">
        <div class="col-md-9 del-padding-left del-padding-right">
            <!--Header|Start - Include-->
            <?php require_once (B2S_PLUGIN_DIR . 'views/b2s/html/header.php'); ?>
            <!--Header|End-->
            <div class="clearfix"></div>
            <!--Content|Start-->
            <div class="panel panel-group b2s-upload-image-no-permission" style="display:none;">
                <div class="panel-body">
                    <span class="glyphicon glyphicon-remove glyphicon-danger"></span> <?php esc_html_e('You need a higher user role to upload an image on this blog. Please contact your administrator.', 'blog2social'); ?>
                </div>
            </div>  
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">                       
                        <div class="b2s-post"></div>
                        <div class="row b2s-loading-area width-100" style="display: none;">
                            <div class="b2s-loader-impulse b2s-loader-impulse-md"></div>
                            <div class="text-center b2s-loader-text"><?php esc_html_e("save...", "blog2social"); ?></div>
                        </div>
                        <div class="row b2s-user-settings-area">
                            <ul class="nav nav-pills">
                                <li class="active">
                                    <a href="#b2s-general" class="b2s-general" data-toggle="tab"><?php esc_html_e('General', 'blog2social') ?></a>
                                </li>
                                <li>
                                    <a href="#b2s-auto-posting" class="b2s-auto-posting" data-toggle="tab"><?php esc_html_e('Auto-Posting', 'blog2social') ?></a>
                                </li>
                                <li>
                                    <a href="#b2s-social-meta-data" class="b2s-social-meta-data" data-toggle="tab"><?php esc_html_e('Social Meta Data', 'blog2social') ?></a>
                                </li>
                            </ul>
                            <hr class="b2s-settings-line">
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="b2s-general">
                                    <?php echo $settingsItem->getGeneralSettingsHtml(); ?>
                                </div>
                                <div class="tab-pane" id="b2s-auto-posting">
                                    <?php echo $settingsItem->getAutoPostingSettingsHtml(); ?>
                                </div> 
                                <div class="tab-pane" id="b2s-social-meta-data">
                                    <form class="b2sSaveSocialMetaTagsSettings" method="post" novalidate="novalidate">
                                        <?php echo $settingsItem->getSocialMetaDataHtml(); ?>
                                            <button class="btn btn-primary pull-right" type="submit" <?php if(B2S_PLUGIN_ADMIN) { echo ''; } else { echo 'disabled="true"'; } ?>><?php esc_html_e('save', 'blog2social') ?></button>
                                        <input type="hidden" name="is_admin" value="<?php echo ((B2S_PLUGIN_ADMIN) ? 1 : 0) ?>">
                                        <input type="hidden" name="version" value="<?php echo B2S_PLUGIN_USER_VERSION ?>">
                                        <input type="hidden" name="action" value="b2s_save_social_meta_tags">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="b2s_user_version" value="<?php echo B2S_PLUGIN_USER_VERSION; ?>" />
                        <?php
                        $noLegend = 1;
                        require_once (B2S_PLUGIN_DIR . 'views/b2s/html/footer.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once (B2S_PLUGIN_DIR . 'views/b2s/html/sidebar.php'); ?>
    </div>
</div>

<input type="hidden" id="b2sLang" value="<?php echo substr(B2S_LANGUAGE, 0, 2); ?>">
<input type="hidden" id="b2sUserLang" value="<?php echo strtolower(substr(get_locale(), 0, 2)); ?>">
<input type="hidden" id="b2sShowSection" value="<?php echo (isset($_GET['show']) ? esc_attr($_GET['show']) : ''); ?>">
<input type="hidden" id="b2s_wp_media_headline" value="<?php esc_html_e('Select or upload an image from media gallery', 'blog2social') ?>">
<input type="hidden" id="b2s_wp_media_btn" value="<?php esc_html_e('Use image', 'blog2social') ?>">
<input type="hidden" id="b2s_user_version" value="<?php echo B2S_PLUGIN_USER_VERSION ?>">
<input type="hidden" id="b2sServerUrl" value="<?php echo B2S_PLUGIN_SERVER_URL; ?>">


<div class="modal fade" id="b2sInfoAllowShortcodeModal" tabindex="-1" role="dialog" aria-labelledby="b2sInfoAllowShortcodeModal" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="b2s-modal-close close" data-modal-name="#b2sInfoAllowShortcodeModal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Allow shortcodes in my post', 'blog2social') ?></h4>
            </div>
            <div class="modal-body">
                <?php esc_html_e('Shortcodes are used by some wordpress plugins like Elementor, Visual Composer and Content Builder. When a shortcode is inserted in a WordPress post or page, it is replaced with some other content when you publish the article on your blog. In other words, a shortcode instructs WordPress to find a special command that is placed in square brackets ([]) and replace it with the appropriate dynamic content by a plugin you use.<br><br>Activate this feature, if you should use dynamic elements in your articles.', 'blog2social') ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="b2sInfoAllowHashTagModal" tabindex="-1" role="dialog" aria-labelledby="b2sInfoAllowHashTagModal" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="b2s-modal-close close" data-modal-name="#b2sInfoAllowHashTagModal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Include WordPress tags as hashtags in your posts', 'blog2social') ?></h4>
            </div>
            <div class="modal-body">
                <?php esc_html_e('Hashtags are a great way to generate more reach and visibility for your posts. By activating this feature Blog2Social will automatically include your WordPress tags as hashtags in all Social Media posts for networks that support hashtags. This way you don\'t need to worry about adding extra hashtags to your comments. Blog2Social erases unnecessary spaces in your WordPress tags to generate valid hashtags.', 'blog2social') ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="b2sInfoLegacyMode" tabindex="-1" role="dialog" aria-labelledby="b2sInfoLegacyMode" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="b2s-modal-close close" data-modal-name="#b2sInfoLegacyMode" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Activate Legacy mode ', 'blog2social') ?></h4>
            </div>
            <div class="modal-body">
                <?php esc_html_e('Plugin contents are loaded one at a time to minimize server load.', 'blog2social') ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="b2sInfoNoCache" tabindex="-1" role="dialog" aria-labelledby="b2sInfoNoCache" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="b2s-modal-close close" data-modal-name="#b2sInfoNoCache" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Instant Caching for Facebook Link Posts', 'blog2social') ?></h4>
            </div>
            <div class="modal-body">
                <?php esc_html_e('Please enable this feature, if you are using varnish caching (HTTP accelerator to relieve your website). Blog2Social will add a "no-cache=1" parameter to the post URL of your Facebook link posts to ensure that Facebook always pulls the current meta data of your blog post.', 'blog2social') ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="b2sInfoTimeZoneModal" tabindex="-1" role="dialog" aria-labelledby="b2sInfoTimeZoneModal" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="b2s-modal-close close" data-modal-name="#b2sInfoTimeZoneModal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Personal Time Zone', 'blog2social') ?></h4>
            </div>
            <div class="modal-body">
                <?php esc_html_e('Blog2Social applies the scheduled time settings based on the time zone defined in the general settings of your WordPress. You can select a user-specific time zone that deviates from the Wordpress system time zone for your social media scheduling.<br><br>Select the desired time zone from the drop-down menu.', 'blog2social') ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="b2sTwitterInfoModal" tabindex="-1" role="dialog" aria-labelledby="b2sTwitterInfoModal" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="b2s-modal-close close" data-modal-name="#b2sTwitterInfoModal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Select Twitter profile:', 'blog2social') ?></h4>
            </div>
            <div class="modal-body">
                <?php esc_html_e('To comply with the Twitter TOS and to avoid duplicate posts, autoposts will be sent to your primary Twitter profile.', 'blog2social') ?> <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('network_tos_faq_032018') ?>"><?php esc_html_e('More information', 'blog2social') ?></a>
            </div>
        </div>
    </div>
</div>






