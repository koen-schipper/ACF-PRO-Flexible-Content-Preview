<?php
defined('ABSPATH') || exit;

function fcp_register_settings() {
    add_option( 'fcp_option_name', 'Flexible Content Preview Settings.');
    register_setting( 'fcp_options_group', 'fcp_option_name', 'fcp_callback' );
}
add_action( 'admin_init', 'fcp_register_settings' );

function fcp_register_options_page() {
    add_options_page('Flexible Content Preview Settings', 'Flexible Content Preview', 'manage_options', 'fcp', 'fcp_options_page');
}
add_action('admin_menu', 'fcp_register_options_page');

function fcp_options_page()
{
    $flexible_content_fields = retrieve_flexible_keys();
    ?>
    <div class="wrap">
        <h2>Flexible Content Preview Settings</h2>

        <?php settings_fields( 'fcp_options_group' ); ?>
        <h3>Screenshot upload</h3>
        <p>Upload a screenshot for every flexible content field. This will be used to show on hover.</p>
        <table>
            <?php foreach ($flexible_content_fields as $name => $label) { ?>
                <tr>
                    <th scope="row" style='text-align: left; min-width: 160px;'><label for="<?= $name ?>_name"><?= $label ?></label></th>
                    <td style="padding-bottom: 20px;">
                        <form action="" method="post" enctype="multipart/form-data">
                            Select screenshot to upload: <br/>
                            <input type="file" name="fileToUpload" id="fileToUpload"> <br/>
                            <input type="submit" value="Upload Image" name="submittheform_<?= $name ?>">
                        </form>
                        <?php
                        global $wp_filesystem;
                        WP_Filesystem();

                        $content_directory = $wp_filesystem->wp_content_dir() . 'plugins/acf-flexible-content-preview/uploads/';
                        $wp_filesystem->mkdir( $content_directory . 'screenshots' );

                        $target_dir_location = $content_directory . 'screenshots/';

                        $formname = "submittheform_".$name;

                        if(isset($_POST[$formname]) && isset($_FILES['fileToUpload'])) {
                            $name_file = $name.".jpg";
                            $tmp_name = $_FILES['fileToUpload']['tmp_name'];

                            if (move_uploaded_file($tmp_name, $target_dir_location.$name_file)) {
                                echo "File was successfully uploaded";
                            } else {
                                echo "The file was not uploaded";
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}


function retrieve_flexible_keys() {
    $keys   = [];
    $groups = acf_get_field_groups();
    if ( empty( $groups ) ) {
        return $keys;
    }

    foreach ( $groups as $group ) {
        $fields = (array) acf_get_fields( $group );
        if ( ! empty( $fields ) ) {
            retrieve_flexible_keys_from_fields( $fields, $keys );
        }
    }

    return $keys;
}

function retrieve_flexible_keys_from_fields( $fields, &$keys ) {
    foreach ( $fields as $field ) {
        if ( 'flexible_content' === $field['type'] ) {
            foreach ( $field['layouts'] as $layout_field ) {
                if ( ! empty( $keys[ $layout_field['key'] ] ) ) {
                    continue;
                }

                $keys[ $layout_field['name'] ] = $layout_field['label'];

                if ( ! empty( $layout_field['sub_fields'] ) ) {
                    retrieve_flexible_keys_from_fields( $layout_field['sub_fields'], $keys );
                }
            }
        }
    }
}