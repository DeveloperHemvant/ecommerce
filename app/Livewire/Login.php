<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        
        'email' => 'required|email',
        'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'Please enter your password.',
        'password.string' => 'Please enter a valid password.',
        'password.regex' => 'The password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
    ];
   public function login(){
    $credentials = [
        'email' => $this->email,
        'password' => $this->password,
    ];
    $this->validate();
    if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
        
        return redirect()->intended('/admindashboard');
    } else {
        $this->addError('login', 'Invalid email or password.');
    }
   }
    public function render()
    {
        return view('livewire.login');
    }
}
