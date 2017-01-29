<?php

namespace Phylax\SourceMoz;

$this->print_page_begin();
$this->print_head();
$this->print_body_begin();

$obr = new FrontImages($this->db);
if (is_null($obr->d)) {
	die('Internal error #020002');
}

?>
	<div id="page_content">

		<div class="row" id="front-row">
			<div class="col-xs-10 col-sm-10 col-md-6 col-lg-6 col-centered">
				<div id="front-content">
					<div class="row">
						<img src="<?= $obr->d['logo_src']; ?>" id="logo" alt="<?= opt('image_header_text'); ?>"
						     title="<?= opt('image_header_text'); ?>">
						<img src="assets/img/sl.png" id="logo-front-page" alt="<?= opt('image_header_text'); ?>"
						     title="<?= opt('image_header_text'); ?>">
					</div>
					<div class="row">
						<form id="front_query" method="get" class="ui-widget">

							<div id="front-search">
								<span class="fa fa-search fa-flip-horizontal"></span>
								<input id="q" name="q" type="text" data-autofocus="true">
								<input id="search" name="" type="submit" value="">
							</div>
						</form>
						<!-- #front_query -->
					</div>
					<div class="row">
						<div id="front_slogan"><?= opt('front_slogan'); ?></div>
						<!-- #front_slogan -->
					</div>
					<div class="row">
						<div id="front_copyright"><?= opt('front_copyright'); ?></div>
						<!-- #front_copyright -->
					</div>
					<div class="row">
						<div id="front_buttons">
							<ul id="buttons">
								<?php if (opt('share_on_front')) : ?>
									<?php if (opt('share_show_facebook') || opt('share_show_twitter')) : ?>
										<li class="button"><a href="#" class="drop_button" id="button_social"></a></li>
									<?php endif; ?>
									<?php if (opt('share_show_email')) : ?>
										<li class="button"><a href="#" class="drop_button" id="button_email"></a></li>
									<?php endif; ?>
								<?php endif; ?>
								<li class="button"><a href="#" class="drop_button" id="button_about"></a></li>
							</ul>
							<!-- #buttons -->

							<div id="drop_content">
								<?php if (opt('share_on_front')) : ?>
									<div id="button_social_content" class="drop_place">
										<?php if (opt('share_show_facebook')) : ?>
											<a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode(URL_HOME); ?>"
											   id="share_facebook"
											   onclick="window.open(this.href,'targetFacebookShare','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=500,height=300');return false;"><?= opt('share_facebook_label'); ?></a>
										<?php endif; ?>
										<?php if (opt('share_show_twitter')) : ?>
											<a href="http://twitter.com/share?text=<?= rawurlencode(opt('share_twitter_message')); ?>&url=<?= rawurlencode(URL_HOME); ?>"
											   id="share_twitter"
											   onclick="window.open(this.href,'targetTwitterShare','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=450,height=400');return false;"><?= opt('share_twitter_label'); ?></a>
										<?php endif; ?>
									</div>
									<?php if (opt('share_show_email')) : ?>
										<div id="button_email_content" class="drop_place">
											<a href="mailto:?subject=<?= rawurlencode(opt('share_email_subject')); ?>&body=<?= rawurlencode(opt('share_email_body')); ?>"
											   id="share_email"><?= opt('share_email_label'); ?></a>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<div id="button_about_content" class="drop_place">
									<?= opt('front_button_about'); ?>
								</div>
							</div>
							<!-- #drop_content -->
						</div>
						<!-- #front_buttons -->
					</div>
				</div>
			</div>
		</div>

		<?php
		/*
		<div id="front_image">
			<div id="front_content">
				<div id="front_logo">
					<img src="<?= $obr->d['logo_src'];?>" id="logo" alt="<?= opt('image_header_text');?>" title="<?= opt('image_header_text');?>">
					<img src="assets/img/sl.png" id="logo-front-page" alt="<?= opt('image_header_text');?>" title="<?= opt('image_header_text');?>">
				</div><!-- #front_logo -->

				<form id="front_query" method="get" class="ui-widget">
					<input id="q" name="q" type="text" data-autofocus="true">
					<input id="search" name="" type="submit" value="">
				</form><!-- #front_query -->
				<div id="front_slogan"><?= opt('front_slogan');?></div><!-- #front_slogan -->
				<div id="front_copyright"><?= opt('front_copyright');?></div><!-- #front_copyright -->
				<div id="front_buttons">
					<ul id="buttons">
<?php   if ( opt('share_on_front') ) : ?>
<?php       if ( opt('share_show_facebook') || opt('share_show_twitter') ) : ?>
						<li class="button"><a href="#" class="drop_button" id="button_social"></a></li>
<?php       endif; ?>
<?php       if ( opt('share_show_email') ) : ?>
						<li class="button"><a href="#" class="drop_button" id="button_email"></a></li>
<?php       endif; ?>
<?php   endif; ?>
						<li class="button"><a href="#" class="drop_button" id="button_about"></a></li>
					</ul><!-- #buttons -->
					<div id="drop_content">
<?php   if ( opt('share_on_front') ) : ?>
						<div id="button_social_content" class="drop_place">
<?php       if ( opt('share_show_facebook') ) : ?>
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode( URL_HOME ); ?>" id="share_facebook" onclick="window.open(this.href,'targetFacebookShare','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=500,height=300');return false;"><?= opt('share_facebook_label'); ?></a>
<?php       endif; ?>
<?php       if ( opt('share_show_twitter') ) : ?>
							<a href="http://twitter.com/share?text=<?= rawurlencode( opt('share_twitter_message') ); ?>&url=<?= rawurlencode( URL_HOME ); ?>" id="share_twitter" onclick="window.open(this.href,'targetTwitterShare','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=450,height=400');return false;"><?= opt( 'share_twitter_label' ); ?></a>
<?php       endif; ?>
						</div>
<?php       if ( opt('share_show_email') ) : ?>
						<div id="button_email_content" class="drop_place">
							<a href="mailto:?subject=<?= rawurlencode( opt('share_email_subject') );?>&body=<?= rawurlencode( opt('share_email_body') ); ?>" id="share_email"><?= opt('share_email_label'); ?></a>
						</div>
<?php       endif; ?>
<?php   endif; ?>
						<div id="button_about_content" class="drop_place">
							<?= opt('front_button_about'); ?>

						</div>
					</div><!-- #drop_content -->
				</div><!-- #front_buttons -->
			</div><!-- #front_content -->
		</div><!-- #front_image -->
		*/ ?>

	</div><!-- #page_content -->


<?php

$this->print_body_end();

# EOF