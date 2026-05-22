<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inquiry;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private string $adminUser;
    private string $adminPass;

    public function __construct()
    {
        $this->adminUser = config('admin.username', 'admin');
        $this->adminPass = config('admin.password', 'manas2025');
    }

    // ── Auth ──────────────────────────────────────
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($request->username === $this->adminUser && $request->password === $this->adminPass) {
            session(['admin_logged_in' => true, 'admin_user' => $request->username]);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Incorrect credentials. Try again.')->withInput(['username' => $request->username]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_user']);
        return redirect()->route('home');
    }

    // ── Pages ─────────────────────────────────────
    public function dashboard()
    {
        $totalProducts    = Product::count();
        $totalInquiries   = Inquiry::count();
        $pendingInquiries = Inquiry::where('status', 'Pending')->count();
        $totalCategories  = Category::count();
        $trashedCategories = Category::onlyTrashed()->count();

        $productsByCategory = Category::withCount('products')
            ->active()
            ->ordered()
            ->get();

        $recentInquiries = Inquiry::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalInquiries', 'pendingInquiries',
            'totalCategories', 'trashedCategories', 'productsByCategory', 'recentInquiries'
        ));
    }

    public function inquiries()
    {
        $inquiries = Inquiry::with('user')->latest()->get();
        $trashedCount = Inquiry::onlyTrashed()->count();
        return view('admin.inquiries', compact('inquiries', 'trashedCount'));
    }

    public function products()
    {
        $products = Product::with(['category', 'variants'])->latest()->get();
        $trashedCount = Product::onlyTrashed()->count();
        return view('admin.products', compact('products', 'trashedCount'));
    }

    public function productsTrash()
    {
        $products = Product::onlyTrashed()->with(['category', 'variants'])->latest()->get();
        return view('admin.products-trash', compact('products'));
    }

    public function users()
    {
        // Hide all admin accounts from the standard users/customers list
        $users = User::where('is_admin', false)->where('is_superadmin', false)->latest()->get();
        $trashedCount = User::where('is_admin', false)->where('is_superadmin', false)->onlyTrashed()->count();
        return view('admin.users', compact('users', 'trashedCount'));
    }

    public function destroyUser(User $user)
    {
        if ($user->is_superadmin || $user->is_admin) {
            return back()->with('error', 'Administrative accounts cannot be deleted here.');
        }

        $user->delete();
        return back()->with('success', 'User moved to trash.');
    }

    public function usersTrash()
    {
        $users = User::where('is_admin', false)->where('is_superadmin', false)->onlyTrashed()->latest()->get();
        return view('admin.users-trash', compact('users'));
    }

    public function restoreUser($id)
    {
        User::onlyTrashed()->where('is_admin', false)->where('is_superadmin', false)->findOrFail($id)->restore();
        return back()->with('success', 'User restored successfully.');
    }

    public function forceDeleteUser($id)
    {
        User::onlyTrashed()->where('is_admin', false)->where('is_superadmin', false)->findOrFail($id)->forceDelete();
        return back()->with('success', 'User permanently deleted.');
    }

    // ── Admins CRUD (Super Admin Only) ──────────────────
    private function checkSuperAdmin()
    {
        if (!auth()->user() || !auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized action. Only Super Admin has access.');
        }
    }

    public function adminsIndex()
    {
        $this->checkSuperAdmin();
        $users = User::where('is_admin', true)->where('is_superadmin', false)->latest()->get();
        $trashedCount = User::where('is_admin', true)->where('is_superadmin', false)->onlyTrashed()->count();
        return view('admin.admins', compact('users', 'trashedCount'));
    }

    public function adminsTrash()
    {
        $this->checkSuperAdmin();
        $users = User::where('is_admin', true)->where('is_superadmin', false)->onlyTrashed()->latest()->get();
        return view('admin.admins-trash', compact('users'));
    }

    public function adminsCreate()
    {
        $this->checkSuperAdmin();
        return view('admin.admin-form', ['user' => new User()]);
    }

    public function adminsStore(Request $request)
    {
        $this->checkSuperAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->forceFill([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_admin' => true,
            'is_superadmin' => false,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
        ])->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin account created successfully.');
    }

    public function adminsEdit(User $user)
    {
        $this->checkSuperAdmin();
        if (!$user->is_admin || $user->is_superadmin) {
            abort(404);
        }
        return view('admin.admin-form', compact('user'));
    }

    public function adminsUpdate(Request $request, User $user)
    {
        $this->checkSuperAdmin();
        if (!$user->is_admin || $user->is_superadmin) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:20|unique:users,mobile,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->forceFill($data)->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin account updated successfully.');
    }

    public function adminsDestroy(User $user)
    {
        $this->checkSuperAdmin();
        if (!$user->is_admin || $user->is_superadmin) {
            abort(404);
        }
        $user->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin moved to trash.');
    }

    public function adminsRestore($id)
    {
        $this->checkSuperAdmin();
        $user = User::onlyTrashed()->where('is_admin', true)->where('is_superadmin', false)->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.admins.trash')->with('success', 'Admin restored successfully.');
    }

    public function adminsForceDelete($id)
    {
        $this->checkSuperAdmin();
        $user = User::onlyTrashed()->where('is_admin', true)->where('is_superadmin', false)->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('admin.admins.trash')->with('success', 'Admin permanently deleted.');
    }
}
