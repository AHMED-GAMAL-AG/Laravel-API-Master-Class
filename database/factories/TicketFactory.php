<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(TicketStatus::toArray()),
        ];
    }
}
