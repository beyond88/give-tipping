<?php return array(
    'root' => array(
        'name' => 'kaderdev/give-tipping',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => '5de8090b8a1bcf8ab421a545bde89b0105fb96b5',
        'type' => 'project',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'dealerdirect/phpcodesniffer-composer-installer' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '1a457ec7536569197503e104bc4616226844b6ff',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/../dealerdirect/phpcodesniffer-composer-installer',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => true,
        ),
        'kaderdev/give-tipping' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '5de8090b8a1bcf8ab421a545bde89b0105fb96b5',
            'type' => 'project',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'squizlabs/php_codesniffer' => array(
            'pretty_version' => '4.0.x-dev',
            'version' => '4.0.9999999.9999999-dev',
            'reference' => 'cb3ef133034713536df910f269f1ae5fddadc7b4',
            'type' => 'library',
            'install_path' => __DIR__ . '/../squizlabs/php_codesniffer',
            'aliases' => array(),
            'dev_requirement' => true,
        ),
    ),
);
