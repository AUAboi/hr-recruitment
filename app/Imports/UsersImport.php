<?php

namespace App\Imports;

use App\Models\User;
use App\Models\JobListing;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Str;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\PersistRelations;

class UsersImport implements PersistRelations, WithHeadingRow, OnEachRow
{


    public function onRow(Row $row)
    {
        $string = preg_replace("/'([^']+)'/", '"$1"', $row["llm_result"]);

        $json_decode = json_decode($string, true);


        if (json_last_error()) {
            return null;
        }

        $user = User::create([
            'first_name'     => $json_decode['name'],
            'username' => Str::slug($json_decode['name']) . Str::random(5),
            'last_name'    => $json_decode['father_name'] ?? Str::random(10),
            'email' => Str::slug($json_decode['name']) . Str::random(5) . "@gmail.com",
            'password' => Hash::make('password'),
        ]);

        $user->jobApplications()->create(
            [
                'user_id' =>  $user->id,
                'data' => json_encode($json_decode),
                'uuid' => Str::uuid(),
                'job_listing_id' => 1,
            ]
        );
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $string = preg_replace("/'([^']+)'/", '"$1"', $row["llm_result"]);

        $json_decode = json_decode($string, true);


        if (json_last_error()) {
            return null;
        }

        $user = new User([
            'first_name'     => $json_decode['name'],
            'username' => Str::slug($json_decode['name']) . Str::random(5),
            'last_name'    => $json_decode['father_name'] ?? Str::random(10),
            'email' => Str::slug($json_decode['name']) . Str::random(5) . "@gmail.com",
            'password' => Hash::make('password'),
        ]);

        $user->jobApplications()->create(
            [
                'user_id' =>  $user->id,
                'data' => json_encode($json_decode),
                'uuid' => Str::uuid(),
                'job_listing_id' => 1,
            ]
        );

        return $user;
    }
}
