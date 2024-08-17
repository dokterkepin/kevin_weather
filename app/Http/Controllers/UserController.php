<?php

namespace App\Http\Controllers;


use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected UserService $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function index(): View
    {
        return view('/index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(): View
    {
        return view('/register');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function postRegister(Request $request): RedirectResponse
    {
        Log::info("handling registration form submission", request()->all());
        Log::info("right know in: " . url()->current());
        $validated = $request->validate([
            "user_id" => "required|string|max:255|unique:user_account",
            "user_name" => "required|string|max:255",
            "user_email" => "required|string|email|max:255|unique:user_account",
            "user_password" => "required|min:8|confirmed"
        ]);

        $this->userService->register($validated);
        Log::info("user register successfully");
        return redirect("/login");

    }

    /**
     * Display the specified resource.
     */
    public function login(): View
    {
        return view('/login');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function postLogin(Request $request): RedirectResponse
    {
        Log::info("handling login form submission", request()->all());
        Log::info("right know in: " . url()->current());
        $validated = $request->validate([
            "user_id" => "required|string",
            "user_password" => "required|min:8"
        ]);

        if($this->userService->login($validated)){
            return redirect("/")->with("loginSuccess", "User Logged In");
        }

        return back()->withErrors([
            "login_error" => "Your ID or password is not correct."
        ])->withInput($request->only('user_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): View
    {
        return view('/update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function postUpdate(Request $request): RedirectResponse
    {
        Log::info("handling update form submission", ["user_name " => Auth::user()->user_name]);
        Log::info("right know in: " . url()->current());
        $validated = $request->validate([
            "user_name" => "required|string|max:255",
            "user_password" => "required|min:8|confirmed"
        ]);

        if($this->userService->update($validated)){
            return redirect("/")->with("updateSuccess", "User updated successfully");
        }
        Log::info("user credential is the same");
        return redirect("/update")->withErrors([
            "update_error" => "Your name or password is the same."
        ])->withInput($request->only('user_name'));
    }

    public function postLogout(): RedirectResponse
    {
        Log::info("handling logout form submission", ["for user_name" => Auth::user()->user_name]);
        Log::info("right know in: " . url()->current());
        $this->userService->logout();

        return redirect("/login")->with("logoutSuccess", "User Logged Out");
    }

    public function postDelete(): RedirectResponse
    {
        Log::info("handling logout form submission", ["for user_name" => Auth::user()->user_name]);
        Log::info("right know in: " . url()->current());
        $this->userService->delete();
        return redirect("/register")->with("deleteSuccess", "User Deleted Successfully");
    }




}
