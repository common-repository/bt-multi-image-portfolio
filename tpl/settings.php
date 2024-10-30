<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
<h2>BT Portfolio Setting</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'portfolio-plugin-css-settings-group' ); ?>
    <?php do_settings_sections( 'portfolio-plugin-css-settings-group' ); ?>
    <?php 
        $themeColor = "#fa7956";
        $fontColor = "#fff";
        $menuFontColor = "#000";
        $menuHoverColor = "#795548";
        $themeColorAttr = esc_attr( get_option('portfolio-plugin-css-theme-color'));
        if(!empty($themeColorAttr)){
            $themeColor = esc_attr( get_option('portfolio-plugin-css-theme-color'));
        }
        
        $fontColorAttr = esc_attr( get_option('portfolio-plugin-css-font-color'));
        if(!empty($fontColorAttr)){
            $fontColor = esc_attr( get_option('portfolio-plugin-css-font-color'));
        }
        $menuFontColorAttr = esc_attr(get_option('portfolio-plugin-css-menu-color'));
        if(!empty($menuFontColorAttr)){
            $menuFontColor = esc_attr( get_option('portfolio-plugin-css-menu-color'));
        }
        
        $menuHoverColorAttr = esc_attr(get_option('portfolio-plugin-css-menu-hover-color'));
        if(!empty($menuHoverColorAttr)){
            $menuHoverColor = esc_attr( get_option('portfolio-plugin-css-menu-hover-color'));
        }
        
        $sliderSpeed = intval(get_option('bt-portfolio-settings-speed-auto-slider'));
        $pauseTiming = intval(get_option('bt-portfolio-settings-pause-timing-slider'));            

    ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Theme Color</th>
        <td><input class="bt-color-picker" data-default-color="#fa7956" name="portfolio-plugin-css-theme-color" value="<?php echo $themeColor; ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Font Color</th>
        <td><input class="bt-color-picker" data-default-color="#fff" name="portfolio-plugin-css-font-color" value="<?php echo $fontColor; ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Menu Font Color</th>
        <td><input class="bt-color-picker" data-default-color="#000" name="portfolio-plugin-css-menu-color" value="<?php echo $menuFontColor; ?>" /></td>
        </tr>
         <tr valign="top">
        <th scope="row">Menu hover Color</th>
        <td><input class="bt-color-picker" data-default-color="#795548" name="portfolio-plugin-css-menu-hover-color" value="<?php echo $menuHoverColor; ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Hide Portfolio Categories Filter</th>
        <td>
            <input name="bt-portfolio-settings-show-hide-filter"  type='checkbox' value="1" <?php checked('1', get_option('bt-portfolio-settings-show-hide-filter')); ?> />
        </td>
        
        </tr>
        <tr valign="top">
        <th scope="row">Disable Image Carousel Auto Play</th>
        <td>
            <input name="bt-portfolio-settings-on-off-auto-slider"  type='checkbox' value="1" <?php checked('1', get_option('bt-portfolio-settings-on-off-auto-slider')); ?> />
        </td>       
        </tr>
        

        
        <tr valign="top">
        <th scope="row">Slider Speed(milliseconds)</th>
        <td>
               <input name="bt-portfolio-settings-speed-auto-slider"  type='number' value="<?php echo $sliderSpeed ? $sliderSpeed:1000; ?>"/>
        </td>       
        </tr>
        
        <tr valign="top">
        <th scope="row">Slider Pause Timing(milliseconds)</th>
        <td>
            <input name="bt-portfolio-settings-pause-timing-slider"  type='number' value="<?php echo $pauseTiming ? $pauseTiming:2000; ?>"/>
        </td>       
        </tr>
        
        
        
    </table>    
    <?php submit_button(); ?>
</form>
</div>