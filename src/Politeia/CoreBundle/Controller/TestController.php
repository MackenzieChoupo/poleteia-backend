<?php
namespace Politeia\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of TestController
 *
 * @author remi
 */
class TestController extends Controller {
    
    public function testIconAction() {
        $icons = array();

//        $content = file_get_contents(__DIR__ . '/../../../../../web/bundles/politeiacore/plugins/font-awesome/css/font-awesome.min.css');
        $content = file_get_contents(__DIR__ . '/../../../../web/bundles/politeiacore/plugins/font-awesome/css/font-awesome.css');
        preg_match_all('/^\.fa-([a-z0-9-]+):before \{/m', $content, $icons);
        
        sort($icons[1]);
        
        $data = array();
        foreach($icons[1] as $icon) {
            $data[$icon] = $icon;
        }
        
        dump($data);
        exit;
        
        return $data;
    }
}
