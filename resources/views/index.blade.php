@extends("layouts.app2")
@section("content")
    @if(session("updateSuccess"))
        <div class="bg-green-500 border border-red-400 text-white px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success</strong>
            <span class="block sm:inline">{{session("updateSuccess")}}</span>
        </div>
    @endif

    @if(session("loginSuccess"))
        <div class="bg-green-500 border border-red-400 text-white px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success</strong>
            <span class="block sm:inline">{{session("loginSuccess")}}</span>
        </div>
    @endif

    <main>
        <div class="p-6 w-[20rem]">
            @if(Auth::check())
                <h1 class="text-3xl font-bold text-gray-500 mb-6 text-center">Welcome Home {{Auth::user()->user_name}}</h1>
                <div class="flex justify-around">
                    <form method="POST" action="{{route("postLogout")}}">
                        @csrf
                        <button type="submit" class="shadow bg-blue-600  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">Logout</button>
                    </form>

                    <form method="POST" action="{{route("postDelete")}}">
                        @csrf
                        <button type="submit" class="shadow bg-red-600 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">Delete</button>
                    </form>

                    <a href="{{route("update")}}" class="shadow bg-emerald-400  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                        Update
                    </a>
                </div>
            @else
                <p class="text-xl text-red-500">Please <a href="{{ route('login') }}" class="text-blue-500 underline">login</a> to access your account.</p>
            @endif
        </div>
    </main>
@endsection
