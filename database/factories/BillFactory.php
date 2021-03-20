<?php

namespace Database\Factories;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bill_identifier' => $this->faker->name,
            'bill_date' => $this->faker->dateTimeBetween('now', '+10 years'),
            'bill_amount' => $this->faker->numberBetween(50, 9999),
            'bill_type' => 0,
            'bill_stats' => 0,
        ];
    }
}
