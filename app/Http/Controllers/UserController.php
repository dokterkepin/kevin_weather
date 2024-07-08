<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function index()
    {
        return view("index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("register");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'user_id' => 'required|string|max:255|unique:user_account',
                "user_name" => "required|string|max:255",
                "user_email" => "required|string|email|max:255|unique:user_account",
                "user_password" => "required|string|min:8|confirmed"
            ]);
            Log::info("validation passed", request()->all());
            $this->userService->register($request->all());
            return redirect("/");
        }catch(ValidationException $e){
            Log::error("validationException: " . $e->getMessage());
            return back()->withErrors($e->validator->errors())->withInput();

        }catch(\Exception $e){
            Log::error("Exception: " . $e->getMessage());
            return back()->with("error", "an error occurred while processing your request");
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
