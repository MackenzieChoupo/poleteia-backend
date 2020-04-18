<?php

namespace Politeia\CoreBundle\Block\Service;

use Kreatys\CmsBundle\Block\Service\ImageBlockService;

class PImageBlockService extends ImageBlockService {
    
    public function init($front = false) {
        $this->removeSettings(array('text_align', 'background'));
        parent::init($front);
    }

}
