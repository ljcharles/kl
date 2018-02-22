<?php

namespace KL\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KLUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
