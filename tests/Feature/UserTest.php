<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    private $name = "Test User";
    private $password = "mypassword";
    
    public function testUserCreation()
    {
       

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ])->json('POST', '/api/auth/signup', [
            'name' => $this->name, 
            'email' => Str::random(7)."@mail.com",
            'password' => $this->password, 
            'password_confirmation' => $this->password
        ]); 


        $response
            ->assertStatus(201)
            ->assertExactJson([
                'message' => "Successfully created user!",
            ]);
    }//testUserCreation

    public function testUserLogin()
    {
        $email = Str::random(7)."@mail.com";

        $user = new User([
            'name' => $this->name,
            'email' => $email,
            'password' => bcrypt($this->password)
        ]);        
        
        $user->save();     
        
        $response = $this->postJson('/api/auth/login', [
            'email' => $email,
            'password' => $this->password
        ]);

        //var_dump($response->getContent());
        
        $response->assertStatus(200);
    }//testUserLogin

    
}
