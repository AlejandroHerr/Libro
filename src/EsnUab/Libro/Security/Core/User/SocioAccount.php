<?php

namespace EsnUab\Libro\Security\Core\User;

use EsnUab\Libro\Security\Core\User\Base\SocioAccount as BaseSocioAccount;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Skeleton subclass for representing a row from the 'socio_account' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class SocioAccount extends BaseSocioAccount implements AdvancedUserInterface
{
    const SALT_LENGTH = '255';

    public function generateSale()
    {
        $salt = base64_encode(mcrypt_create_iv(floor(255 * 0.75), MCRYPT_DEV_URANDOM));
        echo $salt;
        $salt = substr($salt, 0, 255);
        echo '<br>';
        echo $salt;
        echo '<br>';
        echo strlen($salt);
    }
    /** UserInterface Methods **/
    public function getRoles()
    {
        return ['ROLE_ERASMUS'];
    }
    public function getUsername()
    {
        return $this->getEsncard();
    }
    public function eraseCredentials()
    {
    }

    /** AdvancedUserInterface Methods **/
    public function isAccountNonExpired()
    {
        return $this->getActivo() && !$this->getRemoved();
    }
    public function isAccountNonLocked()
    {
        return $this->getActivo() && !$this->getRemoved();
    }
    public function isCredentialsNonExpired()
    {
        return true;
    }
    public function isEnabled()
    {
        return $this->getActivo() && !$this->getRemoved();
    }
}
