<?php

namespace Tests\Feature;

use Event;
use Tests\TestCase;
use App\Events\OrderStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $order;

    protected function setUp ()
    {
        parent::setUp();

        $this->withExceptionHandling();
        $this->order = create('App\Order', ['id' => 1]);
    }

    /** @test */
    public function unauthenticated_users_can_not_see_new_order()
    {
        $this->get(route('user.orders.show', $this->order))
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticate_users_can_see_new_order()
    {
        $this->signIn();

        $this->get(route('user.orders.show', $this->order))
            ->assertSee($this->order->id);
    }

    /** @test */
    public function only_order_owners_can_view_their_orders()
    {
        $this->signIn();

        $order = create('App\Order', ['user_id' => auth()->id()]);

        $this->get(route('user.orders'))
            ->assertSee($order->address);
    }

    /** @test */
    public function only_admins_can_view_all_orders()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $this->get(route('admin.orders'))
            ->assertStatus(200);
    }


    /** @test */
    public function admins_can_update_orders()
    {
        Event::fake();
        
        $this->signIn(factory('App\User')->states('admin')->create());

        $order = create('App\Order');

        $this->patch(route('admin.orders.update', $order), [
            'status_id' => 2
        ]);

        $this->assertEquals(2, $order->fresh()->status_id);

        Event::assertDispatched(OrderStatusChanged::class);

    }
}
