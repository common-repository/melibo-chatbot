<?php

class MeliboView {

    public static function LoadFile($name, array $args = array()) {
        $args = apply_filters('melibo_view_arguments', $args, $name);
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain( MeliboChatbot::PLUGIN_NAME );

		$file = plugin_dir_path( __FILE__ ) . '/inc/'. $name . '.php';

		include( $file );
    }

	public static function createMeliboChatbotPage() {
		//Get the active tab from the $_GET param
        $default_tab = null;
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

        ?>

        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <nav class="nav-tab-wrapper">
                <a href="?page=melibo-settings-page" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>"><?php echo __('Settings', MeliboChatbot::PLUGIN_NAME); ?></a>
                <a href="?page=melibo-settings-page&tab=first_steps" class="nav-tab <?php if($tab==='first_steps'):?>nav-tab-active<?php endif; ?>"><?php echo __('First Steps', MeliboChatbot::PLUGIN_NAME); ?></a>
				<a href="?page=melibo-settings-page&tab=academy" class="nav-tab <?php if($tab==='academy'):?>nav-tab-active<?php endif; ?>"><?php echo __('Academy', MeliboChatbot::PLUGIN_NAME); ?></a>
            </nav>

            <div class="tab-content">
                <?php switch($tab) :
					case 'first_steps':
						MeliboView::firstStepsTab();
						break;
					case 'academy':
						MeliboView::academyTab();
						break;
					default:
						MeliboView::settingsTab();
						break;
                endswitch; ?>
            </div>
    	</div>
		<?php
	}

	public static function settingsTab() {
		?>
		<form action="options.php" method="POST">
			<?php
				settings_fields('meliboplugin');
				do_settings_sections('melibo-settings-page');
				submit_button();
			?>
		</form>
		<?php
	}

	public static function firstStepsTab() { ?>
		<div class="first_steps_conent">
			<details class="collapse" open>
				<summary class="title">1. <?php echo __('Registration', MeliboChatbot::PLUGIN_NAME); ?></summary>
				<hr class="divider">
				<div class="description">
					<dl>
						<b>
							1. <?php echo __('Step: Link to the', MeliboChatbot::PLUGIN_NAME); ?>
							<a href="https://editor.melibo.de/registration" target="_blank"><?php echo __('register page', MeliboChatbot::PLUGIN_NAME); ?></a>
						</b>
					</dl>
					<dd>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/first_steps_1.png'; ?>" alt="<?php echo __('register page', MeliboChatbot::PLUGIN_NAME); ?>">
						<br />
						<span>
							<?php echo __("If you don't have a melibo account yet, you need to register for free using the link above. Fill in the fields in the registration form and you're just one click away from getting started.", MeliboChatbot::PLUGIN_NAME); ?>
						</span>
					</dd>

					<dl>
						<b>2. <?php echo __('Step: Activation email', MeliboChatbot::PLUGIN_NAME); ?></b>
					</dl>
					<dd>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/first_steps_2.png'; ?>" alt="<?php echo __('Activation email', MeliboChatbot::PLUGIN_NAME); ?>">
						<br />
						<span>
						<?php echo __('Go to your mailbox and activate your account via the button. You will be automatically redirected to the login after confirmation.', MeliboChatbot::PLUGIN_NAME); ?>
						</span>
					</dd>

					<dl>
						<b>3. <?php echo __('Step: Log in', MeliboChatbot::PLUGIN_NAME); ?></b>
					</dl>
					<dd>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/first_steps_3.png'; ?>" alt="<?php echo __('Log in', MeliboChatbot::PLUGIN_NAME); ?>">
						<br />
						<span>
							<?php echo __('Enter your email address and password. If you have forgotten your password, you can reset it here. After successfully logging in, you will be redirected to the editor. On-boarding will take place, which will explain the first steps with melibo to you.', MeliboChatbot::PLUGIN_NAME); ?>
						</span>
					</dd>
				</div>
			</details>

			<details class="collapse">
				<summary class="title">2. <?php echo __('Chatbot Design', MeliboChatbot::PLUGIN_NAME); ?></summary>
				<hr class="divider">
				<div class="description">
					<dl>
						<b>1. <?php echo __('Step: Create your chatbot design', MeliboChatbot::PLUGIN_NAME); ?></b>
					</dl>
					<dd>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/first_steps_4.png'; ?>" alt="<?php echo __('View Chatbot Design Settings', MeliboChatbot::PLUGIN_NAME); ?>">
						<br />
						<span>
						<?php echo __('Go to the "Settings" on melibo and then to the "Chatbot Styler" tab in the left column. Here you can define the design of your chatbot and adapt it to your brand. Learn', MeliboChatbot::PLUGIN_NAME); ?>
							<a href="https://melibo.de/corporate-design-wie-du-deinen-bot-personalisieren-kannst/" target="_blank"><?php echo __('more here', MeliboChatbot::PLUGIN_NAME); ?></a>.
						</span>
					</dd>

					<dl>
						<b>2. <?php echo __('Step: Templates', MeliboChatbot::PLUGIN_NAME); ?></b>
					</dl>
					<dd>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/first_steps_5.png'; ?>" alt ="<?php echo __('Choose one or more templates', MeliboChatbot::PLUGIN_NAME); ?>">
						<br />
						<span>
							<?php echo __('Choose from a variety of ready-made chats and deposit your answer. The templates save you time when setting up the chatbot and give you inspiration for further chats.', MeliboChatbot::PLUGIN_NAME); ?>
						</span>
					</dd>

					<dl>
						<b>3. <?php echo __('Step: Create hot topics - design the chatbot introduction', MeliboChatbot::PLUGIN_NAME); ?></b>
					</dl>
					<dd>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/first_steps_6.png'; ?>" alt="<?php echo __('Hot topics and introductory texts', MeliboChatbot::PLUGIN_NAME); ?>">
						<br />
						<span>
							<?php echo __('Which chats should your chat welcome your users with? With the hot topics you can suggest topics to your users at the beginning of the chat. To do this, click on "Add" and select the chats from your library that should be included in the introduction. Then test the chats in your chat window. You can find out more about the hot topics', MeliboChatbot::PLUGIN_NAME); ?>
							<a href="https://melibo.de/hot-topics-gestalte-die-einleitung-deines-chatbots/" target="_blank"><?php echo __('here', MeliboChatbot::PLUGIN_NAME); ?></a>
						</span>
					</dd>
				</div>
			</details>

			<details class="collapse">
				<summary class="title">3. <?php echo __('Installation', MeliboChatbot::PLUGIN_NAME); ?></summary>
				<hr class="divider">
				<div class="description">
					<p>
						<?php echo __('Once you have created your chatbot design and the chats, you can take your bot online! Simply follow the steps below:', MeliboChatbot::PLUGIN_NAME); ?>
					</p>
					<ol>
						<li><?php echo __('Copy your personal API key and paste it in the "API key" field.', MeliboChatbot::PLUGIN_NAME); ?></li>
						<li><?php echo __('To take your bot online on the website, check the "Activate chatbot" box.', MeliboChatbot::PLUGIN_NAME); ?></li>
						<li><?php echo __('Save your settings.', MeliboChatbot::PLUGIN_NAME); ?></li>
					</ol>
					<br />
					<?php MeliboView::contactUS(); ?>
				</div>
			</details>
		</div>
		<?php
	}

	public static function academyTab() { ?>
		<div class="academy_conent">
			<p>
				<?php echo __('To get started, we recommend that you watch our video series "How does...?" melibo on', MeliboChatbot::PLUGIN_NAME); ?>
				<a href="https://www.youtube.com/channel/UCzeW2hdGKBPjxdNG0pusgMA/playlists" target="_blank"><?php echo __('YouTube.', MeliboChatbot::PLUGIN_NAME); ?></a>
			</p>
			<p>
				<?php echo __('As a first video, it is worth watching the video', MeliboChatbot::PLUGIN_NAME); ?>
				<a href="https://www.youtube.com/watch?v=URJnezMQEw8" target="_blank"><?php echo __('"How melibo works | Everything you need to know"', MeliboChatbot::PLUGIN_NAME); ?></a>
				<?php echo __(', because all the important functions are presented there.', MeliboChatbot::PLUGIN_NAME); ?>
			</p>

			<details class="collapse" open>
				<summary class="title">1. <?php echo __('Knowledge Base', MeliboChatbot::PLUGIN_NAME); ?></summary>
				<hr class="divider">
				<div class="description">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/academy_knowledge.png'; ?>" alt="Startseite der Knowledge Base" />
					<br />
					<span>
						<?php echo __('In our Knowledge Base you will find answers and instructions on all topics concerning melibo. Click here to go to the', MeliboChatbot::PLUGIN_NAME); ?>
						<a href="https://melibo.de/help-center/knowledge-base/" target="_blank"><?php echo __('Knowledge Base', MeliboChatbot::PLUGIN_NAME); ?></a>
					</span>
				
				</div>
			</details>

			<details class="collapse">
				<summary class="title">2. <?php echo __('Blog article', MeliboChatbot::PLUGIN_NAME); ?></summary>
				<hr class="divider">
				<div class="description">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'inc/img/academy_blog_article.png'; ?>" alt="Startseite der Blog-Artikel"/>
					<br />
					<span>
						<?php echo __('The blog provides you with additional content, exciting tips & tricks, as well as the latest news & updates. We publish a blog article every week. Discover our', MeliboChatbot::PLUGIN_NAME); ?>
						<a href="https://melibo.de/help-center/meliblog/" target="_blank"><?php echo __('blog', MeliboChatbot::PLUGIN_NAME); ?></a>
					</span>
				
				</div>
			</details>
		</div>
	<?php
	}

	public static function contactUS() { ?>
		<div class="text-center">
			<p><?php echo __('Do you still have questions or need help?', MeliboChatbot::PLUGIN_NAME); ?></p>
			<p><b><?php echo __("Let's Talk! Write to us.", MeliboChatbot::PLUGIN_NAME); ?></b></p>
			<p>
				<?php echo __("Ask us your question at", MeliboChatbot::PLUGIN_NAME); ?>
				<a href="mailto:support@melibo.de">support@melibo.de</a>
				<br />
				<?php echo __("Would you like personal advice? Then contact us at", MeliboChatbot::PLUGIN_NAME); ?>
				<a href="mailto:info@melibo.de">info@melibo.de</a>
				<?php echo __("and we will arrange a free appointment.", MeliboChatbot::PLUGIN_NAME); ?>
				<br /><br />
				<?php echo __("Many greetings,", MeliboChatbot::PLUGIN_NAME); ?>
				<br />
				<?php echo __("Your", MeliboChatbot::PLUGIN_NAME); ?>
				<b>melibo</b>
				<?php echo __("team", MeliboChatbot::PLUGIN_NAME); ?>
			</p>
		</div>
	<?php
	}

	public static function createCheckbox($args) { 
		?>
		<input name="<?php echo esc_attr($args['name']); ?>" type="checkbox" value="1" <?php checked(get_option($args['name']), '1'); ?>>
        <?php if(isset($args['info']) == true) { ?>
            <p><?php echo esc_attr($args['info']); ?></p>
        <?php }
	}

	public static function createTextInput($args) { ?>
		<input name="<?php echo esc_attr($args['name']); ?>" type="text" value="<?php echo esc_attr(get_option($args['name'])); ?>"/>
        <?php if(isset($args['info']) == true) { ?>
            <p><?php echo esc_attr($args['info']); ?></p>
        <?php }
	}

	public static function createAllPagescheckboxList($args) { ?>
		<select id="<?php echo esc_attr($args['id']); ?>"
				name="<?php echo esc_attr($args['name']); ?>[]"
				<?php if($args['multiple'] && $args['multiple'] === true) { echo 'multiple="nultiple"'; } ?>
				style="width: 30%;">
				<?php
				foreach(get_option($args['name']) as $pageID) {
					$title = get_the_title($pageID);
					echo '<option value="'.$pageID.'" selected="selected">'.$title.'</option>';
				}
				?>
		</select>
		<?php if(isset($args['info']) == true) { ?>
            <p><?php echo $args['info']; ?></p>
        <?php }
	}
}