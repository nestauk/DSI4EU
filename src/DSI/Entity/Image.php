<?php

namespace DSI\Entity;

class Image
{
    const TEMP_FOLDER = __DIR__ . '/../../../www/images/tmp/';
    const TEMP_FOLDER_URL = SITE_RELATIVE_PATH . '/images/tmp/';

    const PROFILE_PIC = __DIR__ . '/../../../www/images/users/profile/';
    const PROFILE_PIC_URL = SITE_RELATIVE_PATH . '/images/users/profile/';

    const PROJECT_LOGO = __DIR__ . '/../../../www/images/projects/logo/';
    const PROJECT_LOGO_URL = SITE_RELATIVE_PATH . '/images/projects/logo/';

    const STORY_FEATURED_IMAGE = __DIR__ . '/../../../www/images/stories/feat/';
    const STORY_FEATURED_IMAGE_URL = SITE_RELATIVE_PATH . '/images/stories/feat/';

    const STORY_MAIN_IMAGE = __DIR__ . '/../../../www/images/stories/main/';
    const STORY_MAIN_IMAGE_URL = SITE_RELATIVE_PATH . '/images/stories/main/';
}