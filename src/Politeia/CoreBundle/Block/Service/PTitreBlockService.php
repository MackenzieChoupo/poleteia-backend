<?php

namespace Politeia\CoreBundle\Block\Service;

use Kreatys\CmsBundle\Block\Service\TitreBlockService;

/**
 * Description of PTitreBlockService
 *
 * @author remi
 */
class PTitreBlockService extends TitreBlockService {

    public function init($front = false) {
        $this->removeSettings(array('background'));
        $this
                ->addContent('titre', 'text', array(
                    'label' => 'Titre',
                    'data' => 'Titre à remplacer'
                ))
        ;
    }

}
