<?php

namespace Kreatys\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KreatysUserBundle extends Bundle
{
    public function getParent() {
        return 'SonataUserBundle';
    }
}
