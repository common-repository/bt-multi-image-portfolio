<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="dynamic_form"> 
    <div id="project_url_meta_box_field">
        <?php
        if (!empty($project_data)) {
            //Display existing project url
            foreach ($project_data as $project) {
        ?>
        <div class="field_row">
                <div class="field_left">
                    <div class='project_label'>Project URL</div>
                    <div class="form_field">
                        <input value="<?php echo esc_html_e($project['project_url'])?>" type="text" name="project[project_url][]" />
                    </div>
                </div>
                <div class="clear"></div>
                <div class="field_left">
                    <div class='project_label'> Image URL(Favicon or thumbnail) </div>
                    <div class="form_field">
                        <input class="meta_image_url"  value="<?php echo esc_html_e($project['image_url'])?>" type="text" name="project[image_url][]" />
                    </div>
                </div>
                <div class="clear"></div>
                <div class="field_left project_bt">
                    <input type="button" class="button" value="Choose File" onclick="add_image(this)" />
                    <input class="button" type="button" value="Remove" onclick="remove_field(this)" class="remove_portfolio_image"/> 
                </div>
                <div class="clear"></div>
            </div>
            <?php
        } // endif
    } // endforeach
    ?>
    </div>
<!-- UI for add more project url-->
<div style="display:none" id="project_url_box">
    <div class="field_row">
        <div class="field_left">
            <div class='project_label'>Project URL</div>
            <div class="form_field">
                <input value="" type="text" name="project[project_url][]" />
            </div>
        </div>
        <div class="clear"></div>
        <div class="field_left">
            <div class='project_label'> Image URL(Favicon or thumbnail) </div>
            <div class="form_field">
                <input class="meta_image_url"  value="" type="text" name="project[image_url][]" />
            </div>
        </div>
        <div class="clear"></div>
        <div class="field_left project_bt">
            <input type="button" class="button" value="Choose File" onclick="add_image(this)" />
            <input class="button" type="button" value="Remove" onclick="remove_field(this)" class="remove_portfolio_image"/> 
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="add_field_row">
    <input class="button" type="button" value="Add Project" onclick="add_project_row();" />
</div> 
</div>