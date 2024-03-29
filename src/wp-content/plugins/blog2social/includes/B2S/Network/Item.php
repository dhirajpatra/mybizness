<?php

class B2S_Network_Item {

    private $authurl;
    private $allowProfil;
    private $allowPage;
    private $allowGroup;
    private $modifyBoardAndGroup;
    private $networkKindName;
    private $oAuthPortal;
    private $mandantenId;
    private $bestTimeInfo;
    private $lang;
    private $options;
    private $userSchedData; // >5.1.0
    private $userSchedDataOld; // <5.1.0

    public function __construct($load = true) {
        $this->mandantenId = array(-1, 0); //All,Default
        if ($load) {
            $this->options = new B2S_Options(B2S_PLUGIN_BLOG_USER_ID);
            $this->userSchedData = $this->options->_getOption('auth_sched_time');
            if (!isset($this->userSchedData['time'])) {
                $this->userSchedDataOld = $this->getSchedDataByUser();
            }
            $hostUrl = (function_exists('rest_url')) ? rest_url() : get_site_url();
            $this->authurl = B2S_PLUGIN_API_ENDPOINT_AUTH . '?b2s_token=' . B2S_PLUGIN_TOKEN . '&sprache=' . substr(B2S_LANGUAGE, 0, 2) . '&unset=true&hostUrl=' . $hostUrl;
            $this->allowProfil = unserialize(B2S_PLUGIN_NETWORK_ALLOW_PROFILE);
            $this->allowPage = unserialize(B2S_PLUGIN_NETWORK_ALLOW_PAGE);
            $this->allowGroup = unserialize(B2S_PLUGIN_NETWORK_ALLOW_GROUP);
            $this->oAuthPortal = unserialize(B2S_PLUGIN_NETWORK_OAUTH);
            $this->bestTimeInfo = unserialize(B2S_PLUGIN_SCHED_DEFAULT_TIMES_INFO);
            $this->modifyBoardAndGroup = unserialize(B2S_PLUGIN_NETWORK_ALLOW_MODIFY_BOARD_AND_GROUP);
            $this->networkKindName = unserialize(B2S_PLUGIN_NETWORK_KIND);
            $this->lang = substr(B2S_LANGUAGE, 0, 2);
        }
    }

    public function getData() {
        $result = json_decode(B2S_Api_Post::post(B2S_PLUGIN_API_ENDPOINT, array('action' => 'getUserAuth', 'view_mode' => 'all', 'auth_count' => true, 'token' => B2S_PLUGIN_TOKEN, 'version' => B2S_PLUGIN_VERSION)));
        return array('mandanten' => isset($result->mandanten) ? $result->mandanten : '',
            'auth' => isset($result->auth) ? $result->auth : '',
            'auth_count' => isset($result->auth_count) ? $result->auth_count : false,
            'portale' => isset($result->portale) ? $result->portale : '');
    }

    public function getCountSchedPostsByUserAuth($networkAuthId = 0) {
        global $wpdb;
        $countSched = $wpdb->get_results($wpdb->prepare("SELECT COUNT(b.id) AS count FROM {$wpdb->prefix}b2s_posts b LEFT JOIN {$wpdb->prefix}b2s_posts_network_details d ON (d.id = b.network_details_id) WHERE d.network_auth_id= %d AND b.hide = %d AND b.sched_date !=%s", $networkAuthId, 0, '0000-00-00 00:00:00'));
        if (is_array($countSched) && !empty($countSched) && isset($countSched[0]->count)) {
            if ((int) $countSched[0]->count > 0) {
                return (int) $countSched[0]->count;
            }
        }
        return false;
    }

    public function getSelectMandantHtml($data) {
        $select = '<select class="form-control b2s-network-mandant-select b2s-select">';
        $select .= '<optgroup label="' . esc_attr__("Default", "blog2social") . '"><option value="-1" selected="selected">' . esc_html__('Show all', 'blog2social') . '</option>';
        $select .= '<option value="0">' . esc_html__('My profile', 'blog2social') . '</option></optgroup>';
        if (!empty($data)) {
            $select .='<optgroup id="b2s-network-select-more-client" label="' . esc_attr__("Your profiles:", "blog2social") . '">';
            foreach ($data as $id => $name) {
                $select .= '<option value="' . esc_attr($id) . '">' . esc_html(stripslashes($name)) . '</option>';
            }
            $select .='</optgroup>';
        }
        $select .= '</select>';
        return $select;
    }

    public function getPortale($mandanten, $auth, $portale, $auth_count) {
        $convertAuthData = $this->convertAuthData($auth);

        foreach ($mandanten as $k => $v) {
            $this->mandantenId[] = $k;
        }

        $html = '<div class="col-md-12 b2s-network-details-container">';
        $html .= '<form id = "b2sSaveTimeSettings" method = "post">';
        $html .= '<input id = "action" type = "hidden" value = "b2s_save_user_time_settings" name = "action">';

        foreach ($this->mandantenId as $k => $mandant) {
            $html .= $this->getItemHtml($mandant, $mandanten, $convertAuthData, $portale, $auth_count);
        }
        $html .='</form>';
        $html .= '</div>';
        return $html;
    }

    public function getItemHtml($mandant, $mandantenData, $convertAuthData, $portale, $auth_count) {

        $html = '<ul class="list-group b2s-network-details-container-list" data-mandant-id="' . esc_attr($mandant) . '" style="display:' . ($mandant > 0 ? "none" : "block" ) . '">';
        foreach ($portale as $k => $portal) {
            if (!isset($convertAuthData[$mandant][$portal->id]) || empty($convertAuthData[$mandant][$portal->id])) {
                $convertAuthData[$mandant][$portal->id] = array();
            }
            $maxNetworkAccount = ($auth_count !== false && is_array($auth_count)) ? ((isset($auth_count[$portal->id])) ? $auth_count[$portal->id] : $auth_count[0]) : false;

            if ($mandant == -1) { //all
                $html .= $this->getPortaleHtml($portal->id, $portal->name, $mandant, $mandantenData, $convertAuthData, $maxNetworkAccount, true);
            } else {
                $html .= $this->getPortaleHtml($portal->id, $portal->name, $mandant, $mandantenData, $convertAuthData[$mandant][$portal->id], $maxNetworkAccount);
            }
        }
        $html .= '</ul>';

        return $html;
    }

    private function getPortaleHtml($networkId, $networkName, $mandantId, $mandantenData, $networkData, $maxNetworkAccount = false, $showAllAuths = false) {
        $containerMandantId = $mandantId;
        $mandantId = ($mandantId == -1) ? 0 : $mandantId;
        $sprache = substr(B2S_LANGUAGE, 0, 2);
        $html = '<li class="list-group-item" data-network-id="' . esc_attr($networkId) . '">';
        $html .='<div class="media">';
        if ($networkId != 8) {
            $html .='<img class="pull-left hidden-xs b2s-img-network" alt="' . esc_attr($networkName) . '" src="' . plugins_url('/assets/images/portale/' . $networkId . '_flat.png', B2S_PLUGIN_FILE) . '">';
        } else {
            $html .='<span class="pull-left hidden-xs b2s-img-network"></span>';
        }
        $html .='<div class="media-body network">';

        $html .= '<h4>' . esc_html(ucfirst($networkName));

        if ($maxNetworkAccount !== false) {
            if ($networkId == 18) {
                $html .=' <a class="b2s-info-btn b2sInfoNetwork18Btn" href="#">Info</a>';
            }
        }        
        if (isset($this->bestTimeInfo[$networkId]) && !empty($this->bestTimeInfo[$networkId]) && is_array($this->bestTimeInfo[$networkId]) && $networkId != 8) {
            $time = '';
            $slug = ($this->lang == 'de') ? __('Uhr', 'blog2social') : '';
            foreach ($this->bestTimeInfo[$networkId] as $k => $v) {
                $time .= B2S_Util::getTimeByLang($v[0], $this->lang) . '-' . B2S_Util::getTimeByLang($v[1], $this->lang) . $slug . ', ';
            }
            $html .= '<span class="hidden-xs hidden-sm b2s-sched-manager-best-time-info">(' . esc_html__('Best times', 'blog2social') . ': ' . esc_html(substr($time, 0, -2)) . ')</span>';
        }

        $html .= '<span class="pull-right">';

        $b2sAuthUrl = $this->authurl . '&portal_id=' . $networkId . '&transfer=' . (in_array($networkId, $this->oAuthPortal) ? 'oauth' : 'form' ) . '&mandant_id=' . $mandantId . '&version=3&affiliate_id=' . B2S_Tools::getAffiliateId();

        if (in_array($networkId, $this->allowProfil)) {
            $name = ($networkId == 4) ? __('Blog', 'blog2social') : __('Profile', 'blog2social');
            if($networkId == 6){
                $html .= '<a href="#" class="btn btn-primary btn-sm b2s-network-auth-btn" data-auth-method="client" data-network-mandant-id="' . esc_attr($mandantId) . '">+ ' . esc_html__('Profile', 'blog2social') . '</a>';
            } else {
                $html .= ($networkId != 18 || (B2S_PLUGIN_USER_VERSION >= 2 && $networkId == 18)) ? '<a href="#" onclick="wop(\'' . $b2sAuthUrl . '&choose=profile\', \'Blog2Social Network\'); return false;" class="btn btn-primary btn-sm b2s-network-auth-btn">+ ' . esc_html($name) . '</a>' : '<a href="#" class="btn btn-primary btn-sm b2s-network-auth-btn b2s-btn-disabled b2sProFeatureModalBtn" data-title="' . esc_attr__('You want to connect a network profile?', 'blog2social') . '" data-type="auth-network">+ ' . esc_html__('Profile', 'blog2social') . ' <span class="label label-success">' . esc_html__("PRO", "blog2social") . '</a>';
            }
        }
        if (in_array($networkId, $this->allowPage)) {
            $html .= (B2S_PLUGIN_USER_VERSION > 1 || (B2S_PLUGIN_USER_VERSION == 0 && $networkId == 1) || (B2S_PLUGIN_USER_VERSION == 1 && ($networkId == 1 || $networkId == 10))) ? '<button onclick="wop(\'' . $b2sAuthUrl . '&choose=page\', \'Blog2Social Network\'); return false;" class="btn btn-primary btn-sm b2s-network-auth-btn">+ ' . esc_html__('Page', 'blog2social') . '</button>' : '<a href="#" class="btn btn-primary btn-sm b2s-network-auth-btn b2s-btn-disabled b2sProFeatureModalBtn" data-title="' . esc_attr__('You want to connect a network page?', 'blog2social') . '"  data-type="auth-network">+ ' . esc_html__('Page', 'blog2social') . ' <span class="label label-success">' . esc_html__("PRO", "blog2social") . '</a>';
        }
        if (in_array($networkId, $this->allowGroup)) {
            $name = ($networkId == 11) ? __('Publication', 'blog2social') : __('Group', 'blog2social');
            $html .= (B2S_PLUGIN_USER_VERSION > 1) ? '<button  onclick="wop(\'' . $b2sAuthUrl . '&choose=group\', \'Blog2Social Network\'); return false;" class="btn btn-primary btn-sm b2s-network-auth-btn">+ ' . esc_html($name) . '</button>' : '<a href="#" class="btn btn-primary btn-sm b2s-network-auth-btn b2s-btn-disabled b2sProFeatureModalBtn" data-title="' . esc_attr__('You want to connect a social media group?', 'blog2social') . '" data-type="auth-network">+ ' . esc_html($name) . ' <span class="label label-success">' . esc_html__("PRO", "blog2social") . '</span></a>';
        }
        if (array_key_exists($networkId, unserialize(B2S_PLUGIN_NETWORK_SETTINGS_TEMPLATE_DEFAULT))) {
            $html .= (B2S_PLUGIN_USER_VERSION >= 1) ? '<button onclick="return false;" class="btn btn-primary btn-sm b2s-network-auth-btn b2s-edit-template-btn" data-network-id="' . esc_attr($networkId) . '"><i class="glyphicon glyphicon-pencil"></i> ' . esc_html__('Edit Post Template', 'blog2social') . '</button>' : '<button onclick="return false;" class="btn btn-primary btn-sm b2s-network-auth-btn b2s-edit-template-btn b2s-btn-disabled" data-network-id="' . esc_attr($networkId) . '"><i class="glyphicon glyphicon-pencil"></i> ' . esc_html__('Edit Post Template', 'blog2social') . ' <span class="label label-success">' . esc_html__("SMART", "blog2social") . '</span></button>';
        }

        $html .= '</span></h4>';
        $html .= '<div class="clearfix"></div>';
        $html .= '<ul class="b2s-network-item-auth-list" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" ' . (($showAllAuths) ? 'data-network-count="true"' : '') . '>';

        //First Line
        $html.='<li class="b2s-network-item-auth-list-li"  data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-view="' . esc_attr((($containerMandantId == -1) ? 'all' : 'selected')) . '">';
        $html.='<span class="b2s-network-auth-count">' . esc_html__("Connections", "blog2social") . ' <span class="b2s-network-auth-count-current" ' . (($showAllAuths) ? 'data-network-count-trigger="true"' : '') . '  data-network-id="' . esc_attr($networkId) . '"></span>/' . esc_html($maxNetworkAccount) . '</span>';
        $html.='<span class="pull-right b2s-sched-manager-title hidden-xs"  data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '">' . esc_html__("Best Time Manager", "blog2social") . ' <a href="#" class="b2s-info-btn b2s-load-settings-sched-time-default-info b2sInfoSchedTimesModalBtn">' . esc_html__('Info', 'blog2social') . '</a></span>';
        $html.='</li>';


        if ($showAllAuths) {
            foreach ($this->mandantenId as $ka => $mandantAll) {
                $mandantName = isset($mandantenData->{$mandantAll}) ? ($mandantenData->{$mandantAll}) : esc_html__("My profile", "blog2social");
                if (isset($networkData[$mandantAll][$networkId]) && !empty($networkData[$mandantAll][$networkId])) {
                    $html .= $this->getAuthItemHtml($networkData[$mandantAll][$networkId], $mandantAll, $mandantName, $networkId, $b2sAuthUrl, $containerMandantId, $sprache);
                }
            }
        } else {
            $html .= $this->getAuthItemHtml($networkData, $mandantId, "", $networkId, $b2sAuthUrl, $containerMandantId, $sprache);
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</li>';
        return $html;
    }

    private function getAuthItemHtml($networkData = array(), $mandantId, $mandantName, $networkId, $b2sAuthUrl = '', $containerMandantId = 0, $sprache = 'en') {
        $isEdit = false;
        $html = '';
        if (isset($networkData[0])) {
            foreach ($networkData[0] as $k => $v) {

                $isDeprecated = false;
                $notAllow = ($v['notAllow'] !== false) ? true : false;
                $isInterrupted = ($v['expiredDate'] != '0000-00-00' && $v['expiredDate'] <= date('Y-m-d')) ? true : false;


                $html .= '<li class="b2s-network-item-auth-list-li ' . (($isDeprecated) ? 'b2s-label-info-border-left deprecated' : (($notAllow) ? 'b2s-label-warning-border-left' : (($isInterrupted) ? 'b2s-label-danger-border-left' : ''))) . ' " data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="0">';
                $html .='<div class="pull-left">';

                if ($notAllow) {
                    $html.= '<div class="b2s-network-auth-list-info"><span class="glyphicon glyphicon-remove-circle"></span> ' . esc_html__('To reactivate this connection,', 'blog2social') . ' <a href="' . esc_url(B2S_Tools::getSupportLink('affiliate')) . '"target="_blank">' . esc_html__('please upgrade', 'blog2social') . '</a></div>';
                }
                if ($isInterrupted && !$notAllow) {
                    $html.= '<div class="b2s-network-auth-list-info" data-b2s-auth-info="isInterrupted"><span class="glyphicon glyphicon-remove-circle"></span> ' . esc_html__('Authorization is interrupted since', 'blog2social') . ' ' . esc_html(($sprache == 'en' ? $v['expiredDate'] : date('d.m.Y', strtotime($v['expiredDate'])))) . '</div>';
                }
                if ($v['owner_blog_user_id'] !== false) {
                    $displayName = stripslashes(get_user_by('id', $v['owner_blog_user_id'])->display_name);
                    $html .='<div class="b2s-network-approved-from">' . esc_html__("Assigned by", "blog2social") . ' ' . esc_html(((empty($displayName) || $displayName == false) ? __("Unknown username", "blog2social") : $displayName)) . '</div> ';
                }
                $name = ($networkId == 4) ? __('Blog', 'blog2social') : __('Profile', 'blog2social');
                $html .= '<span class="b2s-network-item-auth-type">' . (($isDeprecated) ? '<span class="glyphicon glyphicon-exclamation-sign glyphicon-info"></span> ' : '') . esc_html($name) . '</span>: <span class="b2s-network-item-auth-user-name">' . esc_html(stripslashes($v['networkUserName'])) . '</span> ';

                if (!empty($mandantName)) {
                    $html .='<span class="b2s-network-mandant-name">(' . esc_html($mandantName) . ')</span> ';
                }

                $html .='</div>';

                $html .='<div class="pull-right">';
                $html .= '<a class="b2s-network-item-auth-list-btn-delete b2s-add-padding-network-delete pull-right" data-network-type="0" data-network-id="' . esc_attr($networkId) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" href="#"><span class="glyphicon  glyphicon-trash glyphicon-grey"></span></a>';
                if (!$notAllow && !$isDeprecated) {
                    if ($v['owner_blog_user_id'] == false) {
                        if ($networkId != 6) {
                            $html .= '<a href="#" onclick="wop(\'' . $b2sAuthUrl . '&choose=profil&update=' . $v['networkAuthId'] . '\', \'Blog2Social Network\'); return false;" class="b2s-network-auth-btn b2s-network-auth-update-btn b2s-add-padding-network-refresh pull-right" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '"><span class="glyphicon  glyphicon-refresh glyphicon-grey"></span></a>';
                        } else {
                            $html .= '<a href="#" class="b2s-network-auth-btn b2s-network-auth-update-btn b2s-add-padding-network-refresh pull-right" data-auth-method="client" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '"><span class="glyphicon  glyphicon-refresh glyphicon-grey"></span></a>';
                        }
                    } else {
                        $html .= '<span class="b2s-add-padding-network-placeholder-btn pull-right"></span>';
                    }
                    $html .='<a href="#" class="pull-right b2s-network-auth-settings-btn b2s-add-padding-network-team pull-right" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="0" data-network-mandant-id="' . esc_attr($mandantId) . '" data-connection-owner="' . esc_attr((($v['owner_blog_user_id'] !== false) ? $v['owner_blog_user_id'] : '0')) . '"><span class="glyphicon glyphicon-cog glyphicon-grey"></span></a>';
                    if ($v['expiredDate'] == '0000-00-00' || $v['expiredDate'] > date('Y-m-d')) {
                        if (isset($this->modifyBoardAndGroup[$networkId])) {
                            if (in_array(0, $this->modifyBoardAndGroup[$networkId]['TYPE'])) {
                                $html .='<a href="#" class="pull-right b2s-modify-board-and-group-network-btn b2s-add-padding-network-edit" data-modal-title="' . esc_attr($this->modifyBoardAndGroup[$networkId]['TITLE']) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="0"><span class="glyphicon glyphicon-pencil glyphicon-grey"></span></a>';
                                $isEdit = true;
                            }
                        }
                    }
                }
                //Sched Manager since V 5.1.0
                if (B2S_PLUGIN_USER_VERSION > 0) {
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-time-area pull-right ' . (!$isEdit ? 'b2s-sched-manager-add-padding' : '') . ' hidden-xs" style="' . (($notAllow) ? 'display:none;' : '') . '">
                        <input class="form-control b2s-box-sched-time-input b2s-settings-sched-item-input-time" type="text" value="' . esc_attr($this->getUserSchedTime($v['networkAuthId'], $networkId, 0, 'time')) . '" readonly="" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="0" data-network-container-mandant-id="' . esc_attr($containerMandantId) . '" name="b2s-user-sched-data[time][' . esc_attr($v['networkAuthId']) . ']">
                        </span>';
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-day-area pull-right hidden-xs" style="' . (($notAllow) ? 'display:none;' : '') . '"><span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-item-input-day-btn-minus" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '">-</span> <span class="b2s-text-middle">+</span> <input type="text" class="b2s-sched-manager-item-input-day" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="0"  data-network-container-mandant-id="' . esc_attr($containerMandantId) . '" name="b2s-user-sched-data[delay_day][' . esc_attr($v['networkAuthId']) . ']" value="' . esc_attr($this->getUserSchedTime($v['networkAuthId'], $networkId, 0, 'day')) . '" readonly> <span class="b2s-text-middle">' . esc_html__('Days', 'blog2social') . '</span> <span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-item-input-day-btn-plus" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '">+</span></span>';
                } else {
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-premium-area pull-right hidden-xs"><span class="label label-success"><a href="#" class="btn-label-premium b2sInfoSchedTimesModalBtn">' . esc_html__('SMART', 'blog2social') . '</a></span></span>';
                }

                $html .='</div>';

                $html .= '<div class="clearfix"></div></li>';
            }
        }
        if (isset($networkData[1])) {
            foreach ($networkData[1] as $k => $v) {

                $isDeprecated = false;
                $notAllow = ($v['notAllow'] !== false) ? true : false;
                $isInterrupted = ($v['expiredDate'] != '0000-00-00' && $v['expiredDate'] <= date('Y-m-d')) ? true : false;

                $html .= '<li class="b2s-network-item-auth-list-li ' . (($isDeprecated) ? 'b2s-label-info-border-left deprecated' : (($notAllow) ? 'b2s-label-warning-border-left' : (($isInterrupted) ? 'b2s-label-danger-border-left' : ''))) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="1">';
                $html .='<div class="pull-left">';

                if ($notAllow) {
                    $html.= '<div class="b2s-network-auth-list-info"><span class="glyphicon glyphicon-remove-circle"></span> ' . esc_html__('To reactivate this connection,', 'blog2social') . ' <a href="' . esc_url(B2S_Tools::getSupportLink('affiliate')) . '"target="_blank">' . esc_html__('please upgrade', 'blog2social') . '</a></div>';
                }
                if ($isInterrupted && !$notAllow) {
                    $html.= '<div class="b2s-network-auth-list-info" data-b2s-auth-info="isInterrupted">' . esc_html__('Authorization is interrupted since', 'blog2social') . ' ' . esc_html(($sprache == 'en' ? $v['expiredDate'] : date('d.m.Y', strtotime($v['expiredDate'])))) . '</div>';
                }
                if ($v['owner_blog_user_id'] !== false) {
                    $displayName = stripslashes(get_user_by('id', $v['owner_blog_user_id'])->display_name);
                    $html .='<div class="b2s-network-approved-from">' . esc_html__("Assigned by", "blog2social") . ' ' . esc_html(((empty($displayName) || $displayName == false) ? __("Unknown username", "blog2social") : $displayName)) . '</div> ';
                }
                $html .= '<span class="b2s-network-item-auth-type">' . (($isDeprecated) ? '<span class="glyphicon glyphicon-exclamation-sign glyphicon-info"></span> ' : '') . ($networkId == 19 && isset($this->networkKindName[$v['networkKind']]) ? $this->networkKindName[$v['networkKind']] . '-' : '') . esc_html__('Page', 'blog2social') . (($networkId == 19 && (int) $v['networkKind'] == 0) ? ' <span class="hidden-xs">(' . esc_html__('Employer Branding', 'blog2social') . ')</span>' : '') . '</span>: <span class="b2s-network-item-auth-user-name">' . esc_html(stripslashes($v['networkUserName'])) . '</span> ';
                
                if($networkId == 19 && (int) $v['networkKind'] == 1) {// Xing Business Pages Info
                    $html .= '<input type="hidden" value="1" id="b2sHasXingBusinessPage">';
                }
                
                if (!empty($mandantName)) {
                    $html .='<span class="b2s-network-mandant-name">(' . esc_html($mandantName) . ')</span> ';
                }

                $html .='</div>';
                $html .='<div class="pull-right">';
                $html .= '<a class="b2s-network-item-auth-list-btn-delete b2s-add-padding-network-delete pull-right" data-network-type="1" data-network-id="' . esc_attr($networkId) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" href="#"><span class="glyphicon  glyphicon-trash glyphicon-grey"></span></a>';
                if (!$notAllow && !$isDeprecated) {
                    if ($v['owner_blog_user_id'] == false) {
                        $html .= '<a href="#" onclick="wop(\'' . $b2sAuthUrl . '&choose=page&update=' . $v['networkAuthId'] . '\', \'Blog2Social Network\'); return false;" class="b2s-network-auth-btn b2s-network-auth-update-btn b2s-add-padding-network-refresh pull-right" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '"><span class="glyphicon  glyphicon-refresh glyphicon-grey"></span></a>';
                    } else {
                        $html .= '<span class="b2s-add-padding-network-placeholder-btn pull-right"></span>';
                    }

                    $html .='<a href="#" class="pull-right b2s-network-auth-settings-btn b2s-add-padding-network-team pull-right" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="1" data-network-mandant-id="' . esc_attr($mandantId) . '" data-connection-owner="' . esc_attr((($v['owner_blog_user_id'] !== false) ? $v['owner_blog_user_id'] : '0')) . '"><span class="glyphicon glyphicon-cog glyphicon-grey"></span></a>';
                    if ($v['expiredDate'] == '0000-00-00' || $v['expiredDate'] > date('Y-m-d')) {
                        if (isset($this->modifyBoardAndGroup[$networkId])) {
                            if (in_array(1, $this->modifyBoardAndGroup[$networkId]['TYPE'])) {
                                $html .='<a href="#" class="pull-right b2s-modify-board-and-group-network-btn b2s-add-padding-network-edit" data-modal-title="' . esc_attr($this->modifyBoardAndGroup[$networkId]['TITLE']) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="1"><span class="glyphicon glyphicon-pencil glyphicon-grey"></span></a>';
                                $isEdit = true;
                            }
                        }
                    }
                }

                //Sched Manager since V 5.1.0
                if (B2S_PLUGIN_USER_VERSION > 0) {
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-time-area pull-right ' . (!$isEdit ? 'b2s-sched-manager-add-padding' : '') . ' hidden-xs" style="' . (($notAllow) ? 'display:none;' : '') . '">
                        <input class="form-control b2s-box-sched-time-input b2s-settings-sched-item-input-time" type="text" value="' . esc_attr($this->getUserSchedTime($v['networkAuthId'], $networkId, 1, 'time')) . '" readonly=""  data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="1" data-network-container-mandant-id="' . esc_attr($containerMandantId) . '" name="b2s-user-sched-data[time][' . esc_attr($v['networkAuthId']) . ']">
                        </span>';
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-day-area pull-right hidden-xs" style="' . (($notAllow) ? 'display:none;' : '') . '"><span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-item-input-day-btn-minus" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '">-</span> <span class="b2s-text-middle">+</span> <input type="text" class="b2s-sched-manager-item-input-day" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="1" data-network-container-mandant-id="' . esc_attr($containerMandantId) . '"  name="b2s-user-sched-data[delay_day][' . esc_attr($v['networkAuthId']) . ']" value="' . esc_attr($this->getUserSchedTime($v['networkAuthId'], $networkId, 1, 'day')) . '" readonly> <span class="b2s-text-middle">' . esc_html__('Days', 'blog2social') . '</span> <span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-item-input-day-btn-plus" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '">+</span></span>';
                } else {
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-premium-area pull-right hidden-xs"><span class="label label-success"><a href="#" class="btn-label-premium b2sInfoSchedTimesModalBtn">' . esc_html__('SMART', 'blog2social') . '</a></span></span>';
                }

                $html .='</div>';

                $html .= '<div class="clearfix"></div></li>';
            }
        }
        if (isset($networkData[2])) {
            foreach ($networkData[2] as $k => $v) {

                $isDeprecated = false;
                $notAllow = ($v['notAllow'] !== false) ? true : false;
                $isInterrupted = ($v['expiredDate'] != '0000-00-00' && $v['expiredDate'] <= date('Y-m-d')) ? true : false;

                $html .= '<li class="b2s-network-item-auth-list-li ' . (($isDeprecated) ? 'b2s-label-info-border-left deprecated' : (($notAllow) ? 'b2s-label-warning-border-left' : (($isInterrupted) ? 'b2s-label-danger-border-left' : ''))) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="2">';

                $html .='<div class="pull-left">';

                if ($notAllow) {
                    $html.= '<div class="b2s-network-auth-list-info"><span class="glyphicon glyphicon-remove-circle"></span> ' . esc_html__('To reactivate this connection,', 'blog2social') . ' <a href="' . esc_url(B2S_Tools::getSupportLink('affiliate')) . '"target="_blank">' . esc_html__('please upgrade', 'blog2social') . '</a></div>';
                }
                if ($isInterrupted && !$notAllow) {
                    $html.= '<div class="b2s-network-auth-list-info" data-b2s-auth-info="isInterrupted">' . esc_html__('Authorization is interrupted since', 'blog2social') . ' ' . esc_html(($sprache == 'en' ? $v['expiredDate'] : date('d.m.Y', strtotime($v['expiredDate'])))) . '</div>';
                }
                if ($v['owner_blog_user_id'] !== false) {
                    $displayName = stripslashes(get_user_by('id', $v['owner_blog_user_id'])->display_name);
                    $html .='<div class="b2s-network-approved-from">' . esc_html__("Assigned by", "blog2social") . ' ' . esc_html(((empty($displayName) || $displayName == false) ? __("Unknown username", "blog2social") : $displayName)) . '</div> ';
                }
                $name = ($networkId == 11) ? __('Publication', 'blog2social') : __('Group', 'blog2social');
                $html .= '<span class="b2s-network-item-auth-type">' . (($isDeprecated) ? '<span class="glyphicon glyphicon-exclamation-sign glyphicon-info"></span> ' : '') . esc_html($name) . '</span>: <span class="b2s-network-item-auth-user-name">' . esc_html(stripslashes($v['networkUserName'])) . '</span> ';

                if (!empty($mandantName)) {
                    $html .='<span class="b2s-network-mandant-name">(' . esc_html($mandantName) . ')</span> ';
                }
                $html .='</div>';
                $html .='<div class="pull-right">';
                $html .= '<a class="b2s-network-item-auth-list-btn-delete b2s-add-padding-network-delete pull-right" data-network-type="2" data-network-id="' . esc_attr($networkId) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" href="#"><span class="glyphicon  glyphicon-trash glyphicon-grey"></span></a>';
                if (!$notAllow && !$isDeprecated) {
                    if ($v['owner_blog_user_id'] == false) {
                        $html .= '<a href="#" onclick="wop(\'' . $b2sAuthUrl . '&choose=group&update=' . $v['networkAuthId'] . '\', \'Blog2Social Network\'); return false;" class="b2s-network-auth-btn b2s-network-auth-update-btn b2s-add-padding-network-refresh pull-right" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '"><span class="glyphicon  glyphicon-refresh glyphicon-grey"></span></a>';
                    } else {
                        $html .= '<span class="b2s-add-padding-network-placeholder-btn pull-right"></span>';
                    }
                    $html .='<a href="#" class="pull-right b2s-network-auth-settings-btn b2s-add-padding-network-team pull-right" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="2" data-network-mandant-id="' . esc_attr($mandantId) . '" data-connection-owner="' . esc_attr((($v['owner_blog_user_id'] !== false) ? $v['owner_blog_user_id'] : '0')) . '"><span class="glyphicon glyphicon-cog glyphicon-grey"></span></a>';
                    if ($v['expiredDate'] == '0000-00-00' || $v['expiredDate'] > date('Y-m-d')) {
                        if (isset($this->modifyBoardAndGroup[$networkId])) {
                            if (in_array(2, $this->modifyBoardAndGroup[$networkId]['TYPE'])) {
                                $html .='<a href="#" class="pull-right b2s-modify-board-and-group-network-btn b2s-add-padding-network-edit" data-modal-title="' . esc_attr($this->modifyBoardAndGroup[$networkId]['TITLE']) . '" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="2"><span class="glyphicon glyphicon-pencil glyphicon-grey"></span></a>';
                                $isEdit = true;
                            }
                        }
                    }
                }

                //Sched Manager since V 5.1.0
                if (B2S_PLUGIN_USER_VERSION > 0) {
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-time-area pull-right ' . (!$isEdit ? 'b2s-sched-manager-add-padding' : '') . ' hidden-xs" style="' . (($notAllow) ? 'display:none;' : '') . '">
                        <input class="form-control b2s-box-sched-time-input b2s-settings-sched-item-input-time" type="text" value="' . esc_attr($this->getUserSchedTime($v['networkAuthId'], $networkId, 2, 'time')) . '" readonly="" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="2" data-network-container-mandant-id="' . esc_attr($containerMandantId) . '" name="b2s-user-sched-data[time][' . esc_attr($v['networkAuthId']) . ']">
                        </span>';
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-day-area pull-right hidden-xs" style="' . (($notAllow) ? 'display:none;' : '') . '"><span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-item-input-day-btn-minus" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '">-</span> <span class="b2s-text-middle">+</span> <input type="text" class="b2s-sched-manager-item-input-day" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" data-network-mandant-id="' . esc_attr($mandantId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="2" data-network-container-mandant-id="' . esc_attr($containerMandantId) . '"  name="b2s-user-sched-data[delay_day][' . esc_attr($v['networkAuthId']) . ']" value="' . esc_attr($this->getUserSchedTime($v['networkAuthId'], $networkId, 2, 'day')) . '" readonly> <span class="b2s-text-middle">' . esc_html__('Days', 'blog2social') . '</span> <span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-item-input-day-btn-plus" data-network-auth-id="' . esc_attr($v['networkAuthId']) . '">+</span></span>';
                } else {
                    $html .='<span data-network-auth-id="' . esc_attr($v['networkAuthId']) . '" class="b2s-sched-manager-premium-area pull-right hidden-xs"><span class="label label-success"><a href="#" class="btn-label-premium b2sInfoSchedTimesModalBtn">' . esc_html__('SMART', 'blog2social') . '</a></span></span>';
                }

                $html .='</div>';

                $html .= '<div class="clearfix"></div></li>';
            }
        }
        return $html;
    }

    private function convertAuthData($auth) {
        $convertAuth = array();
        foreach ($auth as $k => $value) {
            $convertAuth[$value->mandantId][$value->networkId][$value->networkType][] = array(
                'networkAuthId' => $value->networkAuthId,
                'networkUserName' => $value->networkUserName,
                'expiredDate' => $value->expiredDate,
                'networkKind' => $value->networkKind,
                'notAllow' => (isset($value->notAllow) ? $value->notAllow : false),
                'owner_blog_user_id' => (isset($value->owner_blog_user_id) ? $value->owner_blog_user_id : false)
            );
        }
        return $convertAuth;
    }

    //New >V5.1.0 Seeding
    private function getUserSchedTime($network_auth_id = 0, $network_id = 0, $network_type = 0, $type = 'time') { //type = time,day
        //new > v5.1.0
        if ($this->userSchedData !== false) {
            if (is_array($this->userSchedData) && isset($this->userSchedData['delay_day'][$network_auth_id]) && isset($this->userSchedData['time'][$network_auth_id]) && !empty($this->userSchedData['time'][$network_auth_id])) {
                if ($type == 'time') {
                    $slug = ($this->lang == 'en') ? 'h:i A' : 'H:i';
                    return date($slug, strtotime(date('Y-m-d ' . $this->userSchedData['time'][$network_auth_id] . ':00')));
                }
                if ($type == 'day') {
                    return (int) $this->userSchedData['delay_day'][$network_auth_id];
                }
            }
        }
        //old < 5.1.0 load data
        if (!empty($this->userSchedDataOld) && is_array($this->userSchedDataOld)) {
            if ($type == 'time') {
                foreach ($this->userSchedDataOld as $k => $v) {
                    if ((int) $network_id == (int) $v->network_id && (int) $network_type == (int) $v->network_type) {
                        $slug = ($this->lang == 'en') ? 'h:i A' : 'H:i';
                        return date($slug, strtotime(date('Y-m-d ' . $v->sched_time . ':00')));
                    }
                }
            }
        }
        if ($type == 'day') {
            return 0;
        }
        return null;
    }

    //Old < 5.1.0
    private function getSchedDataByUser() {
        global $wpdb;
        $saveSchedData = null;
        //if exists
        if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}b2s_post_sched_settings'") == $wpdb->prefix.'b2s_post_sched_settings') {
            $saveSchedData = $wpdb->get_results($wpdb->prepare("SELECT network_id, network_type, sched_time FROM {$wpdb->prefix}b2s_post_sched_settings WHERE blog_user_id= %d", B2S_PLUGIN_BLOG_USER_ID));
        }
        return $saveSchedData;
    }

    public function getNetworkAuthAssignment($networkAuthId = 0, $networkId = 0, $networkType = 0) {
        global $wpdb;
        $blogUserTokenResult = $wpdb->get_results("SELECT token FROM `{$wpdb->prefix}b2s_user`");
        $blogUserToken = array();
        foreach ($blogUserTokenResult as $k => $row) {
            array_push($blogUserToken, $row->token);
        }
        $data = array('action' => 'getTeamAssignUserAuth', 'token' => B2S_PLUGIN_TOKEN, 'networkAuthId' => $networkAuthId, 'blogUser' => $blogUserToken);
        $networkAuthAssignment = json_decode(B2S_Api_Post::post(B2S_PLUGIN_API_ENDPOINT, $data, 30), true);
        if (isset($networkAuthAssignment['result']) && $networkAuthAssignment['result'] !== false) {
            $doneIds = array();
            $assignList = '<ul class="b2s-network-item-auth-list" id="b2s-approved-user-list"><li class="b2s-network-item-auth-list-li b2s-bold">' . esc_html__('Connection currently assigned to', 'blog2social') . '</li>';
            if (isset($networkAuthAssignment['assignList']) && is_array($networkAuthAssignment['assignList']) && !empty($networkAuthAssignment['assignList'])) {
                $options = new B2S_Options((int) B2S_PLUGIN_BLOG_USER_ID);
                $optionUserTimeZone = $options->_getOption('user_time_zone');
                $userTimeZone = ($optionUserTimeZone !== false) ? $optionUserTimeZone : get_option('timezone_string');
                $userTimeZoneOffset = (empty($userTimeZone)) ? get_option('gmt_offset') : B2S_Util::getOffsetToUtcByTimeZone($userTimeZone);
                foreach ($networkAuthAssignment['assignList'] as $k => $listUser) {
                    array_push($doneIds, $listUser['assign_blog_user_id']);
                    if (get_userdata($listUser['assign_blog_user_id']) !== false) {
                        $current_user_date = date((strtolower(substr(B2S_LANGUAGE, 0, 2)) == 'de') ? 'd.m.Y' : 'Y-m-d', strtotime(B2S_Util::getUTCForDate($listUser['created_utc'], $userTimeZoneOffset)));
                        $displayName = stripslashes(get_user_by('id', $listUser['assign_blog_user_id'])->display_name);
                        $assignList .= '<li class="b2s-network-item-auth-list-li">';
                        $assignList .= '<div class="pull-left" style="padding-top: 5px;"><span>' . esc_html(((empty($displayName) || $displayName == false) ? __("Unknown username", "blog2social") : $displayName)) . '</span></div>';
                        $assignList .= '<div class="pull-right"><span style="margin-right: 10px;">' . esc_html($current_user_date) . '</span> <button class="b2s-network-item-auth-list-btn-delete btn btn-danger btn-sm" data-assign-network-auth-id="' . esc_attr($listUser['assign_network_auth_id']) . '" data-network-auth-id="' . esc_attr($networkAuthId) . '" data-network-id="' . esc_attr($networkId) . '" data-network-type="' . esc_attr($networkType) . '" data-blog-user-id="' . esc_attr($listUser['assign_blog_user_id']) . '">' . esc_html__('delete', 'blog2social') . '</button></div>';
                        $assignList .= '<div class="clearfix"></div></li>';
                    }
                }
            }
            $assignList .= '</ul>';

            $select = '<select class="form-control b2s-select" id="b2s-select-assign-user">';
            if (isset($networkAuthAssignment['userList']) && !empty($networkAuthAssignment['userList'])) {
                foreach ($networkAuthAssignment['userList'] as $k => $listUser) {
                    if ((int) $listUser != B2S_PLUGIN_BLOG_USER_ID && !in_array($listUser, $doneIds)) {
                        array_push($doneIds, $listUser);
                        $userDetails = get_option('B2S_PLUGIN_USER_VERSION_' . $listUser);
                        if (isset($userDetails['B2S_PLUGIN_USER_VERSION']) && (int) $userDetails['B2S_PLUGIN_USER_VERSION'] == 3) {
                            $displayName = stripslashes(get_user_by('id', $listUser)->display_name);
                            if(!empty($displayName) && $displayName != false) {
                                $select .= '<option value="' . esc_attr($listUser) . '">' . esc_html($displayName) . '</option>';
                            }
                        }
                    }
                }
            }
            $select .= '</select>';

            return array('result' => true, 'userSelect' => $select, 'assignList' => $assignList);
        }
        return array('result' => false);
    }
    
    public function getUrlParameterSettings($networkAuthId, $networkId) {        
        $html = '<div class="col-md-12 b2s-text-bold"><span>' . sprintf(__('Define parameters that will be added to link posts on this network e.g. to create tracking links with UTM paramters. <a target="_blank" href="%s">More information</a>', 'blog2social'), esc_url(B2S_Tools::getSupportLink('url_parameter'))) . '</span></div>';
        $html .= '<div class="b2s-col-name">';
        $html .= '<div class="col-md-5 b2s-url-parameter-legend-text">' . esc_html__('Name', 'blog2social') . '</div>';
        $html .= '<div class="col-md-5 b2s-url-parameter-legend-text">' . esc_html__('Value', 'blog2social') . '</div>';
        $html .= '</div>';
        
        $html .= '<ul class="b2s-url-parameter-list col-md-12">';
        
        global $wpdb;
        $sqlGetData = $wpdb->prepare("SELECT `data` FROM `{$wpdb->prefix}b2s_posts_network_details` WHERE `network_auth_id` = %d", (int) $networkAuthId);
        $dataString = $wpdb->get_var($sqlGetData);
        $counter = 0;
        if ($dataString !== NULL && !empty($dataString)) {
            $data = unserialize($dataString);
            if($data != false && isset($data['url_parameter'][0]['querys']) && !empty($data['url_parameter'][0]['querys']) && is_array($data['url_parameter'][0]['querys'])) {
                foreach ($data['url_parameter'][0]['querys'] as $param => $value) {
                    $html .= '<li class="b2s-url-parameter-entry row">';
                    $html .= '<div class="col-md-5"><input class="form-control b2s-link-parameter-name" value="'.urldecode($param).'"></div>';
                    $html .= '<div class="col-md-5"><input class="form-control b2s-link-parameter-value" value="'.urldecode($value).'"></div>';
                    $html .= '<div class="col-md-1"><span aria-hidden="true" class="b2s-url-parameter-remove-btn text-danger">&times;</span></div>';
                    $html .= '</li>';
                    $counter++;
                }
            }
        }
        
        $html .= '</ul>';
        $html .= '<div class="col-md-12 padding-bottom-10"><button class="btn btn-sm btn-default b2s-url-parameter-add-btn" '.(($counter >= 10) ? 'style="display:none;"' : '').'>' . esc_html__('+ add Parameter', 'blog2social') . '</button></div>';
        $html .= '<div class="col-md-12"><input type="checkbox" class="b2s-url-parameter-for-all-network" id="b2s-url-parameter-for-all-network"><label for="b2s-url-parameter-for-all-network"> '.sprintf(esc_html__('Apply for all %s connections', 'blog2social'), unserialize(B2S_PLUGIN_NETWORK)[$networkId]).'</label></div>';
        $html .= '<div class="col-md-6"><input type="checkbox" class="b2s-url-parameter-for-all" id="b2s-url-parameter-for-all"><label for="b2s-url-parameter-for-all"> '.esc_html__('Apply for all connections','blog2social').'</label></div>';
        $html .= '<div class="col-md-6"><button class="btn btn-sm btn-primary pull-right b2s-url-parameter-save-btn" data-network-auth-id="'.$networkAuthId.'" data-network-id="'.$networkId.'">' . esc_html__('save', 'blog2social') . '</button></div>';
        return $html;
    }

    public function getEditTemplateForm($networkId) {
        require_once(B2S_PLUGIN_DIR . 'includes/Options.php');
        $options = new B2S_Options(get_current_user_id());
        $post_template = $options->_getOption("post_template");
        $linkNoCache = $options->_getOption("link_no_cache");
        if($linkNoCache == false || !is_array($linkNoCache)) {
            $fb_linkNoCache = (((int) $linkNoCache > 0) ? 1 : 0);
            $linkNoCache = array(1 => $fb_linkNoCache, 3 => 1);
            $options->_setOption("link_no_cache", $linkNoCache);
        }
        $defaultSchema = unserialize(B2S_PLUGIN_NETWORK_SETTINGS_TEMPLATE_DEFAULT)[$networkId];
        if (B2S_PLUGIN_USER_VERSION >= 1 && $post_template != false && isset($post_template[$networkId]) && !empty($post_template[$networkId])) {
            $schema = $post_template[$networkId];
            if(count($schema) < count($defaultSchema)){
                $schema = array_merge($schema, $defaultSchema);
            }
        } else {
            $schema = $defaultSchema;
        }

        $html = '<div class="row">';
        $html .= '<div class="col-sm-12">';
        $html .= '<div class="alert alert-success b2s-edit-template-save-success" style="display: none;">' . esc_html__('Successfully saved', 'blog2social') . '</div>';
        $html .= '<div class="alert alert-success b2s-edit-template-save-failed" style="display: none;">' . esc_html__('Failed to save', 'blog2social') . '</div>';
        $html .= '<div class="alert alert-success b2s-edit-template-load-default-failed" style="display: none;">' . esc_html__('Failed to load the default template', 'blog2social') . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        if (B2S_PLUGIN_USER_VERSION < 1) {
            $html .= '<div class="row">';
            $html .= '<div class="col-sm-12">';
            $html .= '<div class="alert alert-info">';
            $html .= '<i class="glyphicon glyphicon-info-sign"></i> ' . esc_html__('Upgrade to Blog2Social Smart or higher to customize your individual post templates that will automatically pre-format the structure of your social media posts. Select and define elements, such as title, excerpt, content, hashtags, and edit the post format. The “content” element is selected by default. Define custom post templates per social network and per profile, group & page. You can also add static content (such as individual hashtags or slogans) to your post templates.', 'blog2social');
            $html .= ' <a target="_blank" href="' . esc_url(B2S_Tools::getSupportLink('affiliate')) . '" class="b2s-bold b2s-text-underline">' . esc_html__('Upgrade to Blog2Social Smart', 'blog2social') . '</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-12">';
        if (count($schema) > 1) {
            $html .= '<div class="pull-left ' . ((B2S_PLUGIN_USER_VERSION < 1) ? 'b2s-btn-disabled' : '') . '">';
            $html .= '<ul class="nav nav-pills">';
            $html .= '<li class="active"><a href="#b2s-template-profile" class="b2s-template-profile" data-toggle="tab">' . esc_html__('Profile', 'blog2social') . '</a></li>';
            if (isset($schema[1]) && !empty($schema[1])) {
                $html .= '<li><a href="#b2s-template-page" class="b2s-template-page" data-toggle="tab">' . esc_html__('Page', 'blog2social') . '</a></li>';
            }
            if (isset($schema[2]) && !empty($schema[2])) {
                $html .= '<li><a href="#b2s-template-group" class="b2s-template-group" data-toggle="tab">' . esc_html__('Group', 'blog2social') . '</a></li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
            if ($networkId == 1 || $networkId == 3) {
                $html .= '<div class="pull-right"><input id="link-no-cache" type="checkbox" ' . ((isset($linkNoCache[$networkId]) && $linkNoCache[$networkId] == 1) ? 'checked' : '') . ' name="no_cache"> <label for="link-no-cache">' . esc_html__('Activate Instant Caching', 'blog2social') . '</label> <a href="#" class="b2s-info-btn vertical-middle del-padding-left b2sInfoNoCacheBtn">' . esc_html__('Info', 'Blog2Social') . '</a></div>';
            }
            $html .= '<br>';
            $html .= '<hr>';
        }
        if (B2S_PLUGIN_USER_VERSION < 1) {
            $html .= '<div class="b2s-btn-disabled">';
        }
        $html .= '<div class="tab-content clearfix">';
        $html .= '<div class="tab-pane active b2s-template-tab-0" id="b2s-template-profile">';
        $html .= $this->getEditTemplateFormContent($networkId, 0, $schema);
        $html .= '</div>';
        if (isset($schema[1]) && !empty($schema[1])) {
            $html .= '<div class="tab-pane b2s-template-tab-1" id="b2s-template-page">';
            $html .= $this->getEditTemplateFormContent($networkId, 1, $schema);
            $html .= '</div>';
        }
        if (isset($schema[2]) && !empty($schema[2])) {
            $html .= '<div class="tab-pane b2s-template-tab-2" id="b2s-template-group">';
            $html .= $this->getEditTemplateFormContent($networkId, 2, $schema);
            $html .= '</div>';
        }
        $html .= '</div>';
        if (B2S_PLUGIN_USER_VERSION < 1) {
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function getEditTemplateFormContent($networkId, $networkType, $schema) {
        $defaultTemplate = unserialize(B2S_PLUGIN_NETWORK_SETTINGS_TEMPLATE_DEFAULT);
        $min = $defaultTemplate[$networkId][$networkType]['short_text']['range_min'];
        $limit = $defaultTemplate[$networkId][$networkType]['short_text']['limit'];

        //V5.6.1
        if (!isset($schema[$networkType]['short_text']['excerpt_range_max'])) {
            $schema[$networkType]['short_text']['excerpt_range_max'] = $defaultTemplate[$networkId][$networkType]['short_text']['excerpt_range_max'];
        }
        
        $content = '';
        if($schema[$networkType]['format'] !== false){
            $content .= '<div class="row">';
            $content .= '<div class="col-md-12 media-heading">';
            $content .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Format', 'blog2social') . '</span> <a href="#" data-network-id="' . esc_attr($networkId) . '" class="b2s-info-btn del-padding-left b2sInfoFormatBtn">' . esc_html__('Info', 'Blog2Social') . '</a>';
            $content .= '<button class="pull-right btn btn-primary btn-xs b2s-edit-template-load-default" data-network-type="' . esc_attr($networkType) . '">' . esc_html__('Load default settings', 'blog2social') . '</button>';
            $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="row">';
            $content .= '<div class="col-md-12">';
            if ($schema[$networkType]['format'] == 0) {
                $content .= '<button class="btn btn-primary btn-sm b2s-edit-template-link-post pull-left" data-network-type="' . esc_attr($networkType) . '">' . esc_html((($networkId != 12) ? __('Link', 'blog2social') : __('Image with frame', 'blog2social'))) . '</button>';
                $content .= '<button class="btn btn-light btn-sm b2s-edit-template-image-post pull-left" data-network-type="' . esc_attr($networkType) . '">' . esc_html((($networkId != 12) ? __('Image', 'blog2social') : __('Image cut out', 'blog2social'))) . '</button>';
            } else {
                $content .= '<button class="btn btn-light btn-sm b2s-edit-template-link-post pull-left" data-network-type="' . esc_attr($networkType) . '">' . esc_html((($networkId != 12) ? __('Link', 'blog2social') : __('Image with frame', 'blog2social'))) . '</button>';
                $content .= '<button class="btn btn-primary btn-sm b2s-edit-template-image-post pull-left" data-network-type="' . esc_attr($networkType) . '">' . esc_html((($networkId != 12) ? __('Image', 'blog2social') : __('Image cut out', 'blog2social'))) . '</button>';
            }
            $content .= '<input type="hidden" class="b2s-edit-template-post-format" value="' . esc_attr($schema[$networkType]['format']) . '" data-network-type="' . esc_attr($networkType) . '">';
            $content .= '</div>';
            $content .= '</div>';
            $content .= '<br>';
        }
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12 media-heading">';
        $content .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Content', 'blog2social') . '</span> <a href="#" class="b2s-info-btn del-padding-left b2sInfoContentBtn">' . esc_html__('Info', 'Blog2Social') . '</a>';
        if($schema[$networkType]['format'] === false){
            $content .= '<button class="pull-right btn btn-primary btn-xs b2s-edit-template-load-default" data-network-type="' . esc_attr($networkType) . '">' . esc_html__('Load default settings', 'blog2social') . '</button>';
        }
        $content .= '</div>';
        $content .= '</div>';
        if($networkId == 12) {
            $content .= '<div class="row">';
            $content .= '<div class="col-md-12">';
            $content .= '<div class="alert alert-warning b2s-edit-template-hashtag-warning" style="display:none;">' . esc_html__('Instagram supports up to 30 hashtags. Please reduce the number of hashtags in your post.', 'blog2social') . '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12">';
        $content .= '<div class="b2s-padding-bottom-5">'
                . '<button type="button" draggable="true" class="btn btn-primary btn-xs b2s-edit-template-content-post-title b2s-edit-template-content-post-item" data-network-type="' . esc_attr($networkType) . '">{TITLE}</button>'
                . '<button type="button" draggable="true" class="btn btn-primary btn-xs b2s-edit-template-content-post-excerpt b2s-edit-template-content-post-item" data-network-type="' . esc_attr($networkType) . '">{EXCERPT}</button>'
                . '<button type="button" draggable="true" class="btn btn-primary btn-xs b2s-edit-template-content-post-content b2s-edit-template-content-post-item" data-network-type="' . esc_attr($networkType) . '">{CONTENT}</button>'
                . '<button type="button" draggable="true" class="btn btn-primary btn-xs b2s-edit-template-content-post-keywords b2s-edit-template-content-post-item" data-network-type="' . esc_attr($networkType) . '">{KEYWORDS}</button>'
                . '<button type="button" draggable="true" class="btn btn-primary btn-xs b2s-edit-template-content-post-author b2s-edit-template-content-post-item" data-network-type="' . esc_attr($networkType) . '">{AUTHOR}</button>'
                . '<button type="button" class="btn btn-primary btn-xs b2s-edit-template-content-clear-btn pull-right" data-network-type="' . esc_attr($networkType) . '">' . esc_html__('clear', 'blog2social') . '</button>'
                . '</div>';
        $content .= '<textarea class="b2s-edit-template-post-content" style="width: 100%;" data-network-type="' . esc_attr($networkType) . '" ' . ((B2S_PLUGIN_USER_VERSION < 1) ? 'readonly="true"' : '') . '>' . esc_html($schema[$networkType]['content']) . '</textarea>';
        $content .= '<input class="b2s-edit-template-content-selection-start" data-network-type="' . esc_attr($networkType) . '" type="hidden" value="0">';
        $content .= '<input class="b2s-edit-template-content-selection-end" data-network-type="' . esc_attr($networkType) . '" type="hidden" value="0">';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12 b2s-edit-template-link-info">';
        $content .= '<i class="glyphicon glyphicon-info-sign"></i> ' . esc_html__('The link will be added automatically at the end of the post.', 'blog2social');
        if ((int) $limit != 0) {
            $content .= '<br><i class="glyphicon glyphicon-info-sign"></i> ' . esc_html(__('Network limit', 'blog2social') . ': ' . $limit . ' ' . __('characters', 'blog2social'));
        }
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<br>';
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12 media-heading">';
        $content .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Character limit', 'blog2social') . ' (CONTENT, EXCERPT)</span> <a href="#" class="b2s-info-btn del-padding-left b2sInfoCharacterLimitBtn">' . esc_html__('Info', 'Blog2Social') . '</a>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12">';
        $content .= '<div class="form-group">';
        $content .= '<label class="col-sm-2 control-label b2s-edit-template-character-limit-label">{CONTENT}</label> <input type="number" class="b2s-edit-template-range" data-network-type="' . esc_attr($networkType) . '" value="' . esc_attr($schema[$networkType]['short_text']['range_max']) . '" min="1" max="' . esc_attr((($schema[$networkType]['short_text']['limit']) ? $schema[$networkType]['short_text']['limit'] : '')) . '" ' . ((B2S_PLUGIN_USER_VERSION < 1) ? 'readonly="true"' : '') . '>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12">';
        $content .= '<div class="form-group">';
        $content .= '<label class="col-sm-2 control-label b2s-edit-template-character-limit-label">{EXCERPT}</label> <input type="number" class="b2s-edit-template-excerpt-range" data-network-type="' . esc_attr($networkType) . '" value="' . esc_attr($schema[$networkType]['short_text']['excerpt_range_max']) . '" min="1" max="' . esc_attr((($schema[$networkType]['short_text']['limit']) ? $schema[$networkType]['short_text']['limit'] : '')) . '" ' . ((B2S_PLUGIN_USER_VERSION < 1) ? 'readonly="true"' : '') . '>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
        $content .= '<div class="col-md-12 b2s-edit-template-link-info">';
        $content .= '<i class="glyphicon glyphicon-info-sign"></i> ' . esc_html(__('recommended length', 'blog2social') . ': ' . $min . ' ' . __('characters', 'blog2social') . (((int) $limit != 0) ? '; ' . __('Network limit', 'blog2social') . ': ' . $limit . ' ' . __('characters', 'blog2social') : ''));
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<hr>';
        $content .= '<br>';

        $content .= $this->networkPreview($networkId, $networkType, $schema);

        return $content;
    }

    private function networkPreview($networkId, $networkType, $schema) {
        $domain = get_home_url();
        $title = get_bloginfo('title');
        $desc = get_bloginfo('description');
        $preview = '';
        switch ($networkId) {
            case '1':
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Preview', 'blog2social') . ':</span>';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-8 b2s-edit-template-preview-border b2s-edit-template-preview-border-1">';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<img class="b2s-edit-template-preview-profile-img-1" src="' . plugins_url('/assets/images/b2s@64.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-10">';
                $preview .= '<span class="b2s-edit-template-preview-profile-name-1">Blog2Social</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-link-preview" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 0) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-content-1">';
                $preview .= '<span class="b2s-edit-template-preview-content" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-image-border-1">';
                $preview .= '<img class="b2s-edit-template-preview-link-image b2s-edit-template-preview-link-image-1" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-link-meta-box-1">';
                $preview .= '<span>' . esc_html($domain) . '</span><br>';
                $preview .= '<span class="b2s-edit-template-preview-link-title">' . esc_html($title) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-image-preview" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 1) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-content-1">';
                $preview .= '<span class="b2s-edit-template-preview-content" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-image-border-1">';
                $preview .= '<img class="b2s-edit-template-preview-image-image b2s-edit-template-preview-image-image-1" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<img class="b2s-edit-template-preview-like-icons-1" src="' . plugins_url('/assets/images/settings/like-icons-1.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                break;
            case '2':
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Preview', 'blog2social') . ':</span>';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-8">';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<img class="b2s-edit-template-preview-profile-img-2" src="' . plugins_url('/assets/images/b2s@64.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-10 b2s-edit-template-preview-2">';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<span class="b2s-edit-template-preview-profile-name-2">Blog2Social</span> <span class="b2s-edit-template-preview-profile-handle-2">@blog2social</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-link-preview" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 0) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<span class="b2s-edit-template-preview-content b2s-edit-template-preview-content-2" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row b2s-edit-template-preview-link-meta-box-2">';
                $preview .= '<div class="col-sm-3 b2s-edit-template-preview-link-meta-box-image-2">';
                $preview .= '<img class="b2s-edit-template-preview-link-image b2s-edit-template-preview-link-image-2" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-9" style="padding-top: 12px;">';
                $preview .= '<span>' . esc_html($title) . '</span><br>';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-desc-2">' . esc_html($desc) . '</span><br>';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-domain-2">' . esc_html($domain) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-image-preview" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 1) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<span class="b2s-edit-template-preview-content b2s-edit-template-preview-content-2" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<img class="b2s-edit-template-preview-image-image b2s-edit-template-preview-image-image-2" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<img class="b2s-edit-template-preview-like-icons-2" src="' . plugins_url('/assets/images/settings/like-icons-2.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                break;
            case '3':
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Preview', 'blog2social') . ':</span>';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-8 b2s-edit-template-preview-border b2s-edit-template-preview-border-3">';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<img class="b2s-edit-template-preview-profile-img-3" src="' . plugins_url('/assets/images/b2s@64.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-10">';
                $preview .= '<span class="b2s-edit-template-preview-profile-name-3">Blog2Social</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-link-preview" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 0) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-content-3">';
                $preview .= '<span class="b2s-edit-template-preview-content" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-image-border-3">';
                $preview .= '<img class="b2s-edit-template-preview-link-image b2s-edit-template-preview-link-image-3" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row b2s-edit-template-preview-link-meta-box-3">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-title-3">' . esc_html($title) . '</span><br>';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-domain-3">' . esc_html($domain) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-image-preview" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 1) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-content-3">';
                $preview .= '<span class="b2s-edit-template-preview-content" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-image-border-3">';
                $preview .= '<img class="b2s-edit-template-preview-image-image b2s-edit-template-preview-image-image-3" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<img class="b2s-edit-template-preview-like-icons-3" src="' . plugins_url('/assets/images/settings/like-icons-3.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                break;
            case '12':
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Preview', 'blog2social') . ':</span>';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-8 b2s-edit-template-preview-border b2s-edit-template-preview-border-12">';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<img class="b2s-edit-template-preview-profile-img-12" src="' . plugins_url('/assets/images/b2s@64.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-10 b2s-edit-template-preview-profile-name-12">';
                $preview .= '<span>Blog2Social</span>';
                $preview .= '<span class="pull-right b2s-edit-template-preview-dots-12">...</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-image-border-12">';
                $preview .= '<img class="b2s-edit-template-preview-image-12 b2s-edit-template-link-preview b2s-edit-template-image-frame" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 0) ? '' : 'style="display: none;"') . ' src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '<img class="b2s-edit-template-preview-image-12 b2s-edit-template-image-preview b2s-edit-template-image-cut" data-network-type="' . esc_attr($networkType) . '" ' . (((int) $schema[$networkType]['format'] == 1) ? '' : 'style="display: none;"') . ' src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<img class="b2s-edit-template-preview-like-icons-12" src="' . plugins_url('/assets/images/settings/like-icons-12.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<span class="b2s-edit-template-preview-content-profile-name-12">Blog2Social</span><span class="b2s-edit-template-preview-content b2s-edit-template-preview-content-12" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                break;
            case '19':
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<span class="b2s-edit-template-section-headline">' . esc_html__('Preview', 'blog2social') . ':</span>';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-8 b2s-edit-template-preview-border b2s-edit-template-preview-border-19">';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-2">';
                $preview .= '<img class="b2s-edit-template-preview-profile-img-19" src="' . plugins_url('/assets/images/b2s@64.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-10">';
                $preview .= '<span class="b2s-edit-template-preview-profile-name-19">Blog2Social</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-link-preview" data-network-type="' . esc_attr($networkType) . '" ' . (($schema[$networkType]['format'] !== false && (int) $schema[$networkType]['format'] == 0) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row b2s-edit-template-preview-header-19">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-content-19">';
                $preview .= '<span class="b2s-edit-template-preview-content" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-4 b2s-edit-template-preview-image-border-19">';
                $preview .= '<img class="b2s-edit-template-preview-link-image b2s-edit-template-preview-link-image-19" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '<div class="col-sm-8 b2s-edit-template-preview-link-meta-box-19">';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-title-19">' . esc_html($title) . '</span><br>';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-desc-19">' . esc_html($desc) . '</span><br>';
                $preview .= '<span class="b2s-edit-template-preview-link-meta-box-domain-19">' . esc_html($domain) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="b2s-edit-template-image-preview" data-network-type="' . esc_attr($networkType) . '" ' . (($schema[$networkType]['format'] === false || (int) $schema[$networkType]['format'] == 1) ? '' : 'style="display: none;"') . '>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-content-19">';
                $preview .= '<span class="b2s-edit-template-preview-content" data-network-type="' . esc_attr($networkType) . '">' . preg_replace("/\n/", "<br>", esc_html($schema[$networkType]['content'])) . '</span>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12 b2s-edit-template-preview-image-border-19">';
                $preview .= '<img class="b2s-edit-template-preview-image-image b2s-edit-template-preview-image-image-19" src="' . plugins_url('/assets/images/no-image.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '<div class="row">';
                $preview .= '<div class="col-sm-12">';
                $preview .= '<img class="b2s-edit-template-preview-like-icons-19" src="' . plugins_url('/assets/images/settings/like-icons-19.png', B2S_PLUGIN_FILE) . '">';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                $preview .= '</div>';
                break;
            default:
                break;
        }
        return $preview;
    }

}
