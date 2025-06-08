<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Models\FeedBackSpk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $feedbackSpk = array_map(function ($key) {
            return FeedBackSpk::where('rating', $key)->where('is_read', false)->count() ?: 0;
        }, array_keys(array_flip(range(1, 5))));

        return view('admin.index', [
            'feedbackRating' => $feedbackSpk,
            'feedbackRatingTotal' => array_sum($feedbackSpk),
        ]);
    }

    /**
     * Get statistics for selected user type
     */
    public function getUserStats(Request $request): JsonResponse
    {
        $userType = $request->input('type');

        if (!in_array($userType, ['mahasiswa', 'dosen', 'admin'])) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        try {
            $stats = $this->calculateStats($userType);
            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error calculating stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch statistics'], 500);
        }
    }

    /**
     * Calculate statistics based on user type
     */
    private function calculateStats(string $userType): array
    {
        // Get base user statistics
        $userStats = User::where('role', $userType)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive
            ')
            ->first();

        $totalUsers = $userStats->total ?? 0;
        $activeUsers = $userStats->active ?? 0;
        $inactiveUsers = $userStats->inactive ?? 0;

        $stats = [
            'type' => ucfirst($userType),
            'total' => $totalUsers,
            'active' => $activeUsers,
            'inactive' => $inactiveUsers,
            'active_percentage' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0,
            'inactive_percentage' => $totalUsers > 0 ? round(($inactiveUsers / $totalUsers) * 100, 1) : 0,
        ];

        // Add verification stats specifically for mahasiswa
        if ($userType === 'mahasiswa') {
            $verificationStats = ProfilMahasiswa::selectRaw('
                COUNT(*) as total_profiles,
                SUM(CASE WHEN verified = 1 THEN 1 ELSE 0 END) as verified,
                SUM(CASE WHEN verified = 0 THEN 1 ELSE 0 END) as unverified
            ')->first();

            $totalProfiles = $verificationStats->total_profiles ?? 0;
            $verifiedCount = $verificationStats->verified ?? 0;
            $unverifiedCount = $verificationStats->unverified ?? 0;

            $stats['verified'] = $verifiedCount;
            $stats['unverified'] = $unverifiedCount;
            $stats['total_profiles'] = $totalProfiles;
            $stats['verified_percentage'] = $totalProfiles > 0 ? round(($verifiedCount / $totalProfiles) * 100, 1) : 0;
            $stats['unverified_percentage'] = $totalProfiles > 0 ? round(($unverifiedCount / $totalProfiles) * 100, 1) : 0;
        }

        return $stats;
    }

    /**
     * Get detailed user list for selected type
     */
    public function getUserList(Request $request): JsonResponse
    {
        $userType = $request->input('type');
        $status = $request->input('status', 'all'); // all, active, inactive

        if (!in_array($userType, ['mahasiswa', 'dosen', 'admin'])) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        try {
            $query = User::where('role', $userType);

            if ($status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === 'inactive') {
                $query->where('is_active', 0);
            }

            // Use left join to get profile data more efficiently
            switch ($userType) {
                case 'mahasiswa':
                    $users = $query->leftJoin('profil_mahasiswa', 'user.user_id', '=', 'profil_mahasiswa.mahasiswa_id')
                        ->select([
                            'user.user_id',
                            'user.username',
                            'user.email',
                            'user.is_active',
                            'user.created_at',
                            'profil_mahasiswa.nama',
                            'profil_mahasiswa.nim',
                            'profil_mahasiswa.verified'
                        ])
                        ->get();
                    break;

                case 'dosen':
                    $users = $query->leftJoin('profil_dosen', 'user.user_id', '=', 'profil_dosen.dosen_id')
                        ->select([
                            'user.user_id',
                            'user.username',
                            'user.email',
                            'user.is_active',
                            'user.created_at',
                            'profil_dosen.nama',
                            'profil_dosen.nip'
                        ])
                        ->get();
                    break;

                case 'admin':
                    $users = $query->leftJoin('profil_admin', 'user.user_id', '=', 'profil_admin.admin_id')
                        ->select([
                            'user.user_id',
                            'user.username',
                            'user.email',
                            'user.is_active',
                            'user.created_at',
                            'profil_admin.nama'
                        ])
                        ->get();
                    break;
            }

            $formattedUsers = $users->map(function ($user) use ($userType) {
                $name = $user->nama ?? 'N/A';
                $identifier = 'N/A';
                $verified = null;

                switch ($userType) {
                    case 'mahasiswa':
                        $identifier = $user->nim ?? 'N/A';
                        $verified = $user->verified ?? 0;
                        break;
                    case 'dosen':
                        $identifier = $user->nip ?? 'N/A';
                        break;
                    case 'admin':
                        $identifier = $user->username;
                        break;
                }

                return [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'name' => $name,
                    'identifier' => $identifier,
                    'is_active' => (bool) $user->is_active,
                    'verified' => $verified,
                    'created_at' => \Carbon\Carbon::parse($user->created_at)->format('d/m/Y'),
                ];
            });

            return response()->json(['users' => $formattedUsers]);
        } catch (\Exception $e) {
            \Log::error('Error fetching user list: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch user list'], 500);
        }
    }
}