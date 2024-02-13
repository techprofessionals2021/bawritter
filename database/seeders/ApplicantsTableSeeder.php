<?php

namespace Database\Seeders;

use App\Models\ReferralSource;
use App\Models\ApplicantStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as FakerFactory;

class ApplicantsTableSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // if (!Storage::exists('resumes/dummy_resume.pdf')) {
        //     Storage::copy('dummy-content/dummy_resume.pdf', 'resumes/dummy_resume.pdf');
        // }

        $this->generate(30);
    }

    private function generate($number)
    {
        $faker = $this->faker;
        $statuses = ApplicantStatus::pluck('id')->toArray();
        $sources = ReferralSource::pluck('id')->toArray();

        $applicants = [];

        for ($i = 1; $i <= $number; $i++) {
            $applicants[] = [
                'number' => mt_rand(100000, 999999),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'about' => $faker->paragraph(4),
                'note' => $faker->sentence(4),
                'applicant_status_id' => $faker->randomElement($statuses),
                'country_id' => 1, // USA
                'referral_source_id' => $faker->randomElement($sources),
                'attachment' => 'resumes/dummy_resume.pdf',
                'created_at' => now(),
            ];
        }

        DB::table('applicants')->insert($applicants);
    }
}
