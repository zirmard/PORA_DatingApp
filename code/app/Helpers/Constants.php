<?php
//Backend page titles
define('AWS_CLOUDFRONT_URL', env('AWS_CLOUDFRONT_URL', 'https://dhrio77ug68ii.cloudfront.net/public/'));
define('HOME_PAGE_LABEL', 'Dashboard');
define('CONTENT_PAGE_LABEL','Content pages');
define('CHANGE_PASSWORD_LABEL','Change password');
define('FAQ_LABEL','FAQs');
define('CONTACT_REASON_LABEL','Contact Reasons');
define('REPORT_REASON_LABEL', 'Report Reasons');
define('REPORTED_USERS', 'Reported Users');
define('MANAGE_USER_LABEL', 'Manage Users');
define('VERSION_LABEL', 'Update Version');
define('SEND_NOTIFICATION','Notification');

// OTP Expiration Time
define('OTP_EXPIRE_AT','+5 minutes');

# AWS Bucket Folders

define('AWS_USER_PROFILE_IMAGE', 'user_profile_image/');
define('AWS_USER_VERIFICATION_VIDEO', 'user_verfication_video/');

define('AWS_LOVE_LANGUAGE_ICONS', 'love_language_icons/');
define('AWS_FUN_INTEREST_ICONS', 'fun_interest_icons/');

define('AWS_USER_SELFIE_IMAGE', 'user_selfie_images/');

define('GENDER_ARRAY',array('','Male','Female'));
define('YES_NO',array('','Yes','No'));
define('EDUCATION_ARRAY',array('','high School Diploma','Bachelors Degree','Masters Degree','PHD'));
define('CHOICE_ARRAY',array('','Yes','No','i am indifferent'));
define('ADDICTION_ARRAY',array('','Never','Occasionally','Regular'));
define('STATUS',array('Inactive','Active'));
define('ADMIN_APPROVE_STATUS',array('Unapproved','Approved'));


