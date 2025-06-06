<?php

namespace App\Services;

use App\Models\BobotSPK;
use App\Models\LowonganMagang;
use App\Models\ProfilMahasiswa;


// TOPSIS (Technique for Order Preference by Similarity to Ideal Solution) 
class SPKService
{
    public static function getRecommendations($userId, $weights = null, $useDump = false)
    {
        if ($weights == null) {
            $weights = BobotSPK::pluck('bobot', 'jenis_bobot')->toArray();
        }
        $weights = [
            $weights['IPK'],
            $weights['keahlian'],
            $weights['pengalaman'],
            $weights['jarak'],
            $weights['posisi'],
        ];

        $profilMahasiswa = ProfilMahasiswa::where('mahasiswa_id', $userId)
            ->with('user', 'programStudi', 'preferensiMahasiswa', 'pengalamanMahasiswa', 'keahlianMahasiswa')
            ->first();

        $lowonganMagang = LowonganMagang::where('is_active', true)
            ->whereHas('perusahaanMitra', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('persyaratanMagang')
            ->with(['lokasi', 'persyaratanMagang', 'keahlianLowongan'])
            ->get();

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
                'lokasi' => $profilMahasiswa->preferensiMahasiswa->lokasi,
            ],
            'pengalaman' => $profilMahasiswa->pengalamanMahasiswa->map(function ($pengalaman) {
                return (object) [
                    'tipe_pengalaman' => $pengalaman->tipe_pengalaman,
                    'keahlian' => $pengalaman->pengalamanTag->map(function ($tag) {
                        return (object) [
                            'keahlian_id' => $tag->keahlian->keahlian_id,
                            'tingkat_kemampuan' => 10, // biar score bisa bernilai 1
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ];

        if ($useDump) {
            dump('DATA MAHASISWA');
            dump($dataMahasiswa);
        }

        $alternatifMagang = [];
        foreach ($lowonganMagang as $lowongan) {
            $alternatifMagang[] = (object) [
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
                'lokasi' => $lowongan->lokasi,
                'pengalaman' => $lowongan->persyaratanMagang->pengalaman,
                'lowongan' => $lowongan // Keep original 
            ];
        }

        // dump($dataMahasiswa, $alternatifMagang);

        return self::calculateTopsisRanking($dataMahasiswa, $alternatifMagang, $weights, $useDump);
    }

    private static function calculateTopsisRanking($mahasiswa, $jobs, $weights, $useDump = false)
    {
        $costAttributes = [3];

        $decisionMatrix = self::createDecisionMatrix($mahasiswa, $jobs);
        $normalizedMatrix = self::normalizeMatrix($decisionMatrix);
        $weightedMatrix = self::applyWeights($normalizedMatrix, $weights);
        $idealSolution = self::getIdealSolution($weightedMatrix, $costAttributes);
        $antiIdealSolution = self::getAntiIdealSolution($weightedMatrix, $costAttributes);

        if ($useDump) {
            dump('DECISION MATRIX');
            dump($decisionMatrix);
            dump('NORMALIZED MATRIX');
            dump($normalizedMatrix);
            dump('WEIGHTED MATRIX');
            dump($weightedMatrix);
            dump('IDEAL SOLUTION');
            dump($idealSolution);
            dump('ANTI IDEAL SOLUTION');
            dump($antiIdealSolution);
        }

        $results = [];
        foreach ($weightedMatrix as $index => $values) {
            $sPlus = self::calculateDistance($values, $idealSolution);
            $sMinus = self::calculateDistance($values, $antiIdealSolution);

            $score = $sMinus / ($sPlus + $sMinus);

            if ($useDump) {
                dump([
                    'index' => $index,
                    'S+' => $sPlus,
                    'S-' => $sMinus,
                    'SCORE' => $score
                ]);
            }

            $results[] = [
                'lowongan' => $jobs[$index]->lowongan,
                'score' =>  $score
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
                self::calculateExperienceMatch($mahasiswa->pengalaman, $job->pengalaman, $job->keahlian),
                self::calculateLocation($mahasiswa->preferensi->lokasi, $job->lokasi),
                self::calculatePositionMatch($mahasiswa->preferensi->posisi_preferensi, $job->posisi),
            ];
        }

        $highestLocation = 0;
        foreach ($matrix as $values) {
            $highestLocation = max($highestLocation, $values[3]);
        }

        foreach ($matrix as $index => $values) {
            $matrix[$index][3] = $values[3] / $highestLocation;
        }

        return $matrix;
    }

    private static function applyWeights($matrix, $weights)
    {
        $weighted = [];
        foreach ($matrix as $row) {
            $weightedRow = [];
            foreach ($row as $i => $val) {
                $weightedRow[] = $val * $weights[$i];
            }
            $weighted[] = $weightedRow;
        }

        return $weighted;
    }

    private static function calculateIpkMatch($mahasiswaIpk, $jobMinIpk)
    {
        return $mahasiswaIpk >= (float) $jobMinIpk ? 1 : min(1, ((float) $mahasiswaIpk / (float) $jobMinIpk) * 0.25);
    }

    private static function calculateSkillMatch($mahasiswaSkills, $requiredSkills)
    {
        $matched = 0;
        foreach ($requiredSkills as $required) {
            foreach ($mahasiswaSkills as $mahasiswaSkill) {
                if (
                    $mahasiswaSkill->keahlian_id == $required->keahlian_id &&
                    $mahasiswaSkill->tingkat_kemampuan >= $required->kemampuan_minimum
                ) {
                    $matched++;
                    break;
                } else if (
                    $mahasiswaSkill->keahlian_id == $required->keahlian_id &&
                    $mahasiswaSkill->tingkat_kemampuan > 0 && $required->kemampuan_minimum > 0
                ) {
                    $matched += min(1, ($mahasiswaSkill->tingkat_kemampuan / $required->kemampuan_minimum));
                    break;
                }
            }
        }
        $toReturn = count($requiredSkills) > 0 ? $matched / count($requiredSkills) : 0;
        if ($toReturn == 0) {
            $toReturn = 0.01 / count($requiredSkills);
        };
        return $toReturn;
    }

    private static function calculateExperienceMatch($mahasiswaExperience, $jobRequiredExperience, $requiredSkills)
    {
        if (empty($mahasiswaExperience)) {
            return (0.001 / count($requiredSkills)) * 0.5;
        };
        $tagMatch = 0;
        $haveWorkExperience = false;
        foreach ($mahasiswaExperience as $experience) {
            if (!$haveWorkExperience && $experience->tipe_pengalaman == 'kerja') {
                $haveWorkExperience = true;
            }
            $tagMatch += self::calculateSkillMatch($experience->keahlian, $requiredSkills);
        }
        $tagMatch /= count($mahasiswaExperience);
        $score = $jobRequiredExperience && $haveWorkExperience ? 0.5 : 0;
        $score += $tagMatch * 0.5;
        return $score;
    }

    private static function calculateLocation($mahasiswaLocationId, $jobLocationId)
    {
        return LocationService::haversineDistance(
            $mahasiswaLocationId->latitude,
            $mahasiswaLocationId->longitude,
            $jobLocationId->latitude,
            $jobLocationId->longitude
        );
    }

    private static function calculatePositionMatch($mahasiswaPreferredPositions, $jobPosition)
    {
        $highestPercent = 0;
        foreach ($mahasiswaPreferredPositions as $position) {
            $str1 = strtolower($jobPosition);
            $str2 = strtolower($position);
            $distance = levenshtein($str1, $str2);
            $similarity = 1 - ($distance / max(strlen($str1), strlen($str2)));
            if ($similarity > $highestPercent) {
                $highestPercent = $similarity;
            }
        }

        return $highestPercent;
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

    private static function getIdealSolution($matrix, $costIndex)
    {
        $ideal = [];
        $columns = count($matrix[0]);
        for ($i = 0; $i < $columns; $i++) {
            $column = array_column($matrix, $i);
            $ideal[] = in_array($i, $costIndex) ? min($column) : max($column);
        }
        return $ideal;
    }

    private static function getAntiIdealSolution($matrix,  $costIndex)
    {
        $antiIdeal = [];
        $columns = count($matrix[0]);
        for ($i = 0; $i < $columns; $i++) {
            $column = array_column($matrix, $i);
            $antiIdeal[] = in_array($i, $costIndex) ? max($column) : min($column);
        }

        return $antiIdeal;
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
