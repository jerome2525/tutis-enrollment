<?php
class Tutis_Settings {

    public function __construct() {
        $this->register_settings();
    }

    public function register_settings() {
        add_action('admin_menu', array( $this, 'create_menu') );
    }

    public function create_menu() {

        //create new top-level menu
        add_menu_page('Tutis Settings', 'Tutis Settings', 'administrator', 'tutis-settings', array( $this, 'settings_page' ) , plugins_url('/img/tutis-icon.png', __FILE__) );

        //call register settings function
        add_action( 'admin_init', array( $this, 'plugin_settings' ) );
    }

    public function plugin_settings() {
        //register our settings
        register_setting( 'tutis-settings-group', 'tutis_api_url' );
        register_setting( 'tutis-settings-group', 'tutis_api_key' );
        register_setting( 'tutis-settings-group', 'tutis_api_size' );  
        register_setting( 'tutis-settings-group', 'tutis_pagi_count' );  
        register_setting( 'tutis-settings-group', 'tutis_auto_sync' );  
        register_setting( 'tutis-settings-group', 'tutis_course_per_page' );
        register_setting( 'tutis-settings-group', 'tutis_display_state' );
        register_setting( 'tutis-settings-group', 'tutis_pre_label' );
        register_setting( 'tutis-settings-group', 'tutis_vacancies_label' );
        register_setting( 'tutis-settings-group', 'tutis_secure_pay_client_id' );
        register_setting( 'tutis-settings-group', 'tutis_secure_pay_merchant_code' );
        register_setting( 'tutis-settings-group', 'tutis_enrol_student_title' );
        register_setting( 'tutis-settings-group', 'tutis_enrol_student_instruction' );
        register_setting( 'tutis-settings-group', 'tutis_company_title' );
        register_setting( 'tutis-settings-group', 'tutis_company_instruction' );
        register_setting( 'tutis-settings-group', 'tutis_hide_abn' );
        register_setting( 'tutis-settings-group', 'tutis_required_abn' );
        register_setting( 'tutis-settings-group', 'tutis_hide_account' );
        register_setting( 'tutis-settings-group', 'tutis_required_account' );
        register_setting( 'tutis-settings-group', 'tutis_required_post_box' );
        register_setting( 'tutis-settings-group', 'tutis_hide_post_box' );
        register_setting( 'tutis-settings-group', 'tutis_required_building' );
        register_setting( 'tutis-settings-group', 'tutis_hide_building' );
        register_setting( 'tutis-settings-group', 'tutis_required_flat' );
        register_setting( 'tutis-settings-group', 'tutis_hide_flat' );
        register_setting( 'tutis-settings-group', 'tutis_hide_country' );
        register_setting( 'tutis-settings-group', 'tutis_review_enrol_title' );
        register_setting( 'tutis-settings-group', 'tutis_review_enrol_instruction' );
        register_setting( 'tutis-settings-group', 'tutis_css_code' );
        register_setting( 'tutis-settings-group', 'tutis_thank_you_url' );
        register_setting( 'tutis-settings-group', 'tutis_program_label' );        
        register_setting( 'tutis-settings-group', 'tutis_public_course' );
        register_setting( 'tutis-settings-group', 'tutis_sandbox' );
        
    }

    public function sync_form() {
        echo '<form class="sync-form" id="sync-form1" method="POST" action="'. admin_url( "admin-ajax.php" ) . '">
            <input type="hidden" name="action" value="adminwsfilter"> 
            <input type="hidden" name="sync_input" value="1">  
        </form>';
    }

    public function reset_form() {
        echo '<form class="sync-form" id="reset-form1" method="POST" action="'. admin_url( "admin-ajax.php" ) . '">
            <input type="hidden" name="action" value="adminwsfilter">   
            <input type="hidden" name="reset_input" value="1">
        </form>';
    }

    public function update_form() {
        echo '<form class="sync-form" id="update-form1" method="POST" action="'. admin_url( "admin-ajax.php" ) . '">
            <input type="hidden" name="action" value="adminwsfilter">   
            <input type="hidden" name="update_input" value="1">
        </form>';
    }

    public function sync_button( $btnlabel ) {

        $auto_class = 'auto_submit_class';   

        echo '<p class="submit" style="display: none;"><input type="submit" name="submit" id="submit" class="button button-sync button-primary ' . $auto_class . '" value="' . $btnlabel . '" form="sync-form1"></p>';
    }

    public function reset_button( $disabled_btn_class ) {
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary button-reset ' . $disabled_btn_class . '" value="Reload All" form="reset-form1"></p>';
    }

    public function update_button( $disabled_btn_class ) {
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary ' . $disabled_btn_class . ' button-update" value="Update Offerings" form="update-form1"></p>';
    }

    public function selected_dropdown( $field_name, $val ) {
        if( get_option( $field_name ) == $val ) {
            echo 'selected';
        }
    }

    public function pause_button() {
        echo '<p class="submit"><a href="#" class="button button-primary button-pause">Pause <span class="dashicons dashicons-controls-pause"></span></a></p>';
    }

    public function settings_page() {
        //delete_option('tutis_public_course');
        generate_securepay_api_details();
    ?>
        <div class="wrap" id="tutis-tabs">
            <?php echo '<div class="loader"></div>'; ?>
            <?php $this->sync_form(); ?>
            <?php $this->reset_form(); ?>
            <?php $this->update_form(); ?>
            <?php 
            if ( isset( $_GET['settings-updated'] ) ) {
                echo "<div class='updated'><p>Settings updated!.</p></div>";
            } 

            if ( isset( $_GET['sapi'] ) ) {
                echo "<div class='updated'><p>Secure API Details reloaded.</p></div>";
            }
            ?> 
            <div id="adminresult1" class="adminresult"></div> 
           
         <ul>
            <li><a href="#tabs-1">API Settings</a></li>
            <li><a href="#tabs-2">Course Listing Settings</a></li>
            <li><a href="#tabs-3">Enrol Student Form Settings</a></li>
            <li><a href="#tabs-4">Company Form Settings</a></li>
            <li><a href="#tabs-5">Review Enrolment Form Settings</a></li>
            <li><a href="#tabs-6">Payment Settings</a></li>
            <li><a href="#tabs-7">Styling</a></li>
          </ul>
          <div id="tabs-1">

            <h3>API Settings</h3>
            <form method="post" action="options.php" id="option-form1">
                <?php settings_fields( 'tutis-settings-group' ); ?>
                <?php do_settings_sections( 'tutis-settings-group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row">API URL</th>
                    <td><input type="url" name="tutis_api_url" value="<?php echo esc_attr( get_option('tutis_api_url') ); ?>" required/></td>
                    </tr>
                     
                    <tr valign="top">
                    <th scope="row">API KEY</th>
                    <td><input type="text" name="tutis_api_key" value="<?php echo esc_attr( get_option('tutis_api_key') ); ?>" required/></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row">Size (max 300 per part)</th>
                    <td><input type="number" min="1" max="300" name="tutis_api_size" value="<?php 
                    if( get_option('tutis_api_size') ) {
                        echo esc_attr( get_option('tutis_api_size') ); 
                    }
                    else {
                        echo '1500';
                    }
                    ?>" required/>
                    <input type="hidden" name="tutis_pagi_count" value="<?php  echo esc_attr( get_option('tutis_pagi_count') ); ?>">
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row">Auto Sync</th>
                    <td>
                        <select name="tutis_auto_sync">
                            <option value="0" <?php $this->selected_dropdown( 'tutis_auto_sync', 0 ); ?> >No</option>
                            <option value="1" <?php $this->selected_dropdown( 'tutis_auto_sync', 1 ); ?> >Yes</option>
                        </select>
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row">Show Only Public Courses</th>
                    <td>
                        <select name="tutis_public_course">
                            <option value="1" <?php $this->selected_dropdown( 'tutis_public_course', 1 ); ?>>Yes</option>
                            <option value="0" <?php $this->selected_dropdown( 'tutis_public_course', 0 ); ?>>no</option>
                        </select>
                    </td>
                    </tr>

                </table>
                <?php if( get_option('tutis_last_sync') ) { ?>
                    <p>Last Updated: <?php 
                    echo get_option('tutis_last_sync'); 
                    ?></p>
                    <?php if( get_option('tutis_auto_sync') == 1 && get_option('tutis_next_sync') ) { ?>
                    <p>Next Run: <?php 
                    echo get_option('tutis_next_sync');
                    ?></p>
                    <?php } ?> 
                <?php } ?>    
                <?php 
                $disabled_btn_class = '';
                if( get_option('tutis_api_url') && get_option('tutis_api_key') && get_option('tutis_api_size') && get_option('tutis_total_pages') ) {
                    $page_count = get_option('tutis_pagi_count') + 1;
                    if( get_option('tutis_total_pages') >= $page_count ) {
                        echo '<p><strong>Downloading Part ' . $page_count  . ' of ' . get_option('tutis_total_pages') . '...</strong></p>'; 
                        echo '<p>Please dont close this page or navigate to other page while downloading to avoid interruption.</p>';
                        $disabled_btn_class = 'disabled';
                    }
                }?>
                <div class="tutis-button-row">
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary <?php echo $disabled_btn_class; ?>" value="Save Changes"></p>
                    <?php 
                    if( get_option('tutis_api_url') && get_option('tutis_api_key') && get_option('tutis_api_size') && get_option('tutis_total_pages') ) {
                        $page_count = get_option('tutis_pagi_count') + 1;
                        if( get_option('tutis_total_pages') >= $page_count ) {
                            $btnlabel = 'Sync Part ' . $page_count  . ' of ' . get_option('tutis_total_pages');
                            if( get_option( 'tutis_pagi_count' ) > 0 ) {
                                $btnlabel = 'Syncing Part ' . $page_count  . ' of ' . get_option('tutis_total_pages');
                            }
                            $this->sync_button( $btnlabel );  
                        }
                    }
                    $this->update_button( $disabled_btn_class );
                    $this->reset_button( $disabled_btn_class );
                    ?>
                </div>

            </form>
          </div>
          <div id="tabs-2">
            <h3>Course Listing Settings: </h3>
             <table class="form-table">
                <tr valign="top">
                <th scope="row">Course per page</th>
                <td><input type="number" min="1" max="30" name="tutis_course_per_page" value="<?php echo esc_attr( get_option('tutis_course_per_page') ); ?>" placeholder="10" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Display State</th>
                <td>
                    <select name="tutis_display_state" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_display_state', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_display_state', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">Program Label</th>
                <td><input type="text" name="tutis_program_label" value="<?php echo esc_attr( get_option('tutis_program_label') ); ?>" placeholder="Program" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Pre-Requisites Label</th>
                <td><input type="text" name="tutis_pre_label" value="<?php echo esc_attr( get_option('tutis_pre_label') ); ?>" placeholder="Prerequisites" form="option-form1"></td>
                </tr>

                 <tr valign="top">
                <th scope="row">Vacancies Label</th>
                <td><input type="text" name="tutis_vacancies_label" value="<?php echo esc_attr( get_option('tutis_vacancies_label') ); ?>" placeholder="Vacancies" form="option-form1"></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" form="option-form1"></p>
          </div>
          <div id="tabs-3">
            <h3>Enrol Student Form Label: </h3>
             <table class="form-table">
                <tr valign="top">
                <th scope="row">Enrol Students Title</th>
                <td><input type="text" name="tutis_enrol_student_title" value="<?php echo esc_attr( get_option('tutis_enrol_student_title') ); ?>" placeholder="Thankyou for deciding to study with us" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Enrol Students Instruction</th>
                <td>
                    <input type="text" name="tutis_enrol_student_instruction" value="<?php echo esc_attr( get_option('tutis_enrol_student_instruction') ); ?>" placeholder="Lets get you setup, please add details for all students you wish to control" form="option-form1">
                </td>
                </tr>

            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" form="option-form1"></p>
          </div>

          <div id="tabs-4">
            <h3>Company Form Label: </h3>
             <table class="form-table">
                <tr valign="top">
                <th scope="row">Company Details Title</th>
                <td><input type="text" name="tutis_company_title" value="<?php echo esc_attr( get_option('tutis_company_title') ); ?>" placeholder="Your Company Details" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Company Details Instruction</th>
                <td>
                    <input type="text" name="tutis_company_instruction" value="<?php echo esc_attr( get_option('tutis_company_instruction') ); ?>" placeholder="If you enroll multiple students or enrolling on behalf of a business, these details are mandatory. If you are a private student enrolling only yourself, then leave these field blank." form="option-form1">
                </td>
                </tr> 
            </table>
            <h3>Form Display Settings: </h3>
            <table class="form-table select-space">
                <tr valign="top">
                <th scope="row">ABN: </th>
                <td>
                    <label>Hide Field?</label>
                    <select name="tutis_hide_abn" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_hide_abn', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_hide_abn', 0 ); ?>>no</option>
                    </select>
                    <label>Required Field?</label>
                    <select name="tutis_required_abn" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_required_abn', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_required_abn', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr> 

                <tr valign="top">
                <th scope="row">Account Number: </th>
                <td>
                    <label>Hide Field?</label>
                    <select name="tutis_hide_account" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_hide_account', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_hide_account', 0 ); ?>>no</option>
                    </select>
                    <label>Required Field?</label>
                    <select name="tutis_required_account" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_required_account', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_required_account', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr> 

                <tr valign="top">
                <th scope="row">Post Delivery Box: </th>
                <td>
                    <label>Hide Field?</label>
                    <select name="tutis_hide_post_box" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_hide_post_box', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_hide_post_box', 0 ); ?>>no</option>
                    </select>
                    <label>Required Field?</label>
                    <select name="tutis_required_post_box" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_required_post_box', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_required_post_box', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr> 

                <tr valign="top">
                <th scope="row">Building / Property Name: </th>
                <td>
                    <label>Hide Field?</label>
                    <select name="tutis_hide_building" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_hide_building', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_hide_building', 0 ); ?>>no</option>
                    </select>
                    <label>Required Field?</label>
                    <select name="tutis_required_building" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_required_building', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_required_building', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr> 

                <tr valign="top">
                <th scope="row">Flat / Unit Details: </th>
                <td>
                    <label>Hide Field?</label>
                    <select name="tutis_hide_flat" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_hide_flat', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_hide_flat', 0 ); ?>>no</option>
                    </select>
                    <label>Required Field?</label>
                    <select name="tutis_required_flat" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_required_flat', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_required_flat', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr> 

                <tr valign="top">
                <th scope="row">Country: </th>
                <td>
                    <label>Hide Field?</label>
                    <select name="tutis_hide_country" form="option-form1">
                        <option value="1" <?php $this->selected_dropdown( 'tutis_hide_country', 1 ); ?>>Yes</option>
                        <option value="0" <?php $this->selected_dropdown( 'tutis_hide_country', 0 ); ?>>no</option>
                    </select>
                </td>
                </tr> 
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" form="option-form1"></p>
          </div>

          <div id="tabs-5">
            <h3>Review Enrolment Form Label</h3>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Review Enrolment Title</th>
                <td><input type="text" name="tutis_review_enrol_title" value="<?php echo esc_attr( get_option('tutis_review_enrol_title') ); ?>" placeholder="OK, we are nearly there" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Review Enrolment Instruction</th>
                <td>
                    <input type="text" name="tutis_review_enrol_instruction" value="<?php echo esc_attr( get_option('tutis_review_enrol_instruction') ); ?>" placeholder="Please check the details, before going to the payment screen" form="option-form1">
                </td>
                </tr> 
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" form="option-form1"></p>
          </div>

          <div id="tabs-6">
            <h3>Secure Pay API Settings</h3>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Client ID</th>
                <td><input type="text" name="tutis_secure_pay_client_id" value="<?php echo esc_attr( get_option('tutis_secure_pay_client_id') ); ?>" placeholder="" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Merchant Code</th>
                <td><input type="text" name="tutis_secure_pay_merchant_code" value="<?php echo esc_attr( get_option('tutis_secure_pay_merchant_code') ); ?>" placeholder="" form="option-form1"></td>
                </tr>

                <tr valign="top">
                <th scope="row">Sandbox mode</th>
                <td>
                    <select name="tutis_sandbox" form="option-form1">
                        <option value="0" <?php $this->selected_dropdown( 'tutis_sandbox', 0 ); ?> >Yes</option>
                        <option value="1" <?php $this->selected_dropdown( 'tutis_sandbox', 1 ); ?> >No</option>
                    </select>
                </td>
                </tr>

                <tr valign="top">
                <th scope="row">Thank you page URL</th>
                <td><input type="url" name="tutis_thank_you_url" value="<?php if( get_option('tutis_thank_you_url') ) { echo esc_attr( get_option('tutis_thank_you_url') ); } else { echo get_home_url(); } ?>" placeholder="" form="option-form1" ></td>
                </tr>
            </table>
            <input type="submit" name="submit" id="submit" class="button button-primary <?php if(isset( $_GET['sapi'] ) ) { echo 'disabled'; } ?>" value="Save Changes" form="option-form1">
            <a class="button button-primary" href="?page=<?php echo $_GET['page']; ?>&sapi=1&settings-updated=true">Fetch Secure API Details</a>
          </div>

          <div id="tabs-7">
            <h3>Add your custom css here:</h3>
            <textarea name="tutis_css_code" placeholder="Place CSS here, No <style> tag needed" form="option-form1" class="tutis-admin-field-textarea"><?php echo esc_attr( get_option('tutis_css_code') ); ?></textarea>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" form="option-form1"></p>

          </div>
        </div>
    <?php 
    } 
}

if(is_admin()) {
    new Tutis_Settings;
}
?>