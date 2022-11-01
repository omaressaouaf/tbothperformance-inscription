<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = CourseCategory::create([
            "name" => "Web development"
        ]);

        $plan = Plan::create([
            "name" => "STANDARD",
            "price" => 999,
            "features" => [
                "Assistance technique et pédagogique par e-mail ou téléphone dans un délai maximum de 24h",
                "Supports avancés"
            ],
        ]);

        $course = $category->courses()->create([
            "title" => "PHP & Laravel",
            "certificate" => "MSI DEVELOPMENT"
        ]);

        $course->plans()->attach($plan->id, [
            "cpf_link" => "https://www.moncompteformation.gouv.fr/espace-prive/html/#/formation/recherche/89986421900022_BUR_2022_04/89986421900022_SES_2022_10"
        ]);
    }
}
