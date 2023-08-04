<?php

namespace App\Http\Controllers;

use App\PromoCode;
use Carbon\Carbon;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PromoCodeController extends Controller
{
    //
    public function checkPromoCode(Request $request)
    {
        $request->validate([
            'promo_code' => 'required',
        ]);

        $promoCode = PromoCode::where('code', $request->input('promo_code'))->first();


        if ($promoCode && $promoCode->user_id === null) {
            // Associate the promo code with the authenticated user
            $promoCode->user_id = Auth::user()->id;

            $promoCode->promo_code_selection_date = Carbon::now();


            $promoCode->save();

            // Redirect back to the user's cabinet with a success message
            return redirect()->route('user.cabinet')->with('success', 'Промокод успешно применен!');
        } else {
            // The promo code is either not found or already associated with a user
            // Redirect back to the user's cabinet with an appropriate error message
            if ($promoCode && $promoCode->user_id !== null) {
                return redirect()->route('user.cabinet')->with('error', 'Промокод уже был использован.');
            } else {
                return redirect()->route('user.cabinet')->with('error', 'Промокод не действителен.');
            }
        }

    }
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();

        // Read the CSV file using Laravel's built-in CSV reader
        $csvData = array_map('str_getcsv', file($path));

        // Process each record and create PromoCode instances
        foreach ($csvData as $record) {
            try {
                // Ensure the record has enough elements before accessing them
                if (count($record) >= 3) {
                    $user_id = $record[0];
                    $promo_code_selection_date = $record[1];
                    $code = $record[2];

                    PromoCode::create([
                        'user_id' => $user_id,
                        'promo_code_selection_date' => $promo_code_selection_date,
                        'code' => $code,
                        // Add other attributes based on the columns in your CSV file
                    ]);
                } else {
                    // Handle cases where the record does not have enough elements
                    // For example, log the error or skip the record
                    Log::warning('Record does not have enough elements: ' . implode(', ', $record));
                }
            } catch (Exception $e) {
                // Handle any exceptions that occur during data insertion
                Log::error('Error inserting data: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.promo_codes.index')->with('success', 'Promo codes uploaded successfully.');
    }
}
