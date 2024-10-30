<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="bt-portfolio-wrapper" >
    <div id='div-portfolio-nav'>
        <ul id='bt-portfolio-nav'>
        </ul>
    </div>
    <div class="bt-portfolio-list-wrapper">
        <ul id ='bt-portfolio-list'>
        </ul>       
    </div>
    <div id="bt-overlay-inabox" class="bt-overlay">
        <div class="bt-toolbar"><a class="close" href="#"><img src="<?php echo BAYATREE_PORTFOLIO_DIR; ?>/img/close-lightbox.png"/></a></div>
        <div class="bt-over-wrapper">
            <div class ="bt_overlay_image">
                <div class="clearfixBT margin-auto" id="bt_overlay_container" style="max-width:500px;">
                </div>
            </div>
            <div class ="bt_overlay_description">
                <h2 class="bt_portfolio_title"></h2>
                <p class ="bt_description_text"></p>
                <div class="bt_overlay_projects"></div>
            </div>
            

            <div class='bt_previous_div'>
                <a href="javascript:void(0)" class="bt_previous"><img src="<?php echo BAYATREE_PORTFOLIO_DIR; ?>/img/prev.png"/></a>
            </div>
            <div class='bt_next_div'>
                <a href="javascript:void(0)" class="bt_next"><img src="<?php echo BAYATREE_PORTFOLIO_DIR; ?>/img/next.png"/></a>
            </div>
        </div>
    </div>
</div> 
<script>
    var BT_PORTFOLIO_DIR = '<?php echo BAYATREE_PORTFOLIO_DIR ?>';
    var portfolioJson = <?php echo $portfolioJson ?>;
    var hideProductFilter = '<?php echo get_option('bt-portfolio-settings-show-hide-filter'); ?>';
    var hideAutoSlider = '<?php  echo get_option('bt-portfolio-settings-on-off-auto-slider') ? false:true ?>';
    var autoSliderSpeed = <?php echo get_option('bt-portfolio-settings-speed-auto-slider') ? get_option('bt-portfolio-settings-speed-auto-slider'):1000; ?>;
    var pauseTimingSlider = '<?php echo get_option('bt-portfolio-settings-pause-timing-slider') ? get_option('bt-portfolio-settings-pause-timing-slider'):2000 ?>';

</script>