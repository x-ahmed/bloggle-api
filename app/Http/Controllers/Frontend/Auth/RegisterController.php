<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/profile';
    protected $redirectTo = RouteServiceProvider::USER;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'unique:users'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile'     => ['required', 'numeric', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'user_image' => ['nullable', 'image', 'max:20000', 'mimes:jpeg,jpg,png'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'mobile'   => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['user_image'])) {
            if ($image = $data['user_image']) {
                $file_name = Str::slug($data['username']).'.'.$image->getClientOriginalExtension();
                $path      = public_path('/assets/users/'.$file_name);
                Image::make($image->getRealPath())->resize(300, 300, function ($constraint)
                {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $user->update(['user_image'=>$file_name]);
            }
        }
        $user->attachRole(Role::whereName('user')->first()->id);
        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return redirect_with_msg(
            'frontend.index',
            'Registered Successfully, Please check your email for activation',
            'success'
        );
    }
}
