<?php

namespace Database\Factories;

use App\Models\Committee;
use App\Models\Project;
use Faker\Provider\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ar'=>[
                'name'=>$this->faker->name,
                'description'=>$this->faker->paragraph(1),
            ],
            'image'=>'default.png',
            'special'=>$this->faker->randomElement(['0','1']),
            'url'=>$this->faker->url,
            'committee_id'=>Committee::all()->random()->id
        ];
    }
}
