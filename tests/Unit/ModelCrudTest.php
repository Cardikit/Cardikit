<?php

use App\Models\Card;
use App\Models\User;

test('card create only writes fillable values', function () {
    $expectedParams = [
        'name'    => 'Test Card',
        'user_id' => 5,
    ];

    // Statement mock
    $statement = $this->createMock(\PDOStatement::class);
    $statement->expects($this->once())
        ->method('execute')
        ->with($this->callback(function ($params) use ($expectedParams) {
            return $params === $expectedParams;
        }))
        ->willReturn(true);

    // PDO mock
    $pdo = $this->createMock(\PDO::class);
    $pdo->expects($this->once())
        ->method('prepare')
        ->with('INSERT INTO cards (name, user_id) VALUES (:name, :user_id)')
        ->willReturn($statement);
    $pdo->method('lastInsertId')->willReturn('99');

    $card = new Card($pdo);

    expect($card->create([
        'name'    => 'Test Card',
        'user_id' => 5,
        'ignored' => 'should not persist',
    ]))->toMatchArray([
        'id' => 99,
        'name' => 'Test Card',
        'user_id' => 5,
    ]);
});

test('user create hashes password before insert', function () {
    $plainPassword = 'secret-password';

    $statement = $this->createMock(\PDOStatement::class);
    $statement->expects($this->once())
        ->method('execute')
        ->with($this->callback(function ($params) use ($plainPassword) {
            return $params['name'] === 'Test'
                && $params['email'] === 'test@test.com'
                && $params['password'] !== $plainPassword
                && password_verify($plainPassword, $params['password']);
        }))
        ->willReturn(true);

    $pdo = $this->createMock(\PDO::class);
    $pdo->expects($this->once())
        ->method('prepare')
        ->with('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)')
        ->willReturn($statement);
    $pdo->method('lastInsertId')->willReturn('7');

    $user = new User($pdo);

    expect($user->create([
        'name' => 'Test',
        'email' => 'test@test.com',
        'password' => $plainPassword
    ]))->toMatchArray([
        'id' => 7,
        'name' => 'Test',
        'email' => 'test@test.com',
        // hashed password checked in execute expectations
    ]);
});

test ('card update uses fillable whitelist and id binding', function () {
    $expectedParams = [
        'name' => 'Updated Name',
        'user_id' => 1,
        'id' => 10,
    ];

    $statement = $this->createMock(\PDOStatement::class);
    $statement->expects($this->once())
        ->method('execute')
        ->with($this->callback(function ($params) use ($expectedParams) {
            return $params === $expectedParams;
        }))->willReturn(true);

    $pdo = $this->createMock(\PDO::class);
    $pdo->expects($this->once())
        ->method('prepare')
        ->with('UPDATE cards SET name = :name, user_id = :user_id WHERE id = :id')
        ->willReturn($statement);

    $card = new Card($pdo);

    expect($card->updateById(10, [
        'name' => 'Updated Name',
        'user_id' => 1,
    ]))->toBeTrue();
});

test ('user update rehashes provided password', function () {
    $newPassword = 'new-password';

    $statement = $this->createMock(\PDOStatement::class);
    $statement->expects($this->once())
        ->method('execute')
        ->with($this->callback(function ($params) use ($newPassword) {
            return $params['name'] === 'Test'
                && password_verify($newPassword, $params['password'])
                && $params['id'] === 3;
      }))->willReturn(true);

    $pdo = $this->createMock(\PDO::class);
    $pdo->expects($this->once())
        ->method('prepare')
        ->with('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id')
        ->willReturn($statement);

    $user = new User($pdo);

    expect($user->updateById(3, [
        'name' => 'Test',
        'password' => $newPassword,
        'email' => 'test@test.com',
    ]))->toBeTrue();
});
