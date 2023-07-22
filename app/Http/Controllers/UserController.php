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
            ->whereDoesntHave('connections', function ($query) use ($loggedInUserId) {
                $query->where('connected_user_id', $loggedInUserId);
            })
            ->whereDoesntHave('receivedConnections', function ($query) use ($loggedInUserId) {
                $query->where('user_id', $loggedInUserId);
            })
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
            'connected_user_id' => $request->connectionId,
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

    public function logout(Request $request){
        $user = auth()->user()->id;
        $user->tokens()->delete();
        return true;
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
            $user           = User::find($loggedInUserId);
            $connection     = $user->connections()
                                ->where('connected_user_id', $request->connectionId)
                                ->where('status', 0)
                                ->delete();
            return $connection;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function receivedConnectionRequest(){
        try {
            $loggedInUserId      = auth()->user()->id;
            $user                = User::find($loggedInUserId);
            $receivedConnections = $user->receivedConnections()->where('status', 0)->get();
            return $receivedConnections->load('user');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function acceptConnectionRequest(Request $request){
        try {
            $loggedInUserId = auth()->user()->id;
            $user           = User::find($loggedInUserId);
            $connection     = $user->receivedConnections()->where('user_id', $request->connectionId)->first();
            $connection->update(['status' => 1]);
            return $connection;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getConnectedConnections(Request $request){
        try {
            $loggedInUserId = auth()->user()->id;
            $connectedUsers = User::whereHas('receivedConnections', function ($query) use ($loggedInUserId) {
                $query->where('user_id', $loggedInUserId)->where('status', 1);
            })
            ->orWhereHas('connections', function ($query) use ($loggedInUserId) {
                $query->where('connected_user_id', $loggedInUserId)->where('status', 1);
            })
            ->get();
            return $connectedUsers;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function removeConnectionRequest(Request $request){
        try {
            $loggedInUserId = auth()->user()->id;
            $connectedUsers = Connection::where(function ($query) use ($loggedInUserId, $request) {
                $query->where('user_id', $loggedInUserId)
                    ->where('connected_user_id', $request->connectionId);
            })
            ->orWhere(function ($query) use ($loggedInUserId, $request) {
                $query->where('user_id', $request->connectionId)
                    ->where('connected_user_id', $loggedInUserId);
            })->first();

            $connectedUsers->delete();
            
            return $connectedUsers;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getCount(){
        try {
            $loggedInUserId = auth()->user()->id;

            // Count suggested users
            $suggestedUsersCount = User::where('id', '!=', $loggedInUserId)
                ->whereDoesntHave('connections', function ($query) use ($loggedInUserId) {
                    $query->where('connected_user_id', $loggedInUserId);
                })
                ->whereDoesntHave('receivedConnections', function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId);
                })
                ->count();

            // Count sent connections
            $sentConnectionsCount = User::find($loggedInUserId)
                ->connections()
                ->where('status', 0)
                ->count();

            // Count received connections
            $receivedConnectionsCount = User::find($loggedInUserId)
                ->receivedConnections()
                ->where('status', 0)
                ->count();

            // Count connected users
            $connectedUsersCount = User::whereHas('receivedConnections', function ($query) use ($loggedInUserId) {
                $query->where('user_id', $loggedInUserId)->where('status', 1);
            })
            ->orWhereHas('connections', function ($query) use ($loggedInUserId) {
                $query->where('connected_user_id', $loggedInUserId)->where('status', 1);
            })
            ->count();

            $data = [
                'suggestedUsersCount'      => $suggestedUsersCount,
                'sentConnectionsCount'     => $sentConnectionsCount,
                'receivedConnectionsCount' => $receivedConnectionsCount,
                'connectedUsersCount'      => $connectedUsersCount                
            ];

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
