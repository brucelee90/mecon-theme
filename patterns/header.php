<?php
/**
 * Title: Header
 * Slug: mecon/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Site header with site title and navigation.
 *
 * @package WordPress
 * @subpackage Mecon
 * @since Mecon 1.0
 */

?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|tiny","bottom":"var:preset|spacing|tiny"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center","orientation":"horizontal"}} -->
    <div class="wp-block-group alignwide"
        style="padding-top:var(--wp--preset--spacing--tiny);padding-bottom:var(--wp--preset--spacing--tiny)">
        <!-- wp:site-logo /-->

        <!-- wp:group {"layout":{"type":"constrained","justifyContent":"left"}} -->
        <div class="wp-block-group">
            <!-- wp:navigation {"ref":5,"overlayBackgroundColor":"base","overlayTextColor":"contrast","layout":{"type":"flex","orientation":"horizontal","flexWrap":"nowrap"}} /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"constrained"}} -->
        <div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
            <!-- wp:mecon-blocks/buttons {"text":"\u003ca href=\u0022http://mecon-solutions.local/?page_id=510\u0022 data-type=\u0022page\u0022 data-id=\u0022510\u0022\u003eJetzt Anfrage stellen\u003c/a\u003e\u003ca href=\u0022http://mecon-solutions.local/?page_id=509\u0022 data-type=\u0022page\u0022 data-id=\u0022509\u0022\u003e\u003c/a\u003e"} /-->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->