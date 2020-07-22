<?php

namespace App;

interface UserRepositoryInterface
{
/*
* Create New User to Data Source
* @param New user ORM instance
* @return New user ORM instance or failure
*/
public function registerUser(User $user);

public function findUserByEmail(string $email);
public function findUserByUsername(string $username);
public function findUserById(int $id);

/*
* Update user from Data source
* @param user info
* @return updated user info or False
*/
public function updateUserEmail(int $id, string $email);
public function updateUserUsername(int $id, string $username);
public function updateUserPassword(int $id, string $password);
public function updateUserName(int $id, string $name);

public function checkUserPassword(int $id, string $password);
}