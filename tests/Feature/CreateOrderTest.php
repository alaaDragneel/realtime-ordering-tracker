<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_can_not_create_new_order()
    {
        $this->withExceptionHandling();

        $this->post(route('user.orders.store'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_users_can_create_new_order()
    {
        $this->signIn();

        $order = create('App\Order', ['toppings' => null]);

        $response = $this->post(route('user.orders.store'), $order->toArray());
        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', $order->toArray());
    }

    /** @test */
    public function order_address_is_required()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $order = create('App\Order', ['address' => null]);

        $response = $this->post(route('user.orders.store'), $order->toArray());
        $response->assertSessionHasErrors('address');

    }

    /** @test */
    public function order_size_is_required()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $order = create('App\Order', ['size' => null]);

        $response = $this->post(route('user.orders.store'), $order->toArray());
        $response->assertSessionHasErrors('size');

    }
}
