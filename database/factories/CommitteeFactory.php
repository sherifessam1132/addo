<?php

namespace Database\Factories;

use App\Models\Committee;
use Faker\Provider\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
//use Faker;

class CommitteeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Committee::class;

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
            'license_number'=> Str::random(10),
            'special'=>$this->faker->randomElement(['0','1']),
            'longitude'=> Address::longitude(),
            'latitude'=> Address::latitude(),
            'url'=>$this->faker->url,
            'sort_order'=>$this->faker->randomElement([1,100])
        ];
    }
}
