<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            'الطب البشري',
            'طب الأسنان ',
            'الصيدلة ',
            ' التمريض ',
            'المختبرات ',
            'الاأشعة',
            'الهندسة المدنية ',
            'الهندسة المعمارية ',
            'الهندسة الكهربائية',
            'الهندسة الميكانيكية ',
            'الهندسة الصناعية',
            'علوم حاسوب ',
            'نظم معلومات ',
            'تقنية المعلومات ',
            'الأمن السيبراني ',
            'الذكاء الأصطناعي ',
            'إدارة الأعمال',
            'المحاسبة',
            'الاقتصاد',
            ' الشريعة والقانون ',
            '  الدراسات الاسلامية ',
            ' الاعلام  ',
            ' الترجمة  ',
            ' اللغة العربية  ',

        ];

        foreach ($specialties as $name) {
            Specialty::firstOrCreate(['name' => $name]);
            $this->call([
        SpecialtySeeder::class,
        // أي Seeders أخرى هنا...
    ]);
        }
    }

}
