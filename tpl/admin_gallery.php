<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="dynamic_form"> 
    <div id="field_wrap">
        <?php
        if (isset($gallery_data['image_url'])) {
            for ($i = 0; $i < count($gallery_data['image_url']); $i++) {
                ?>

                <div class="field_row">

                    <div class="field_left">
                        <div class="form_field">
                            <input type="hidden"
                                   class="meta_image_url"
                                   name="gallery[image_url][]"
                                   value="<?php esc_html_e($gallery_data['image_url'][$i]); ?>"
                                   />
                        </div>
                    </div>

                    <div class="field_right image_wrap">
                        <img src="<?php esc_html_e($gallery_data['image_url'][$i]); ?>" height="248" width="248" />
                    </div>

                    <div class="field_right">
                        <input class="button" type="button" value="Choose File" onclick="add_image(this)" />
                        <input class="button" type="button" value="Remove" onclick="remove_field(this)" class="remove_portfolio_image"/>
                    </div>

                    <div class="clear" /></div> 
            </div>
        <?php
    } // endif
} // endforeach
?>
    </div>

<div style="display:none" id="master-row">
    <div class="field_row">
        <div class="field_left">
            <div class="form_field">
                <input class="meta_image_url" value="" type="hidden" name="gallery[image_url][]" />
            </div>
        </div>
        <div class="field_right image_wrap">
        </div> 
        <div class="field_right"> 
            <input type="button" class="button" value="Choose File" onclick="add_image(this)" />
            <input class="button" type="button" value="Remove" onclick="remove_field(this)" class="remove_portfolio_image"/> 
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="add_field_row">
    <input class="button" type="button" value="Add Portfolio Image" onclick="add_portfolio_row();" />
</div> 
</div>