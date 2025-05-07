<?php

namespace App\Services;

use App\Models\KeahlianLowongan;
use App\Models\LowonganMagang;
use App\Models\ProfilMahasiswa;
use Illuminate\Support\Facades\Log;

// TOPSIS (Technique for Order Preference by Similarity to Ideal Solution) 
class SPKService
{
    public static function getRecommendations($userId)
    {
        $profilMahasiswa = ProfilMahasiswa::where('mahasiswa_id', $userId)
            ->with('user', 'programStudi', 'preferensiMahasiswa', 'pengalamanMahasiswa', 'keahlianMahasiswa')
            ->first();

        $lowonganMagang = LowonganMagang::with(['lokasi', 'persyaratanMagang', 'keahlianLowongan'])->get();

        $dataMahasiswa = (object) [
            'ipk' => $profilMahasiswa->ipk,
            'keahlian' => $profilMahasiswa->keahlianMahasiswa->map(function ($keahlian) {
                return (object) [
                    'keahlian_id' => $keahlian->keahlian_id,
                    'tingkat_kemampuan' => $keahlian->tingkatKemampuanIndex(),
                ];
            })->toArray(),
            'preferensi' => (object) [
                'posisi_preferensi' => explode(', ', $profilMahasiswa->preferensiMahasiswa->posisi_preferensi),
                'tipe_kerja_preferensi' => explode(', ', $profilMahasiswa->preferensiMahasiswa->tipe_kerja_preferensi),
                'lokasi_id' => $profilMahasiswa->preferensiMahasiswa->lokasi_id,
            ],
            'pengalaman' => $profilMahasiswa->pengalamanMahasiswa->map(function ($pengalaman) {
                return (object) [
                    'tipe_pengalaman' => $pengalaman->tipe_pengalaman,
                    'tag' => $pengalaman->pengalamanTag->map(function ($tag) {
                        return $tag->keahlian->keahlian_id;
                    })->toArray(),
                ];
            })->toArray(),
        ];

        $kriteriaMagang = [];
        foreach ($lowonganMagang as $lowongan) {
            $kriteriaMagang[] = (object) [
                'id' => $lowongan->id,
                'min_ipk' => $lowongan->persyaratanMagang->minimum_ipk,
                'keahlian' => $lowongan->keahlianLowongan->map(function ($keahlianLowongan) {
                    return (object) [
                        'keahlian_id' => $keahlianLowongan->keahlian_id,
                        'kemampuan_minimum' => $keahlianLowongan->kemampuanMinimumIndex(),
                    ];
                })->toArray(),
                'posisi' => $lowongan->judul_posisi,
                'remote' => $lowongan->opsi_remote,
                'lokasi_id' => $lowongan->lokasi_id,
                'pengalaman' => $lowongan->persyaratanMagang->pengalaman,
                'lowongan' => $lowongan // Keep original 
            ];
        }

        return self::calculateTopsisRanking($dataMahasiswa, $kriteriaMagang);
    }

    private static function calculateTopsisRanking($mahasiswa, $jobs)
    {
        $decisionMatrix = self::createDecisionMatrix($mahasiswa, $jobs);
        $normalizedMatrix = self::normalizeMatrix($decisionMatrix);
        $idealSolution = self::getIdealSolution($normalizedMatrix);
        $antiIdealSolution = self::getAntiIdealSolution($normalizedMatrix);

        $results = [];
        foreach ($normalizedMatrix as $index => $values) {
            $sPlus = self::calculateDistance($values, $idealSolution);
            $sMinus = self::calculateDistance($values, $antiIdealSolution);
            
            $results[] = [
                'lowongan' => $jobs[$index]->lowongan,
                'score' => $sMinus / ($sPlus + $sMinus)
            ];
        }

        // descending
        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $results;
    }

    private static function createDecisionMatrix($mahasiswa, $jobs)
    {
        $matrix = [];
        
        foreach ($jobs as $job) {
            $matrix[] = [
                self::calculateIpkMatch($mahasiswa->ipk, $job->min_ipk),
                self::calculateSkillMatch($mahasiswa->keahlian, $job->keahlian),
                self::calculateExperienceMatch(count($mahasiswa->pengalaman), $job->pengalaman),
                self::calculateLocationMatch($mahasiswa->preferensi->lokasi_id, $job->lokasi_id),
                self::calculatePositionMatch($mahasiswa->preferensi->posisi_preferensi, $job->posisi),
                self::calculateWorkTypeMatch($mahasiswa->preferensi->tipe_kerja_preferensi, $job->remote)
            ];
        }

        return $matrix;
    }

    private static function calculateIpkMatch($mahasiswaIpk, $jobMinIpk)
    {
        return (float) $mahasiswaIpk >= (float) $jobMinIpk ? 1 : 0;
    }

    private static function calculateSkillMatch($mahasiswaSkills, $requiredSkills)
    {
        $matched = 0;
        foreach ($requiredSkills as $required) {
            foreach ($mahasiswaSkills as $mahasiswaSkill) {
                if ($mahasiswaSkill->keahlian_id == $required->keahlian_id && 
                    $mahasiswaSkill->tingkat_kemampuan >= $required->kemampuan_minimum) {
                    $matched++;
                    break;
                }
            }
        }
        
        return count($requiredSkills) > 0 ? $matched / count($requiredSkills) : 0;
    }

    private static function calculateExperienceMatch($mahasiswaExperienceCount, $jobRequiredExperience)
    {
        return $mahasiswaExperienceCount >= $jobRequiredExperience ? 1 : 0;
    }

    private static function calculateLocationMatch($mahasiswaLocationId, $jobLocationId)
    {
        return $mahasiswaLocationId == $jobLocationId ? 1 : 0;
    }

    private static function calculatePositionMatch($mahasiswaPreferredPositions, $jobPosition)
    {
        return in_array($jobPosition, $mahasiswaPreferredPositions) ? 1 : 0;
    }

    private static function calculateWorkTypeMatch($mahasiswaWorkPreferences, $jobWorkType)
    {
        return in_array($jobWorkType, $mahasiswaWorkPreferences) ? 1 : 0;
    }

    private static function normalizeMatrix($matrix)
    {
        if (empty($matrix)) return [];
        
        $columns = count($matrix[0]);
        $sumSquares = array_fill(0, $columns, 0);

        foreach ($matrix as $row) {
            foreach ($row as $i => $val) {
                $sumSquares[$i] += $val ** 2;
            }
        }

        return array_map(function ($row) use ($sumSquares) {
            return array_map(function ($val, $index) use ($sumSquares) {
                return $sumSquares[$index] ? $val / sqrt($sumSquares[$index]) : 0;
            }, $row, array_keys($row));
        }, $matrix);
    }

    private static function getIdealSolution($matrix)
    {
        return array_map('max', array_map(null, ...$matrix));
    }

    private static function getAntiIdealSolution($matrix)
    {
        return array_map('min', array_map(null, ...$matrix));
    }

    private static function calculateDistance($point, $solution)
    {
        $sum = 0;
        foreach ($point as $i => $val) {
            $sum += ($val - $solution[$i]) ** 2;
        }
        return sqrt($sum);
    }
}