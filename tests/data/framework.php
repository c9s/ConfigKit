<?php return array (
  'ApplicationName' => 'Phifty',
  'ApplicationID' => 'phifty',
  'ApplicationUUID' => '9fc933c0-70f9-11e1-9095-3c07541dfc0c',
  'Domain' => 'phifty.dev',
  'Mail' => 
  array (
    'NoReply' => '"No Reply" <no-reply@corneltek.com>',
    'admin' => '"Admin" <cornelius.howl@gmail.com>',
  ),
  'Requirement' => 
  array (
    'Extensions' => 
    array (
      0 => 'mbstring',
      1 => 'apc',
    ),
  ),
  'Applications' => 
  array (
    'Core' => NULL,
    'TestApp' => NULL,
  ),
  'View' => 
  array (
    'Backend' => 'twig',
    'Class' => '\\Phifty\\View',
    'TemplateDirs' => 
    array (
    ),
  ),
  'Locale' => 
  array (
    'LocaleDir' => 'locale',
    'Default' => 'zh_TW',
    'Langs' => 
    array (
      0 => 'en',
      1 => 'zh_TW',
    ),
  ),
  'Services' => 
  array (
    'CurrentUserService' => 
    array (
      'Class' => 'Phifty\\Security\\CurrentUser',
      'Model' => 'User\\Model\\User',
    ),
    'ActionService' => 
    array (
    ),
    'RouterService' => 
    array (
    ),
    'MailerService' => 
    array (
      'Transport' => 'MailTransport',
      'Plugins' => 
      array (
        'AntiFloodPlugin' => 
        array (
          'EmailLimit' => 10,
          'PauseSeconds' => 30,
        ),
      ),
    ),
    'CacheService' => 
    array (
    ),
    'SessionService' => 
    array (
    ),
    'LocaleService' => 
    array (
    ),
    'TwigService' => 
    array (
    ),
    'AssetService' => 
    array (
    ),
    'PluginService' => 
    array (
    ),
  ),
  'Plugins' => 
  array (
    'I18N' => NULL,
    'AdminUI' => NULL,
    'CRUD' => 
    array (
    ),
    'User' => 
    array (
      'allow_signup' => 0,
    ),
    'News' => 
    array (
      'with_icon' => 1,
      'with_image' => 1,
      'with_category' => 1,
      'with_external_link' => 1,
      'with_brief' => 1,
      'with_cover_option' => 1,
      'with_event' => 
      array (
        'with_image' => 1,
        'with_brief' => 1,
        'with_external_link' => 1,
      ),
    ),
    'Product' => 
    array (
      'with_category' => 1,
      'with_subcategory' => 1,
      'with_private' => 1,
      'with_price' => 1,
      'with_types' => 1,
      'with_images' => 1,
      'with_features' => 1,
      'with_spec' => 1,
      'with_resources' => 1,
      'with_cover_option' => 1,
      'with_cover_image' => 1,
      'image' => 
      array (
        'size_limit' => 600,
        'resize_width' => 600,
      ),
      'thumb' => 
      array (
        'size_limit' => 600,
        'resize_width' => 300,
      ),
      'upload_dir' => 'static/upload',
    ),
    'ImageData' => 
    array (
    ),
    'DMenu' => NULL,
    'HotelBundle' => NULL,
    'TourBundle' => NULL,
  ),
);