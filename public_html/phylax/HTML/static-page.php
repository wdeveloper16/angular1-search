<?php

namespace Phylax\SourceMoz;

$this->print_page_begin();
$this->print_head();
$this->print_body_begin();

?>
        <div id="page_content">
            <div id="content">
                <div id="static_place"><?= $this->setup['body'];?></div><!-- #web_place -->
            </div><!-- #content -->
                <div id="upline">
                    <a id="logo_link" href="<?= opt('logo_link');?>"><h1 id="logo"><span><?= opt('logo_title');?></span></h1></a><!-- #logo -->
                    <form id="search" method="get">
                        <input id="q" name="q" type="text" value="<?= $_SESSION['SearchTerm']['Clear'];?>" maxlength="160" data-autofocus="true">
                        <input id="qs" name="" type="submit" value="" title="Search with SourceMoz">
<?= $_SESSION['CurrentInputHidden']; ?>                    </form><!-- #search -->
                    <div class="c"></div>
                </div><!-- #upline -->
        		<div id="copyright">
                    <span id="mfCopy"><?= opt('serp_copyright');?></span><span id="mfLinks"><?php
                        $l = opt('serp_footer_links');
                        if ( strpos( $l, '@Execute:feedback;' ) !== false ) {
                            $l = str_replace( '@Execute:feedback;', '<a href="mailto:' . opt('share_feedback_recipient') . '?subject=' . rawurlencode( opt('share_feedback_subject') ) . '&body=' . rawurlencode( opt('share_feedback_body') ) . '">' . opt('share_serp_feedback_label') . '</a>', $l );
                        }
                        echo $l;
                ?></span>
            </div><!-- #copyright -->
        </div><!-- #page_content -->
<?php

$this->print_body_end();

# EOF