<?php
/**
 * Pesapal enrolments plugin settings and presets.
 *
 * @package    enrol_pesapal
 * @copyright 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Settings.
    $settings->add(new admin_setting_heading(
        'enrol_pesapal_settings',
        '',
        get_string('pluginname_desc', 'enrol_pesapal')
    ));
    $settings->add(new admin_setting_configtext(
        'enrol_pesapal/consumer_key',
        get_string('consumer_key', 'enrol_pesapal'),
        get_string('consumer_key_desc', 'enrol_pesapal'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'enrol_pesapal/consumer_secret',
        get_string('consumer_secret', 'enrol_pesapal'),
        get_string('consumer_secret_desc', 'enrol_pesapal'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'enrol_pesapal/notification_id',
        get_string('notification_id', 'enrol_pesapal'),
        get_string('notification_id_desc', 'enrol_pesapal'),
        '',
        PARAM_TEXT
    ));
    
    $settings->add(new admin_setting_configcheckbox(
        'enrol_pesapal/sandbox',
        get_string('sandbox', 'enrol_pesapal'),
        get_string('sandbox_desc', 'enrol_pesapal'),
        1
    ));
    $settings->add(new admin_setting_configcheckbox(
        'enrol_pesapal/mailstudents',
        get_string('mailstudents', 'enrol_pesapal'),
        '',
        0
    ));
    $settings->add(new admin_setting_configcheckbox(
        'enrol_pesapal/mailteachers',
        get_string('mailteachers', 'enrol_pesapal'),
        '',
        0
    ));
    $settings->add(new admin_setting_configcheckbox(
        'enrol_pesapal/mailadmins',
        get_string('mailadmins', 'enrol_pesapal'),
        '',
        0
    ));

    // Note: let's reuse the ext sync constants and strings here, internally it is very similar,
    //       it describes what should happen when users are not supposed to be enrolled any more.
    $options = array(
        ENROL_EXT_REMOVED_KEEP           => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL        => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect(
        'enrol_pesapal/expiredaction',
        get_string('expiredaction', 'enrol_pesapal'),
        get_string('expiredaction_help', 'enrol_pesapal'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES,
        $options
    ));

    // Enrol instance defaults.
    $settings->add(new admin_setting_heading(
        'enrol_pesapal_defaults',
        get_string('enrolinstancedefaults', 'admin'),
        get_string('enrolinstancedefaults_desc', 'admin')
    ));

    $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                     ENROL_INSTANCE_DISABLED => get_string('no'));
    $settings->add(new admin_setting_configselect(
        'enrol_pesapal/status',
        get_string('status', 'enrol_pesapal'),
        get_string('status_desc', 'enrol_pesapal'),
        ENROL_INSTANCE_DISABLED,
        $options
    ));

    $settings->add(new admin_setting_configtext(
        'enrol_pesapal/cost',
        get_string('cost', 'enrol_pesapal'),
        '',
        0,
        PARAM_FLOAT,
        4
    ));

    $currencies = enrol_get_plugin('pesapal')->get_currencies();
    $settings->add(new admin_setting_configselect(
        'enrol_pesapal/currency',
        get_string('currency', 'enrol_pesapal'),
        '',
        'UGX',
        $currencies
    ));

//    $settings->add(new admin_setting_configtext(
//        'enrol_pesapal/maxenrolled',
//        get_string('maxenrolled', 'enrol_pesapal'),
//        get_string('maxenrolled_help', 'enrol_pesapal'),
//        0,
//        PARAM_INT
//    ));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect(
            'enrol_pesapal/roleid',
            get_string('defaultrole', 'enrol_pesapal'),
            get_string('defaultrole_desc', 'enrol_pesapal'),
            $student->id,
            $options
        ));
    }

    $settings->add(new admin_setting_configduration(
        'enrol_pesapal/enrolperiod',
        get_string('enrolperiod', 'enrol_pesapal'),
        get_string('enrolperiod_desc', 'enrol_pesapal'),
        0
    ));
}
