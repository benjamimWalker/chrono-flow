<?php

namespace Database\Factories;

use App\Models\WorkLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WorkLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_name' => $this->faker->name(),
            'date' => Carbon::now(),
            'hours' => $this->faker->word(),
            'description' => $this->faker->text(),
        ];
    }
}
