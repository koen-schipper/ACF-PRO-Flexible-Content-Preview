<?php
defined('ABSPATH') || exit;

function fcp_hover() {
    $siteURL = get_site_url();
    ?>
    <style>
        .imagePreview { position:absolute; right:100%; bottom:0px; z-index:999999; border:1px solid #f2f2f2; box-shadow:0px 0px 3px #b6b6b6; background-color:#fff; padding:20px;}
        .imagePreview img { width:500px; height:auto; display:block; }
        .acf-tooltip li:hover { background-color:#0074a9; }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('a[data-name=add-layout]').click(function(){
                waitForEl('.acf-tooltip li', function() {
                    $('.acf-tooltip li a').hover(function(){
                        imageTP = $(this).attr('data-layout');
                        $('.acf-tooltip').append('<div class="imagePreview"><img src="<?php echo $siteURL; ?>/wp-content/plugins/acf-flexible-content-preview/uploads/screenshots/' + imageTP + '.jpg"></div>');
                    }, function(){
                        $('.imagePreview').remove();
                    });
                });
            })
            var waitForEl = function(selector, callback) {
                if (jQuery(selector).length) {
                    callback();
                } else {
                    setTimeout(function() {
                        waitForEl(selector, callback);
                    }, 100);
                }
            };
        })
    </script>
    <?php
}
add_action('acf/input/admin_head', 'fcp_hover');