@extends("layouts.app2")
@section("content")
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="h-lvh content-center grid lg:grid-cols-[2fr,1.5fr]">
        <h1 class="text-center text-3xl font-bold text-gray-500 m-auto">Welcome Home, Register New User</h1>
        <form class="w-5/6 shadow-lg p-5 m-auto" action="{{route("postRegister")}}" method="POST">
            @csrf
            <div class="mb-6">
                <input name="user_id" class="border-b-2 border-gray-200 w-full py-2 p text-gray-700 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="User ID">
            </div>

            <div class="mb-6">
                <input name="user_name" class="border-b-2 border-gray-200 w-full py-2 p text-gray-700 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="User Name">
            </div>

            <div class="mb-6">
                <input name="user_password" class="border-b-2 border-gray-200 w-full py-2 p text-gray-700 leading-tight focus:outline-none focus:bg-white" type="password" placeholder="User Password">
            </div>

            <div class="mb-6">
                <input name="user_password_confirmation" class="border-b-2 border-gray-200 w-full py-2 p text-gray-700 leading-tight focus:outline-none focus:bg-white" type="password" placeholder="Confirm">
            </div>

            <div class="mb-6">
                <input name="user_email" class="border-b-2 border-gray-200 w-full py-2 p text-gray-700 leading-tight focus:outline-none focus:bg-white" type="email" placeholder="User Email">
            </div>

            <div class="flex justify-end">
                <button class="shadow bg-gray-500  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                        Register
                </button>
            </div>
        </form>
    </main>
@endsection
