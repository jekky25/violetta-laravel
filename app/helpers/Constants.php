<?php
//Пол
define('WOMEN', 2);
define('MEN', 1);

//Режимы
define('BIG', 2);
define('VIP', 1);
define('NOT_VIP', 0);

//Ориентация
define('GOMOSEXUAL', 1);
define('GETEROSEXUAL', 2);
define('BISEXUAL', 3);

// Коды ошибок
define('GENERAL_MESSAGE', 200);
define('CONFIRM_MESSAGE', 201);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);


//возраст, рост, вес
define('AGE_MIN', 15);
define('AGE_MAX', 15);
define('PARTNER_AGE_MIN', 15);
define('PARTNER_AGE_MAX', 15);
define('HEIGHT_MIN', 149);
define('HEIGHT_MAX', 149);
define('PARTNER_HEIGHT_MIN', 149);
define('PARTNER_HEIGHT_MAX', 149);
define('WEIGHT_MIN', 29);
define('WEIGHT_MAX', 29);
define('PARTNER_WEIGHT_MIN', 29);
define('PARTNER_WEIGHT_MAX', 29);

//Сообщения
define('PRIV_NEW_MESSAGE', 1);
define('PRIV_NOT_NEW_MESSAGE', 0);

//имена классов
define('BODY_CLASS', 'Body');
define('HAIR_COLOR_CLASS', 'HairColor');
define('HAIR_TYPE_CLASS', 'HairType');
define('EYES_CLASS', 'Eyes');
define('SEX_ORIENT_CLASS', 'SexOrient');
define('MEET_TARGET_CLASS', 'MeetTarget');
define('EDUCATION_CLASS', 'Education');
define('SMOKE_CLASS', 'Smoke');
define('SPIRT_CLASS', 'Spirt');
define('FAMILY_STATUS_CLASS', 'FamilyStatus');
define('CHILDREN_CLASS', 'Children');
define('HELP_MONEY_CLASS', 'HelpMoney');
define('INTEREST_CLASS', 'Interest');
define('SPEAK_LANG_CLASS', 'SpeakLang');


define ('SITE_URL', $pathrelative);

define('DIR_ROOT', str_replace("\\","/",realpath(dirname(__FILE__))));

//google re-capcha keys
define("RE_SITE_KEY","6LdlLG4kAAAAAA0-93MpsTTjLwjX4ExEMT-ShXtW");
define("RE_SEC_KEY","6LdlLG4kAAAAAGLB87ixCoNC7BABLbdAwWZHw_-n");


define('DATE_DAY',      '4');
define('DATE_MONTH',    '3');
define('DATE_YEAR',     '2');

define('COUNTRY_ID_RUSSIA',     '141');