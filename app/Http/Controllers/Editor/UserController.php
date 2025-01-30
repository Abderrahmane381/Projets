<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    // ...existing code...

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:guest,subscriber,theme_responsible,editor'
        ]);

        try {
            DB::beginTransaction();
            
            // Detach all roles first
            DB::table('model_has_roles')
                ->where('model_type', User::class)
                ->where('model_id', $user->id)
                ->delete();

            // Attach new role
            DB::table('model_has_roles')->insert([
                'role_id' => DB::table('roles')->where('name', $request->role)->value('id'),
                'model_type' => User::class,
                'model_id' => $user->id
            ]);

            DB::commit();
            return back()->with('success', 'User role updated successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update user role');
        }
    }
}
