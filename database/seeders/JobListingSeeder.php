<?php

namespace Database\Seeders;

use App\Models\JobListing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobListing::create([
            'job_title' => 'Machine Learning Developer',
            'user_id' => 1,
            'api_json' => json_decode('{
                "skills":[
                   "Python",
                   "TensorFlow",
                   "Keras",
                   "PyTorch",
                   "scikit-learn",
                   "NumPy",
                   "Pandas",
                   "Machine Learning",
                   "Deep Learning",
                   "Data Preprocessing",
                   "Model Deployment"
                ],
                "programming_language":[
                   "Python",
                   "R",
                   "Java",
                   "SQL"
                ],
                "education_history":[
                   "MS or PhD degree in Computer Science, Data Science, or related field"
                ],
                "summary":"A machine learning engineer with extensive experience in developing and deploying machine learning models is required. A minimum of 2 years of experience is necessary. The engineer must be proficient in using machine learning frameworks such as TensorFlow, Keras, and PyTorch, and have experience with data preprocessing and model deployment. A degree is not required but preferred for MS or PhD in computer science, data science, or related field."
             }', true),
            'job_details' => json_decode('{"company_profile":"Our company is a leading tech firm specializing in cutting-edge machine learning solutions. We are dedicated to pushing the boundaries of artificial intelligence and creating innovative products that impact millions of lives.","job_title":"Machine Learning Developer","requirements":["Proficiency in Python programming language","Solid understanding of machine learning algorithms and techniques","Experience with data preprocessing, model training, and evaluation","Strong problem-solving skills and analytical thinking"],"what_will_you_do":["Develop and implement machine learning models and algorithms","Collaborate with cross-functional teams to design and deploy ML solutions","Optimize model performance and scalability","Stay up-to-date with the latest trends and advancements in the field of machine learning"],"benefits":["Competitive salary and bonuses","Flexible work hours and remote work options","Professional development opportunities","Health insurance and wellness programs"]}', true)
        ]);

        JobListing::create([
            'job_title' => 'Flutter Developer',
            'user_id' => 1,

            'api_json' => json_decode('{
                "skills":[
                   "Flutter",
                   "Dart",
                   "Material UI",
                   "State Management",
                   "BLoc",
                   "Firebase",
                   "Riverpod",
                   "Git",
                   "Responsive Development",
                   "API Integration"
                ],
                "programming_language":[
                   "Dart",
                   "Flutter"
                ],
                "education_history":[
                   "BS degree in Computer Science or related"
                ],
                "summary":"This job requires a flutter developer who is skilled in working in a diverse and fast paced environment. 1-2 years of experience in flutter development is preferred. The developer must be familiar with flutter and its core libraries. A degree in software related field is preferred"
             }', true),
            'job_details' => json_decode('{"company_profile":"We are a leading tech company specializing in creating innovative solutions for various industries. Our team is composed of talented individuals who are passionate about pushing the boundaries of technology.","job_title":"Flutter Developer","requirements":["3+ years of experience in Flutter development","Strong knowledge of Dart programming language","Experience with multiplatform development","Ability to work in a fast-paced environment and meet deadlines"],"what_will_you_do":["Develop and maintain mobile applications using Flutter framework","Collaborate with cross-functional teams to define, design, and ship new features","Optimize applications for maximum speed and scalability","Stay up-to-date with new technologies and trends in the mobile development space"],"benefits":["Competitive salary","Healthcare benefits","Flexible working hours","Professional development opportunities"]}', true)
        ]);

        JobListing::create([
            'job_title' => 'Javascript Developer',
            'user_id' => 1,

            'api_json' => json_decode('{
  "skills": [
    "HTML",
    "CSS",
    "JavaScript",
    "Vue",
    "Nuxt",
    "Tailwindcss",
    "SASS",
    "State Management",
    "LESS",
    "Git",
    "Agile Development",
    "API Integration",
    "RESTful Services",
    "Web Performance Optimization"
  ],
  "programming_language": [
    "HTML",
    "CSS",
    "JavaScript"
  ],
  "education_history": [
    "BS degree in Computer Science or Software Engineering"
  ],
  "summary": "This job requires a highly skilled JavaScript developer with atleast 3 years of experience and a minimum BS degree in Computer Science or Software Engineering. Applicant must be ready for teamwork in competitive environment with flexible hours."
}', true),
            'job_details' => json_decode('{"company_profile":"We are a leading tech company specializing in creating innovative solutions for our clients. Our team is dedicated to pushing the boundaries of technology and delivering top-notch products to our customers.","job_title":"Javascript Developer","requirements":["Bachelors degree in Computer Science or related field","Strong proficiency in JavaScript, including DOM manipulation and the JavaScript object model","Experience with modern frameworks and libraries such as React, Angular, or Vue","Solid understanding of web development technologies including HTML, CSS, and AJAX","Ability to work in a fast-paced environment and meet deadlines"],"what_will_you_do":["Developing front end website architecture","Designing user interactions on web pages","Creating servers and databases for functionality","Ensuring cross-platform optimization for mobile phones","Ensuring responsiveness of applications"],"benefits":["Competitive salary and benefits package","Opportunity for career growth and advancement","Flexible working hours and remote work options","Dynamic and collaborative work environment","Regular team building activities and events"]}', true)
        ]);
        JobListing::create([
            'user_id' => 1,

            'job_title' => 'Laravel Developer',
            'api_json' => json_decode('{
                "skills":[
                   "Laravel",
                   "PHP",
                   "Blade",
                   "MySQL",
                   "Rest API creation",
                   "Vite",
                   "Git",
                   "MVC architecture"
                ],
                "programming_language":[
                   "PHP",
                   "Blade",
                   "JavaScript",
                   "Laravel framework",
                   "MySQL"
                ],
                "education_history":[
                   "BS degree in Computer Science or related"
                ],
                "summary":"A backend developer who has extensive laravel experience is required. 2 years experience is minimum. The developer must know how to create restful API with laravel framework and must be familiar with laravel build tools like vite. A degree is not required but preferred for BS computer science or related field. "
             }', true),
            'job_details' => json_decode('{"company_profile":"Our company is a leading tech firm specializing in web development solutions.","job_title":"Laravel Developer","requirements":["Proficient in Laravel framework","Experience with PHP, HTML, CSS, and JavaScript","Strong understanding of MVC design patterns","Good communication skills"],"what_will_you_do":["Develop and maintain web applications using Laravel framework","Collaborate with the team to design and implement new features","Optimize application performance","Troubleshoot and debug issues"],"benefits":["Competitive salary","Opportunity for growth and advancement","Flexible work hours","Health insurance and other benefits"]}', true)
        ]);
    }
}
