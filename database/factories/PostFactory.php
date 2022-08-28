<?php

 namespace Database\Factories;

 use Illuminate\Database\Eloquent\Factories\Factory;

 /**
  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
  */
 class PostFactory extends Factory
 {
     /**
      * Define the model's default state.
      *
      * @return array
      */
     public function definition()
     {
         return [
             'user_id' => (\App\Models\User::get()->random())->id,
             'title' => $this->faker->words(5, true),
             'body' => $this->faker->text(200),
         ];
     }
 }
