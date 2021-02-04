<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\UserCurrency;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;


class UserUpdateService
{
    private $user;
    private $request;

    function update($user, $request)
    {
        $this->user = $user;
        $this->request = $request;

        $this->updateUser();
        $this->updateUserCurrencies();

        return new UserResource($this->user);
    }

    function updateUser()
    {
        if ($this->request->file('image')) {
            $uploadFolder = 'users';
            $image = $this->request->file('image');
            $image_uploaded_path = $image->store($uploadFolder, 'public');

            $this->user->image = URL::to('/') . Storage::url($image_uploaded_path);
        }


        $this->user->firstname = $this->request->firstname;
        $this->user->lastname = $this->request->lastname;
        $this->user->tax_rate = $this->request->tax_rate;
        $this->user->notification_period = $this->request->notification_period;
        $this->user->save();
    }

    function updateUserCurrencies()
    {
        $userCurrencies = UserCurrency::where("user_id", $this->user->id)->get();

        foreach ($userCurrencies as $dbCurrency) {

            foreach ($this->request->currencies as $requestCurrency) {

                if ($dbCurrency->currency == $requestCurrency["currency"]) {
                    $dbCurrency->force_exchange = $requestCurrency["force_exchange"];
                    $dbCurrency->force_exchange_amount = $requestCurrency["force_exchange_amount"];
                    $dbCurrency->save();
                }
            }
        }
    }
}
