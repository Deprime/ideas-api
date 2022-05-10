<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator;
use Illuminate\Container\Container;

class IdeaSeeder extends Seeder
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
    $start = microtime(true);

    // Preconditions
    $batches_count = 100;
    $cycles_count  = 10000 * 10;
    $total_count   = ($cycles_count * $batches_count);
    $formated_total_count = number_format($total_count, 0, '.', ' ');

    $this->command->info("Ignintion. Start seeding $formated_total_count records...");

    // Truncate table
    DB::table('idea')->truncate();

    for ($i = 0; $i < $cycles_count; $i++) {
      $records = [];

      for ($j = 1; $j <= $batches_count; $j++) {
        $rownum = ($i * $batches_count + $j);
        $records[] = [
          'rownum'     => $rownum,
          // 'title'      => "Idea number $rownum",
          'title'      => $this->faker->sentence(),
          'created_at' => now(),
        ];
      }

      DB::table('idea')->insert($records);
    }

    $execution_time = date("H:i:s", microtime(true) - $start);
    $this->command->info('Seeding finished in ' . $execution_time);
  }
}
