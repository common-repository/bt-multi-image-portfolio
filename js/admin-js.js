jQuery(document).ready(function ($) {
    $('.bt-color-picker').wpColorPicker();
});
/*
 * Function used to add portfolio image
 * Used wordpress default functionality to upload image.
 */
function add_image(obj) {
    var parent = jQuery(obj).parent().parent('div.field_row');
    var inputField = jQuery(parent).find("input.meta_image_url");
    tb_show('', 'media-upload.php?TB_iframe=true');
    window.send_to_editor = function (html) {
        var src_arr = html.match(/src="(.*?)"/);
        var img_src = '';
        if (src_arr && src_arr.length) {
            img_src = src_arr[1];
        }
        inputField.val(img_src);
        jQuery(parent)
                .find("div.image_wrap")
                .html('<img src="' + img_src + '" height="248" width="248" />');
        tb_remove();
    };
    return false;
}
/*
 * Function used to remove portfolio image or project url
 */
function remove_field(obj) {
    var parent = jQuery(obj).parent().parent();
    parent.remove();
}

function add_portfolio_row() {
    var row = jQuery('#master-row').html();
    jQuery(row).appendTo('#field_wrap');
}

function add_project_row() {
    var row = jQuery('#project_url_box').html();
    jQuery(row).appendTo('#project_url_meta_box_field');
}