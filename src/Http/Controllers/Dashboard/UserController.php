<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\User;
use Illuminate\Auth\Events\Registered;
use Maatwebsite\Excel\Facades\Excel;
use Sndpbag\AdminPanel\Exports\UsersExport; // We will create this class next
use Sndpbag\AdminPanel\Imports\UsersImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // public function index()
    // {
    //     $users = User::all();

    //     return view('dashboard.users.index', compact('users'));
    // }

     public function index(Request $request)
    {
        // Start with a query builder instance
        $query = User::query();

        // Handle Search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Handle Status Filter
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Handle Role Filter
        if ($request->filled('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // Paginate the results
        $users = $query->latest()->paginate(10); // Show 10 users per page

        // Pass all request inputs to the view for filter persistence
        return view('admin-panel::dashboard.users.index', compact('users'))->with('request', $request->all());
    }

    public function create()
    {
        return view('admin-panel::dashboard.users.create');
    }

  /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    // --- Validation ---
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:Admin,User',
        'status' => 'required|in:Active,Inactive',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Max 2MB
    ]);

    // --- Prepare Data for Creation ---
    $data = $request->except('password', 'profile_image');
    $data['password'] = Hash::make($request->password);

    // --- Handle Profile Image Upload ---
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $data['profile_image'] = $path;
    }

    // --- Create the User ---
    $user = User::create($data);

       event(new Registered($user));
    
    return redirect()->route('users.index')->with('success', 'User created successfully!');
}

    public function edit(User $user)
    {
        // Fetch user by ID
        return view('admin-panel::dashboard.users.edit', compact('user'));
    }

 

       public function update(Request $request, User $user)
    {
        // --- Validation ---
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Ensure the email is unique, but ignore the current user's email
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            // Password is optional. If provided, it must be confirmed and at least 8 characters.
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Admin,User',
            'status' => 'required|in:Active,Inactive',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Max 2MB
        ]);

        // --- Prepare Data for Update ---
        $updateData = $request->except('password', 'profile_image');

        // --- Handle Optional Password Update ---
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // --- Handle Profile Image Upload ---
        if ($request->hasFile('profile_image')) {
            // 1. Delete the old image if it exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // 2. Store the new image and get its path
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $updateData['profile_image'] = $path;
        }

        // --- Update the User ---
        $user->update($updateData);
        
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

public function destroy(User $user)
{
   
    // This will now perform a SOFT DELETE because of the trait in the User model.
    // It sets the `deleted_at` column to the current timestamp.
    $user->delete();
    
    // The message is updated to be more accurate for a soft delete.
    return redirect()->route('users.index')->with('success', 'User moved to trash successfully!');
}

      /**
     * Toggle the status of a user.
     */
    public function toggleStatus(User $user)
    {
        $user->status = ($user->status == 'Active') ? 'Inactive' : 'Active';
        $user->save();

        return back()->with('success', 'User status updated successfully.');
    }

    /**
     * Toggle the role of a user.
     */
    public function toggleRole(User $user)
    {
        $user->role = ($user->role == 'Admin') ? 'User' : 'Admin';
        $user->save();

        return back()->with('success', 'User role updated successfully.');
    }

    // Excel Export
      public function export(Request $request, $type)
    {
        // Get filtered list of users (same logic as index but without pagination)
        $query = User::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->get();
        $filename = 'users-' . now()->format('Y-m-d') . '.' . $type;

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('admin-panel::dashboard.pdf.user', compact('users'));
            return $pdf->download($filename);
        }

        // For Excel/CSV, we need an Export class
        return Excel::download(new UsersExport($users), $filename);
    }

    // import 

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    try {
        Excel::import(new UsersImport, $request->file('file'));
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errorMessages = [];

        // Loop through each failure and create a user-friendly message
        foreach ($failures as $failure) {
            $errorMessages[] = "Error on row " . $failure->row() . ": " . implode(', ', $failure->errors());
        }

        // Redirect back with the formatted errors in a named error bag ('import')
        // And flash a session variable to re-open the modal
        return redirect()->route('users.index')
            ->withErrors($errorMessages, 'import')
            ->with('show_import_modal', true);
    }
    
    return redirect()->route('users.index')->with('success', 'Users imported successfully!');
}

public function downloadTemplate()
{
    $path = public_path('templates/user_import_template.xlsx');
    // Create the templates directory and the file if it doesn't exist
    if (!file_exists($path)) {
        if (!is_dir(public_path('templates'))) {
            mkdir(public_path('templates'), 0755, true);
        }
        // You would typically generate this with a package, but for simplicity, you can
        // place a pre-made template file in `public/templates/`
        // For this example, let's assume you have placed 'user_import_template.xlsx' there.
        // The headers in the Excel file should be: name, email, password, phone, role, status
    }
    return response()->download($path);
}


//  soft delete

 /**
     * Display a listing of soft deleted users.
     */
    public function trashed(Request $request)
    {
        $query = User::onlyTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $users = $query->orderBy('deleted_at', 'desc')->paginate(10);

        return view('admin-panel::dashboard.users.trashed', compact('users'));
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        $user->restore();

        return redirect()->route('users.trashed')
            ->with('success', 'User "' . $user->name . '" has been restored successfully!');
    }

    /**
     * Permanently delete a user from database.
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        $userName = $user->name;

        // Delete profile image if exists
        if ($user->profile_image) {
            // Delete from storage
            $imagePath = public_path('storage/' . $user->profile_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Permanently delete the user
        $user->forceDelete();

        return redirect()->route('users.trashed')
            ->with('success', 'User "' . $userName . '" has been permanently deleted!');
    }
    
}
