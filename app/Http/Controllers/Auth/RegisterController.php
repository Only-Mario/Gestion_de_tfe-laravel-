<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Rules\AnneeDuNiveau;
use App\Rules\Inedit;
use App\Services\CodeEmail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Tfe;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; // Ajouter cette ligne

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,filter', 'max:255', new Inedit('email')],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'entity' => ['required', 'string', Rule::exists('filieres', 'nom')->where('active', true)->whereNotNull('niveau_id')],
            'study_year' => ['required', 'integer', new AnneeDuNiveau($data['entity'] ?? null)],
            'matricule' => ['required', 'string', 'regex:/^[0-9A-Za-z-]{6,30}$/', new Inedit('matricule')], 
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    /** Le compte est créé ; l'adresse se confirme avec le code envoyé. */
    protected function registered(Request $request, $user)
    {
        return redirect()->route('email.confirmer')
            ->with(...CodeEmail::message(CodeEmail::envoyer($user, 'confirmation', $user->email), $user->email));
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'entity' => $data['entity'],
            'niveau_id' => Filiere::where('nom', $data['entity'])->value('niveau_id'),
            'study_year' => $data['study_year'],
            'matricule' => $data['matricule'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
        public function showRegistrationForm()
    {
        $niveaux = Niveau::ordonnes()->whereIn('code', ['licence', 'master'])->with('filieresActives')->get();
        return view('auth.register', compact('niveaux'));
    }
}
