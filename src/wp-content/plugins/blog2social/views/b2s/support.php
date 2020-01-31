<?php wp_nonce_field('b2s_security_nonce', 'b2s_security_nonce'); ?>
<div class="b2s-container">
    <div class="b2s-inbox">
        <div class="col-md-12 del-padding-left">
            <div class="col-md-9 del-padding-left del-padding-right">
                <!--Header|Start - Include-->
                <?php require_once (B2S_PLUGIN_DIR . 'views/b2s/html/header.php'); ?>
                <!--Header|End-->
                <div class="clearfix"></div>
                <!--Content|Start-->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!--Menu|Start - Support-->
                        <ul class="nav nav-pills b2s-support-menu">
                            <li class="active">
                                <a href="#b2s-support-faq" class="b2s-support-faq" data-toggle="tab"><?php esc_html_e('FAQ', 'blog2social') ?></a>
                            </li>
                            <li>
                                <a href="#b2s-support-check-system" class="b2s-support-check-sytem" data-toggle="tab"><?php esc_html_e('Troubleshooting-Tool', 'blog2social') ?> <span class="label label-success"><?php esc_html_e("NEW", "blog2social") ?></span></a>
                            </li>
                            <li>
                                <a href="#b2s-support-sharing-debugger" class="b2s-support-sharing-debugger" data-toggle="tab"><?php esc_html_e('Sharing-Debugger', 'blog2social') ?> <span class="label label-success"><?php esc_html_e("NEW", "blog2social") ?></span></a>
                            </li>
                            <li>
                                <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('howto'); ?>"><?php esc_html_e('Step-by-Step-Guide', 'blog2social') ?></a>
                            </li>
                        </ul>
                        <hr class="b2s-support-line">
                        <!--Menu|End - Support-->
                        <div class="tab-content clearfix">
                            <div class="tab-pane active" id="b2s-support-faq">
                                <div class="row">
                                    <br>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <form action="<?php echo B2S_Tools::getSupportLink('faq_direct'); ?>" method="GET" target="_blank">
                                                <input type="hidden" name="action" value="search" />
                                                <div class="input-group">
                                                    <span class="input-group-addon btn-primary b2s-color-white b2s-text-bold hidden-xs"><?php esc_html_e('Search all support', 'blog2social') ?></span>
                                                    <input type="text" name="search" placeholder="<?php esc_html_e('Entry keyword or ask a question', 'blog2social') ?>" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-12">
                                            <h3><?php esc_html_e('Support Topics', 'blog2social') ?></h3>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_installation'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Installation', 'blog2social') ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_network'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Connecting Social Networks', 'blog2social') ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_sharing'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Autoposting, Sharing und Re-Sharing', 'blog2social') ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_customize'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Customizing Social Media Posts', 'blog2social') ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a> </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_scheduling'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Scheduling and Best Time Manager', 'blog2social') ?></a>
                                                            </div>
                                                        </div></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a> </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_licence'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Contracting and Licensing', 'blog2social') ?></a>
                                                            </div>
                                                        </div></div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a> </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_troubleshooting'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Troubleshooting for Error Messages', 'blog2social') ?></a>
                                                            </div>
                                                        </div></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="media">
                                                        <div class="col-md-1 del-padding-left">
                                                            <a class="pull-left" href="#">
                                                                <img class="img-responsive b2s-support-topic-img" src="<?php echo plugins_url('/assets/images/support/topic.png', B2S_PLUGIN_FILE); ?>" alt="topic">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-11 del-padding-left">
                                                            <div class="media-body">
                                                                <a href="<?php echo B2S_Tools::getSupportLink('faq_settings'); ?>" class="btn btn-link btn-lg b2s-color-black"target="_blank"><?php esc_html_e('Helpful Network Settings', 'blog2social') ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-12">
                                            <h3><?php esc_html_e('TOP FAQs', 'blog2social') ?></h3>
                                            <div class="b2s-faq-area">
                                                <div class="b2s-loading-area-faq" style="display:block">
                                                    <br>
                                                    <div class="b2s-loader-impulse b2s-loader-impulse-md"></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="b2s-faq-content"></div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-12">
                                            <a target="_blank" class="btn btn-default" href="<?php echo B2S_Tools::getSupportLink('faq'); ?>">
                                                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <?php esc_html_e('Contact Support by Email', 'blog2social') ?>
                                            </a>
                                            <?php if (B2S_PLUGIN_USER_VERSION > 0) { ?>
                                                <span class="btn btn-default b2s-dashoard-btn-phone"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> <?php esc_html_e('Call us: +49 2181 7569-277', 'blog2social') ?></span>
                                                <br>
                                                <div class="b2s-info-sm"><?php esc_html_e('(Support times: from 9:00 a.m. to 5:00 p.m. CET on working days)', 'blog2social') ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="b2s-support-check-system">
                                <div class="row b2s-loading-area width-100">
                                    <br>
                                    <div class="b2s-loader-impulse b2s-loader-impulse-md"></div>
                                    <div class="clearfix"></div>
                                    <div class="text-center b2s-loader-text"><?php esc_html_e("Loading...", "blog2social"); ?></div>
                                </div>
                                <div class="row width-100" id="b2s-support-no-admin" style="display: none;">
                                    <div class="text-center b2s-text-bold"><?php esc_html_e("You need admin rights to use the Troubleshooting-Tool. Please contact your administrator.", "blog2social"); ?></div>
                                </div>
                                <div id="b2s-main-debug" style="display: none;">
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-3">
                                            <h4><?php esc_html_e("Needed", "blog2social"); ?></h4>
                                        </div>
                                        <div class="col-sm-2">
                                            <h4><?php esc_html_e("Current", "blog2social"); ?></h4>
                                        </div>
                                        <div class="col-sm-3">
                                            <button id="b2s-reload-debug-btn" class="btn btn-primary pull-right margin-right-15 b2s-margin-left-10" title="<?php esc_html_e("reload", "blog2social"); ?>"><i class="glyphicon glyphicon-refresh"></i></button>
                                            <a class="btn btn-primary pull-right b2s-support-link-not-active" title="<?php esc_html_e("Export as txt-file", "blog2social"); ?>" id="b2s-debug-export" download="blog2social-support.txt"><i class="glyphicon glyphicon-download-alt"></i></a>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div id="b2s-debug-htmlData">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="b2s-support-sharing-debugger">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><?php esc_html_e("Enter a URL to see how your link preview will look on social media.", "blog2social"); ?></h3>
                                        <div class="b2s-sharing-debugger-result-area">
                                            <div class="clearfix"></div>
                                            <br>
                                            <div>
                                                <img class="b2s-feature-img-with-24" src="<?php echo plugins_url('/assets/images/portale/1_flat.png', B2S_PLUGIN_FILE); ?>" alt="Facebook">  <span class="b2s-text-bold"><?php esc_html_e("Facebook Open Graph Meta Tags", "blog2social") ?>
                                                    | <a class="btn-link" href="<?php echo B2S_Tools::getSupportLink("open_graph_tags"); ?>" target="_blank"><?php esc_html_e("Learn how to edit and adjust Open Graph tags.", "blog2social"); ?></a>
                                                </span>
                                            </div>
                                            <div class="input-group col-md-7 b2s-padding-top-8">
                                                <input type="text" name="b2s-debug-url" class="input-sm form-control" id="b2s-debug-url" value="<?php echo get_site_url(); ?>" data-network-id="1" placeholder="<?php esc_html_e("For example your Wordpress Home Page", "blog2social"); ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary btn-sm b2s-btn-sharing-debugger" data-network-id="1" b2s-url-query="https://developers.facebook.com/tools/debug/sharing/?q="><?php esc_html_e("Debug & Preview", "blog2social") ?></button>
                                                </span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div>
                                                <img class="b2s-feature-img-with-24" src="<?php echo plugins_url('/assets/images/portale/3_flat.png', B2S_PLUGIN_FILE); ?>" alt="Linkedin">  <span class="b2s-text-bold"><?php esc_html_e("LinkedIn Post Inspector", "blog2social") ?></span>
                                            </div>
                                            <div class="input-group col-md-7 b2s-padding-top-8">
                                                <input type="text" name="b2s-debug-url" class="input-sm form-control" id="b2s-debug-url" value="<?php echo get_site_url(); ?>" data-network-id="3" placeholder="<?php esc_html_e("For example your Wordpress Home Page", "blog2social"); ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary btn-sm b2s-btn-sharing-debugger" data-network-id="3" b2s-url-query="https://www.linkedin.com/post-inspector/inspect/"><?php esc_html_e("Inspect Post", "blog2social") ?></button>
                                                </span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div>
                                                <img class="b2s-feature-img-with-24" src="<?php echo plugins_url('/assets/images/portale/2_flat.png', B2S_PLUGIN_FILE); ?>" alt="Twitter">  <span class="b2s-text-bold"><?php esc_html_e("Twitter Card Validator", "blog2social") ?>
                                                    | <a class="btn-link" href="<?php echo B2S_Tools::getSupportLink("twitter_cards"); ?>" target="_blank"><?php esc_html_e("Learn how to edit and adjust Twitter Card tags.", "blog2social"); ?></a>
                                                </span>
                                            </div>
                                            <div class="b2s-padding-top-8">
                                                <button class="btn btn-primary btn-sm b2s-btn-sharing-debugger" data-network-id="2" b2s-url-query="https://cards-dev.twitter.com/validator?url="><?php esc_html_e("Validate directly on Twitter", "blog2social") ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!--Content|End-->
            </div>
            <?php require_once (B2S_PLUGIN_DIR . 'views/b2s/html/sidebar.php'); ?>
        </div>
    </div>
</div>