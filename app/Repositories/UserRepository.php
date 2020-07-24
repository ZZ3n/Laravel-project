<?php

namespace App\Repositories;

use App\User;

interface UserRepository
{
/*
* Create New User to Data Source
* @param New user ORM instance
* @return User Model or false
*/
public function store(User $user);

/*
 *  Find User from Data Source
     * @param user info
     * @return user Model or False
 */

public function findByEmail(string $email);
public function findByUsername(string $username);
public function findById(int $id);

/*
* Update user from Data source
* @param user info ( exist id , New email )
* @return updated user Model or False
*/
public function updateEmail(int $id, string $email);
public function updateUsername(int $id, string $username);
public function updatePassword(int $id, string $password);
public function updateName(int $id, string $name);
/*
 *  Check user's password
 * @param user id, string user password
 * @return true or false
 */
public function checkPassword(int $id, string $password);
}