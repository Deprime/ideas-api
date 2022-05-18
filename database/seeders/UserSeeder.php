<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
  Hash, DB,
};
use Faker\Generator;
use Illuminate\Container\Container;

class UserSeeder extends Seeder
{
  /**
   * The Faker instance
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Create a new seeder instance.
   * @return void
   */
  public function __construct()
  {
    $this->faker = $this->withFaker();
  }

  /**
   * Get a new Faker instance.
   * @return \Faker\Generator
   */
  protected function withFaker()
  {
    return Container::getInstance()->make(Generator::class);
  }

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    // Truncate table
    DB::table('users')->truncate();
    $this->command->info("Seeding 4 users.");

    $records = [
      [
        'name' => 'Cyber Johny',
        'email' => 'mnemonic@mail.io',
        'email_verified_at' => now(),
        'password' => Hash::make('password1'),
      ],
      [
        'name' => 'Mr Andreson',
        'email' => 'neo@mail.io',
        'email_verified_at' => now(),
        'password' => Hash::make('password1'),
      ],
      [
        'name' => 'Holy Konstantin',
        'email' => 'undevil@mail.io',
        'email_verified_at' => now(),
        'password' => Hash::make('password1'),
      ],
      [
        'name' => 'John the Wick',
        'email' => 'gunman@mail.io',
        'email_verified_at' => now(),
        'password' => Hash::make('password1'),
      ],
      [
        'name' => 'Mr Darth Vader',
        'email' => 'tatooin@mail.io',
        'email_verified_at' => now(),
        'password' => Hash::make('password1'),
      ],
      [
        'name' => 'Lil Yoda',
        'email' => 'naboo@mail.io',
        'email_verified_at' => now(),
        'password' => Hash::make('password1'),
      ],
    ];

    DB::table('users')->insert($records);
    $this->command->info('Users seeding finished. Next ...');

  }
}
