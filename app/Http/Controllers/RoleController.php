<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return Inertia::render('Auth/Register', [
            'roles' => $roles,
        ]);
    }
}
