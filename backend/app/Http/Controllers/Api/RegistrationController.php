<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalRegistrationRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function register(FinalRegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['first_name'].' '.$data['last_name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'type_of_participation' => $data['type_of_participation'],
            ]);

            $companyData = $data['company'];
            $brochurePath = null;
            if ($request->hasFile('company.brochure')) {
                $file = $request->file('company.brochure');
                $brochurePath = $file->store('public/brochures');
            }

            $company = new Company([
                'company_name' => $companyData['company_name'],
                'address_line' => $companyData['address_line'],
                'town_city' => $companyData['town_city'],
                'region_state' => $companyData['region_state'],
                'country' => $companyData['country'],
                'year_established' => $companyData['year_established'],
                'website' => $companyData['website'] ?? null,
                'brochure_path' => $brochurePath,
            ]);

            $user->company()->save($company);

            DB::commit();

            return response()->json(['message' => 'Registration successful.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if (!empty($brochurePath)) {
                Storage::delete($brochurePath);
            }
            return response()->json(['message' => 'Registration failed.', 'error' => $e->getMessage()], 500);
        }
    }
}
