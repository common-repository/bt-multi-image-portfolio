 <?php 
 if ( ! defined( 'ABSPATH' ) ) exit;
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
        $menuFontColorAttr = esc_attr( get_option('portfolio-plugin-css-menu-color'));
        if(!empty($menuFontColorAttr)){
            $menuFontColor = esc_attr( get_option('portfolio-plugin-css-menu-color'));
        }
        $menuHoverColorAttr = esc_attr( get_option('portfolio-plugin-css-menu-hover-color'));
        if(!empty($menuHoverColorAttr)){
            $menuHoverColor = esc_attr( get_option('portfolio-plugin-css-menu-hover-color'));
        }
    
    ?>
<style>
                    .li-portfolio-left .portfolio-thumb:hover .project-title, #bt-portfolio-nav li a.active
               {
                    background: <?php echo $themeColor; ?> !important;
                }
                #bt-portfolio-nav li a.active , .li-portfolio-left .portfolio-thumb:hover .project-title{
                    color: <?php echo $fontColor; ?> !important;
                }
                #bt-portfolio-nav li a{
                    color: <?php echo $menuFontColor; ?> !important;
                }
                #bt-portfolio-nav li a:hover:not(.active), #bt-portfolio-nav li a.active{
                    border: 1px solid <?php echo $themeColor; ?> !important;
                }
                #bt-portfolio-nav li a:hover{
                 color: <?php  echo $menuHoverColor; ?> !important;
                }
</style>