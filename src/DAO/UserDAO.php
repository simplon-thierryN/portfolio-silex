<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 10/10/2016
 * Time: 11:48
 */
namespace Portfolio\DAO;

use Portfolio\Domain\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserDAO extends DAO implements UserProviderInterface {

    public function findById($userId){
        $req = " select * from user where usr_id=?";
        $result = $this->getDb()->fetchAssoc($req, array($userId));
        if ($result){
            return $this->buildObject($result);
        }
    }

    public function loadUserByUsername($username){
        $req = "select * from user where usr_name=?";
        $result = $this->getDb()->fetchAssoc($req, array($username));
        if($result){
            return $this->buildObject($result);
        }
        else{
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'Portfolio\Domain\User' === $class;
    }


    protected function buildObject($row)
    {
        // TODO: Implement buildObject() method.
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setUsername($row['usr_name']);
        $user->setPassword($row['usr_password']);
        $user->setRole($row['usr_role']);
        return $user;
    }

    //$2y$13$xHIGaByv.e5j6AReiw3KAec14OjhpDAbQhBQHdL/dClxk9m/pwiiO
}