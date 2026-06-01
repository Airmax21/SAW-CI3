<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Ranking Akhir Semester</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #555;
            font-size: 12px;
            font-weight: bold;
        }

        .meta {
            margin-bottom: 15px;
            width: 100%;
            border-collapse: collapse;
        }

        .meta td {
            font-weight: bold;
            font-size: 11px;
        }

        .table-data {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
        }

        .table-data th {
            background-color: #e5e7eb;
            padding: 8px 6px;
            font-weight: bold;
            border: 1px solid #999;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        .table-data td {
            padding: 8px 6px;
            border: 1px solid #ccc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Hasil Akhir Perangkingan Siswa</h2>
        <p>PAUD Mekar Sari - Metode Simple Additive Weighting (SAW)</p>
    </div>

    <table class="meta">
        <tr>
            <td width="50">Kelas</td>
            <td width="10">:</td>
            <td><?= html_escape($className) ?></td>
            <td width="60" align="right">Periode</td>
            <td width="10" align="right">:</td>
            <td width="120" align="right"><?= date('F Y', strtotime($period)) ?></td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="35" class="text-center">Rank</th>
                <th>Nama Anak Didik</th>
                <?php foreach ($criterias as $crit): ?>
                    <th class="text-center" width="50"><?= html_escape($crit->code) ?></th>
                <?php endforeach; ?>
                <th class="text-right" width="70">Skor Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ranking)): ?>
                <?php foreach ($ranking as $index => $row): ?>
                    <tr>
                        <td class="text-center font-bold"><?= $index + 1 ?></td>
                        <td class="font-bold"><?= html_escape($row->student_name) ?></td>

                        <?php foreach ($criterias as $crit): ?>
                            <?php
                            // Proteksi pengecekan data matriks untuk menghindari Undefined Index di SQLite
                            $score_node = isset($row->matrix[$crit->id]) ? $row->matrix[$crit->id] : null;
                            $normalized_val = 0.00;

                            if (is_array($score_node) && isset($score_node['normalized'])) {
                                $normalized_val = (float) $score_node['normalized'];
                            } elseif (is_object($score_node) && isset($score_node->normalized)) {
                                $normalized_val = (float) $score_node->normalized;
                            }
                            ?>
                            <td class="text-center"><?= number_format($normalized_val, 2) ?></td>
                        <?php endforeach; ?>

                        <td class="text-right font-bold" style="color: #bc267d;"><?= number_format($row->total_score * 100, 1) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($criterias) + 2 ?>" class="text-center" style="padding: 30px;">
                        Tidak ada data penilaian hasil perangkingan pada periode ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>