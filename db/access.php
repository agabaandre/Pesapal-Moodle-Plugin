<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(

  'enrol/pesapal:config' => array(
    'captype' => 'write',
    'contextlevel' => CONTEXT_COURSE,
    'archetypes' => array(
      'manager' => CAP_ALLOW,
    )
  ),
  'enrol/pesapal:manage' => array(
    'captype' => 'write',
    'contextlevel' => CONTEXT_COURSE,
    'archetypes' => array(
      'manager' => CAP_ALLOW,
      'editingteacher' => CAP_ALLOW,
    )
  ),
  'enrol/pesapal:unenrol' => array(
    'captype' => 'write',
    'contextlevel' => CONTEXT_COURSE,
    'archetypes' => array(
      'manager' => CAP_ALLOW,
    )
  ),
  'enrol/pesapal:unenrolself' => array(
    'captype' => 'write',
    'contextlevel' => CONTEXT_COURSE,
    'archetypes' => array(
    )
  ),
);
