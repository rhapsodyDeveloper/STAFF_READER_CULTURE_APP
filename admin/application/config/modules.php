<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | List of all modules
  |--------------------------------------------------------------------------
  |
  | List of all modules in array to loaded in front.
  |
 */
$config['modules_list'] = array(
    'dashboard',
    'users',
    'authors',
    'orders',
    'subscriptions',
    'plans',
    'audioplans',
    'comboplans',
    'plantype',
    'mobiappstore',
    'prayerrequests',
    'advertisement',
    'contactus',
    'links',
    'activationcode',
    'reports',
    'sales',
    'news',
    'slider',
    'quiz',
    'quizquestion',
    'feedback',
    'guesttestimonials',
    'pushnotification',
//    'pins',
);

/*
  |--------------------------------------------------------------------------
  | List of all modules icons which you need to display on menu
  |--------------------------------------------------------------------------
  |
  | modulename[classname]
  |
 */
$config['modules_menu_icon'] = array(
    'dashboard' => 'icon-home',
    'users' => 'icon-user',
    'authors' => 'icon-pencil',
    'orders' => 'icon-book',
    'subscriptions' => 'icon-envelope',
    'plans' => 'icon-plan',
    'audioplans' => 'icon-plan',
    'comboplans' => 'icon-plan',
    'plantype' => 'icon-plantype',
    'mobiappstore' => 'icon-shopping-cart',
    'prayerrequests' => 'icon-bullhorn',
    'advertisement' => 'icon-film',
    'contactus' => 'icon-list-alt',
    'links' => 'icon-globe',
    'activationcode' => 'icon-tags',
    'reports' => 'icon-file',
    'sales' => 'icon-briefcase',
    'news' => 'icon-facetime-video',
    'slider' => 'icon-film',
    'quiz' => 'icon-question-sign',
    'feedback' => 'icon-list-alt',
    'guesttestimonials' => 'icon-list-alt',
    'pushnotification' => 'icon-list-alt',
//    'pins' => 'icon-tags',
);


/* End of file modules.php */
/* Location: ./application/config/modules.php */
