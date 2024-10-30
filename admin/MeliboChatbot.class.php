<?php

class MeliboChatbot  {
    protected $CDN_URL;
    protected $svgIcon;
    protected $apiKeyValid;
    protected $meliboValidationClass;

    const PLUGIN_NAME = 'melibo-chatbot';

    public function __construct() {
        $this->CDN_URL = 'https://cdn.melibo.de/bundle.js';
        $this->svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" zoomAndPan="magnify" viewBox="0 0 30 30.000001" height="40" preserveAspectRatio="xMidYMid meet" version="1.0"><defs><clipPath id="id1"><path d="M 1.007812 3.046875 L 29.3125 3.046875 L 29.3125 26.273438 L 1.007812 26.273438 Z M 1.007812 3.046875 " clip-rule="nonzero"/></clipPath></defs><g clip-path="url(#id1)"><path fill="rgb(100%, 100%, 100%)" d="M 7.871094 26.273438 L 7.746094 19.460938 L 4.777344 19.460938 L 1.003906 16.007812 L 1.003906 6.507812 L 4.769531 3.046875 L 25.519531 3.046875 L 29.285156 6.507812 L 29.285156 16.007812 L 25.554688 19.460938 L 17.769531 19.460938 Z M 5.273438 18.207031 L 8.988281 18.207031 L 9.09375 23.898438 L 17.371094 18.207031 L 25.039062 18.207031 L 28.035156 15.457031 L 28.035156 7.058594 L 25.039062 4.304688 L 5.273438 4.304688 L 2.28125 7.058594 L 2.28125 15.457031 Z M 5.273438 18.207031 " fill-opacity="1" fill-rule="nonzero"/></g><path fill="rgb(100%, 100%, 100%)" d="M 17.816406 12.324219 L 17.050781 11.324219 L 21.234375 8.132812 L 25.253906 11.332031 L 24.460938 12.316406 L 21.214844 9.734375 Z M 17.816406 12.324219 " fill-opacity="1" fill-rule="nonzero"/><path fill="rgb(100%, 100%, 100%)" d="M 6.667969 12.339844 L 5.898438 11.339844 L 10.140625 8.101562 L 14.21875 11.347656 L 13.425781 12.332031 L 10.125 9.703125 Z M 6.667969 12.339844 " fill-opacity="1" fill-rule="nonzero"/></svg>';
        $this->apiKeyValid = false;
        $this->meliboValidationClass = new MeliboValidation();
        new MeliboTranslation();

        wp_register_style( 'admin.css', plugin_dir_url( __FILE__ ) . 'inc/styles/admin.css', array(), MELIBO_VERSION );
        wp_enqueue_style( 'admin.css');

        add_action( 'admin_enqueue_scripts', array($this, 'melibo_select2_enqueue'));
        add_action( 'admin_init', array($this, 'initSettings'));
        add_action( 'admin_menu', array($this, 'adminPage'));
        add_action( 'wp_ajax_melibopages', array($this, 'ajaxGetAllPages'));
        add_action( 'admin_notices', array( $this, 'displayNotices'));
        add_action( 'wp_enqueue_scripts', array($this, 'showChatbot'));

        add_filter( 'plugin_action_links_melibo-chatbot/melibo-chatbot.php', array($this, 'addSettingsLink'));
    }

    public function initSettings() {
        $this->initSections();
        
        $this->initApiKeyField();
        $this->initEnvironmentIDField();
        $this->initSelectPagesForEnvironmentChatbotField();
        $this->initActivationField();
        $this->initExcludedPages();
    }

    private function initSections() {
        add_settings_section('melibo_general_section', __('General', MeliboChatbot::PLUGIN_NAME), null, 'melibo-settings-page');
        add_settings_section('melibo_environment_section', __('Environment', MeliboChatbot::PLUGIN_NAME), null, 'melibo-settings-page');
    }

    private function initApiKeyField() {
        add_settings_field(
            'melibo_api_key',
            __('API Key', MeliboChatbot::PLUGIN_NAME),
            array($this, 'inputHTML'),
            'melibo-settings-page',
            'melibo_general_section',
            array(
                'name' => 'melibo_api_key',
                'info' => __( 'You can find the API key for your chatbot in your melibo account under "Settings" > "Installation". Here you can see the API key in the code snippet.', MeliboChatbot::PLUGIN_NAME)
            )
        );
        register_setting('meliboplugin', 'melibo_api_key', array($this->meliboValidationClass, 'apiKeyValidation'));
    }

    public function test($input) {
        add_settings_error('melibo_api_key', 'melibo_api_key_error', __('The API key must not contain any spaces.', MeliboChatbot::PLUGIN_NAME), 'error');
        return $input;
    }

    private function initEnvironmentIDField() {
        add_settings_field(
            'melibo_environment_id',
            __('Environment ID', MeliboChatbot::PLUGIN_NAME),
            array($this, 'inputHTML'),
            'melibo-settings-page',
            'melibo_environment_section',
            array(
                'name' => 'melibo_environment_id',
                'info' => __('You can optionally use the Environment ID if you want to use different chat widgets. For example, you can give your chatbot a different look for your contact page than for your homepage. This way you can give your chatbot a different appearance on different subpages on your website.', MeliboChatbot::PLUGIN_NAME)
            )
        );
        register_setting('meliboplugin', 'melibo_environment_id', array($this->meliboValidationClass, 'environmentIDValidation'));
    }

    private function initSelectPagesForEnvironmentChatbotField() {
        add_settings_field(
            'melibo_environment_select_pages',
            __('page selection', MeliboChatbot::PLUGIN_NAME),
            array($this, 'checkboxPageListHTML'),
            'melibo-settings-page',
            'melibo_environment_section',
            array(
                'id' => 'melibo_environment_select_pages',
                'name' => 'melibo_environment_select_pages',
                'info' => __('Select the pages you want your chatbot to appear on.', MeliboChatbot::PLUGIN_NAME),
                'multiple' => true
            )
        );
        register_setting('meliboplugin', 'melibo_environment_select_pages');
    }

    private function initActivationField() {
        add_settings_field(
            'melibo_activate',
            __('Activate chatbot', MeliboChatbot::PLUGIN_NAME),
            array($this, 'checkboxHTML'),
            'melibo-settings-page',
            'melibo_general_section',
            array(
                'name' => 'melibo_activate',
                'info' => __('As soon as you tick the box here and click on "Save changes", your melibo chatbot will appear on your website.', MeliboChatbot::PLUGIN_NAME)
            )
        );
        register_setting('meliboplugin', 'melibo_activate', array($this->meliboValidationClass, 'activateValidation'));
    }

    private function initExcludedPages() {
        add_settings_field(
            'melibo_excluded_pages',
            __('excluded pages', MeliboChatbot::PLUGIN_NAME),
            array($this, 'checkboxPageListHTML'),
            'melibo-settings-page',
            'melibo_general_section',
            array(
                'id' => 'melibo_excluded_pages',
                'name' => 'melibo_excluded_pages',
                'info' => sprintf('%s <u>%s</u> %s',
                                  __('Select the pages you do', MeliboChatbot::PLUGIN_NAME),
                                  __('not', MeliboChatbot::PLUGIN_NAME),
                                  __('want your chatbot to appear on.', MeliboChatbot::PLUGIN_NAME)),
                'multiple' => true
            )
        );
        register_setting('meliboplugin', 'melibo_excluded_pages');
    }

    public function melibo_select2_enqueue(){

		wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
		wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
		
		wp_enqueue_script('melibo_admin',  plugin_dir_url( __FILE__ ) . '/inc/js/admin.js', array( 'jquery', 'select2' ) ); 
		
	}

    public function checkboxHTML($args) {
        return MeliboView::createCheckbox($args);
    }

    public function checkboxPageListHTML($args) {
        return MeliboView::createAllPagescheckboxList($args);
    }

    public function inputHTML($args) { 
        return MeliboView::createTextInput($args);
    }

    public function ajaxGetAllPages() {
        MeliboAjaxFunctions::getAllPages();
    }

    public function adminPage() {
        add_menu_page(
            __('melibo Settings', MeliboChatbot::PLUGIN_NAME),
            'melibo Chatbot',
            'manage_options',
            'melibo-settings-page',
            array($this, 'createMeliboChatbotPage'),
            $this->get_icon_svg(),
            99
        );
    }

    public function get_icon_svg( $base64 = true ) {
		if ( $base64 ) {
			//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- This encoding is intended.
			return 'data:image/svg+xml;base64,' . base64_encode( $this->svgIcon );
		}

		return $svg;
	}

    public function createMeliboChatbotPage() {
        // check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        MeliboView::createMeliboChatbotPage();
    }

    public function showChatbot($content) {
        $activated = $this->isChatbotActivated();
        $showOnPage = $this->showOnPage();
        $isExcludedPage = $this->isExcludedPage();
        $isEnvironmentPage = $this->isEnvironmentPage();

        if($activated AND $showOnPage) {
            if($isExcludedPage) {
                return true;
            }

            wp_enqueue_script('cdn-melibo-chatbot', $this->CDN_URL);
            if($isEnvironmentPage) { ?>
                <melibo-webchat api-key="<?php echo esc_attr(get_option('melibo_api_key')); ?>"
                                environment-id="<?php echo esc_attr(get_option('melibo_environment_id')); ?>"></melibo-webchat>
                <?php
            } else { ?>
                <melibo-webchat api-key="<?php echo esc_attr(get_option('melibo_api_key')); ?>"></melibo-webchat>
                <?php
            }
        }
    }

    private function isChatbotActivated() {
        return get_option('melibo_activate', '1');
    }

    private function showOnPage() {
        return (is_page() OR is_front_page() OR is_single());
    }

    private function isExcludedPage() {
        global $post;
        $exludedPages = get_option('melibo_excluded_pages');
        return is_array($exludedPages) AND in_array($post->ID, $exludedPages);
    }

    private function isEnvironmentPage() {
        global $post;
        $environmentPages = get_option('melibo_environment_select_pages');
        return is_array($environmentPages) AND in_array($post->ID, $environmentPages);
    }

    public function addSettingsLink($links) {
        
        $url = esc_url( add_query_arg(
            'page',
            'melibo-settings-page',
            get_admin_url() . 'admin.php'
        ) );
        
        $settings_link = "<a href='$url'>" . __( 'Settings', MeliboChatbot::PLUGIN_NAME) . '</a>';
        
        array_push(
            $links,
            $settings_link
        );
        return $links;
    }

    public function displayNotices() {
        global $hook_suffix;

        if( in_array( $hook_suffix, array(
            'index.php', # dashboard
            'plugins.php',
            'toplevel_page_melibo-settings-page'
        ))) {
            wp_register_style( 'notice.css', plugin_dir_url( __FILE__ ) . 'inc/styles/notice.css', array(), MELIBO_VERSION );
            wp_enqueue_style( 'notice.css');

            $this->createNoAPIKeyExistsNotice();
            $this->createErrorSettingsNotice();
            
        }        
    }

    private function createNoAPIKeyExistsNotice() {
        if(empty(trim(get_option('melibo_api_key')))) {
            MeliboView::loadFile('notice', array('type' => 'no-api-key'));
        }
    }

    private function createErrorSettingsNotice() {
        $settingsErrors = get_settings_errors();

        foreach($settingsErrors as $error) {
            MeliboView::loadFile('notice', array('type' => 'error', 'msg' => $error['message']));
        }
    }
 }