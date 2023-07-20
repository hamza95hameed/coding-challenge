<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Connection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loggedInUserId = auth()->user()->id;
        $users = User::where('id', '!=', $loggedInUserId)
            ->whereDoesntHave('connections')
            ->whereDoesntHave('receivedConnections')
            ->get();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user()->id;
        $connection = Connection::create([
            'user_id' => $user,
            'connected_user_id' => $request->connectedUserId,
            'status' => 0
        ]);

        return $connection;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->roles()->attach(2); // Simple user role

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'Invalid credentials'
                    ],
                ]
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $authToken,
        ]);
    }

    public function sentConnectionRequest(){
        try {
            $loggedInUserId = auth()->user()->id;
            $user = User::with('connections')->find($loggedInUserId);
            $sentConnections = $user->connections()->where('status', 0)->get();

            return $sentConnections->load('connectedUser');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function withdrawConnectionRequest(Request $request){
        try {
            $loggedInUserId = auth()->user()->id;
            $user = User::find($loggedInUserId);
            $withdrawConnection = $user->connections()->where('connected_user_id', $request->connected_user_id)->delete();

            return $withdrawConnection;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
