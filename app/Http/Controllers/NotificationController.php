<?php

namespace App\Http\Controllers;

use App\Models\ProfilAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->role == 'admin') {
                $notifications = Auth::user()->notifications;
                foreach (ProfilAdmin::with('user')->get() as $admin) {
                    $notifications = $notifications->union($admin->user->notifications);
                }
            } else {
                $notifications = Auth::user()->notifications;
            }

            return DataTables::of($notifications)
                ->addColumn('judul', function ($row) {
                    return $row->data['title'];
                })
                ->addColumn('pesan', function ($row) {
                    return $row->data['message'];
                })
                ->addColumn('linkTitle', function ($row) {
                    return $row->data['linkTitle'];
                })
                ->addColumn('read', function ($row) {
                    return $row->read_at == null ? '1' : '0';
                })
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('link', function ($row) {
                    return url($row->data['link']);
                })
                ->make(true);
        }
        return view('notification.index');
    }

    public function getUnreaded()
    {
        if (Auth::user()->role == 'admin') {
            $notifications = Auth::user()->unreadNotifications;
            foreach (ProfilAdmin::with('user')->get() as $admin) {
                $notifications = $notifications->union($admin->user->unreadNotifications);
            }
        } else {
            $notifications =  Auth::user()->unreadNotifications;
        }

        $data = [];
        foreach ($notifications as $notification) {
            $data[] = [
                'id' => $notification->id,
                'judul' => $notification->data['title'],
                'pesan' => $notification->data['message'],
                'linkTitle' => $notification->data['linkTitle'],
                'link' => url($notification->data['link']),
                'read' => $notification->read_at == null ? '1' : '0',
            ];
        }
        return response()->json(['data' => $data]);
    }

    public function readall()
    {
        try {
            if (Auth::user()->role == 'admin') {
                foreach (ProfilAdmin::with('user')->get() as $admin) {
                    $admin->user->unreadNotifications->markAsRead();
                }
            } else {
                Auth::user()->unreadNotifications->markAsRead();
            }

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function read(Request $request)
    {
        try {
            if (Auth::user()->role == 'admin') {
                $notification = ProfilAdmin::with('user.notifications')
                    ->get()
                    ->pluck('user.notifications')
                    ->flatten()
                    ->where('id', $request->id)
                    ->first();
            } else {
                $notification = Auth::user()->notifications()->where('id', $request->id)->first();
            }
            $notification->markAsRead();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
