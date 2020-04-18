<?php

namespace Politeia\CoreBundle\Block\Service;

use Kreatys\CmsBundle\Block\Service\RawContentBlockService;

class PRawContentBlockService extends RawContentBlockService {

    public function init($front = false) {
        parent::init($front);
        
        $this->removeSettings(array('text_align', 'background'));
    }

}
