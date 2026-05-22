<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    /** Public: submit a query from the website */
    public function store(Request $request)
    {
        $data = $request->validate([
            'contact'  => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'category' => 'nullable|string|max:100',
            'product'  => 'nullable|string|max:255',
            'message'  => 'required|string',
        ]);

        $user = Auth::user();

        $data['user_id']  = $user->id;
        $data['name']     = $user->name;
        $data['status']   = 'Pending';
        $data['category'] = $data['category'] ?? 'General';

        Inquiry::create($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "✓ Your query has been sent! We'll contact you within 24 hours.");
    }

    /** Admin: toggle Pending ↔ Resolved */
    public function toggleStatus(Inquiry $inquiry)
    {
        $inquiry->toggleStatus();

        if ($inquiry->status === 'Resolved') {
            $inquiry->resolved_at = now();
            $inquiry->save();

            if ($inquiry->user && $inquiry->user->email && !str_starts_with($inquiry->user->email, 'mobile_')) {
                try {
                    \Illuminate\Support\Facades\Mail::to($inquiry->user->email)
                        ->send(new \App\Mail\InquiryResolvedMail($inquiry));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to send inquiry resolution email: " . $e->getMessage());
                }
            }

            // Immediately soft delete the resolved inquiry to trash
            $inquiry->delete();
        }

        if (request()->expectsJson()) {
            return response()->json(['status' => 'Resolved', 'deleted' => true]);
        }

        return back()->with('success', 'Inquiry resolved and automatically moved to Trash.');
    }

    /** Admin: resolve with a custom response email */
    public function resolveWithResponse(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'response' => 'required|string|min:5',
        ]);

        $inquiry->forceFill([
            'status' => 'Resolved',
            'response' => $request->response,
            'resolved_at' => now(),
        ])->save();

        if ($inquiry->user && $inquiry->user->email && !str_starts_with($inquiry->user->email, 'mobile_')) {
            try {
                \Illuminate\Support\Facades\Mail::to($inquiry->user->email)
                    ->send(new \App\Mail\InquiryResolvedMail($inquiry));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send custom inquiry resolution email: " . $e->getMessage());
            }
        }

        // Immediately soft delete the resolved inquiry to trash
        $inquiry->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => 'Resolved',
                'response' => $inquiry->response,
                'message' => 'Response emailed and inquiry automatically moved to Trash.'
            ]);
        }

        return back()->with('success', 'Response emailed and inquiry automatically moved to Trash.');
    }

    /** Admin: delete inquiry */
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Inquiry moved to trash.');
    }

    public function trash()
    {
        $inquiries = Inquiry::onlyTrashed()->with('user')->latest()->get();
        return view('admin.inquiries-trash', compact('inquiries'));
    }

    public function restore($id)
    {
        Inquiry::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Inquiry restored.');
    }

    public function forceDelete($id)
    {
        Inquiry::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Inquiry permanently deleted.');
    }
}
