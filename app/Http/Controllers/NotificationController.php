<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $notifications = Auth::user()->notifications;
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
                    return $row->data['link'];
                })
                ->make(true);
        } else if ($request->wantsJson()) {
            $notifications =  Auth::user()->unreadNotifications;
            $data = [];
            foreach ($notifications as $notification) {
                $data[] = [
                    'id' => $notification->id,
                    'judul' => $notification->data['title'],
                    'pesan' => $notification->data['message'],
                    'linkTitle' => $notification->data['linkTitle'],
                    'link' => $notification->data['link'],
                    'read' => $notification->read_at == null ? '1' : '0',
                ];
            }
            return response()->json(['data' => $data]);
        }
        return view('notification.index');
    }

    public function readall()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function read(Request $request)
    {
        try {
            $notification = Auth::user()->notifications()->where('id', $request->id)->first();
            $notification->markAsRead();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
