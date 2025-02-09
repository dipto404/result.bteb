<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diploma in Agriculture Result Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74b9ff, #00cec9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        h2 {
            font-weight: 700;
            color: #2d3436;
        }
        .table-container {
            margin-top: 30px;
        }
        .table th {
            background-color: #0984e3;
            color: #fff;
            text-transform: uppercase;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f1f2f6;
        }
        .gpa-label {
            font-weight: bold;
            font-size: 1.5rem;  /* Larger font size for GPA */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .gpa-label.high {
            background-color: #2ecc71;  /* Green */
            color: white;
        }
        .gpa-label.medium {
            background-color: #2ecc71;  /* Green */
            color: white;
        }
        .gpa-label.low {
            background-color: #2ecc71;  /* Green */
            color: white;
        }
        .developer-info {
            text-align: center;
            margin-top: 30px;
            font-size: 16px;
            color: #2d3436;
            font-weight: bold;
        }

        .alert {
            transition: opacity 0.5s ease;
        }

        /* Hiding the title on result page */
        .result-header {
            display: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <?php if (!isset($_GET['roll'])): ?>
            <!-- Input Form will be shown only if 'roll' parameter is not set -->
            <h2 class="text-center">Diploma in Agriculture Result Checker</h2> <!-- This title will show on input page -->
            <form method="GET" class="mt-4">
                <div class="mb-3">
                    <label for="roll" class="form-label">Enter Roll Number:</label>
                    <!-- Changed the input type to 'number' for numeric input -->
                    <input type="number" class="form-control" id="roll" name="roll" placeholder="Enter your roll number" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50">View Result</button>
                </div>
            </form>
        <?php endif; ?>

        <?php
        if (isset($_GET['roll'])) {
            echo "<h2 class='text-center mt-4'>Your Diploma in Agriculture Result</h2>"; // This title will be shown on result page

            $roll = $_GET['roll'];
            $exam = "DIPLOMA+IN+AGRICULTURE";  // নির্ধারিত পরীক্ষার ধরন
            $regulation = "2022";

            $url = "https://btebresultszone.com/api/results/individual?roll=" . urlencode($roll) . "&exam=$exam&regulation=$regulation";

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
                CURLOPT_HTTPHEADER => array(
                    "Host: btebresultszone.com",
                    "sec-ch-ua-platform: \"Android\"",
                    "user-agent: Mozilla/5.0 (Linux; Android 11; Redmi Note 8 Build/RKQ1.201004.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.6834.122 Mobile Safari/537.36",
                    "sec-ch-ua: \"Not A(Brand\";v=\"8\", \"Chromium\";v=\"132\", \"Android WebView\";v=\"132\"",
                    "sec-ch-ua-mobile: ?1",
                    "accept: */*",
                    "x-requested-with: mark.via.gp",
                    "sec-fetch-site: same-origin",
                    "sec-fetch-mode: cors",
                    "sec-fetch-dest: empty",
                    "referer: https://btebresultszone.com/results",
                    "accept-encoding: gzip, deflate, br, zstd",
                    "accept-language: en-US,en;q=0.9",
                    "cookie: _clck=1piggx8%7C2%7Cft5%7C0%7C1861",
                    "cookie: __cf_bm=F7hGRrRxoLf_nJTI.5wV2qTh7Te43gQWhZER0M_nsJk-1738692149-1.0.1.1-SHlHINIWovRk4DPu7Rz6_vxT1xXrhOXbeqvxzD0_6yww3HBf4YpBigDjNGOhp4YZZEF1c2mCFjBDqchtymf0VA",
                    "cookie: _clsk=i84y5h%7C1738692152053%7C1%7C1%7Cw.clarity.ms%2Fcollect",
                    "priority: u=1, i"
                ),
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                echo "<div class='alert alert-danger mt-4'>cURL Error: " . $error . "</div>";
            } else {
                $decodedResponse = json_decode($response, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    if (isset($decodedResponse['semester_results'])) {
                        foreach ($decodedResponse['semester_results'] as $result) {
                            if (isset($result['semester'])) {
                                echo "<h5>Semester: " . $result['semester'] . "</h5>";
                                echo "<table class='table table-striped table-bordered mt-3'>";
                                echo "<thead><tr><th>Exam Date</th><th>GPA</th><th>Referred Subjects</th></tr></thead><tbody>";

                                if (isset($result['exam_results']) && is_array($result['exam_results'])) {
                                    foreach ($result['exam_results'] as $exam) {
                                        $gpa = isset($exam['gpa']) ? $exam['gpa'] : '❌';
                                        $referreds = '';

                                        if (isset($exam['reffereds']) && count($exam['reffereds']) > 0) {
                                            // Iterate through the referred subjects and display their names
                                            $referred_subjects = array_map(function($subject) {
                                                return $subject['subject_name'];  // Assuming subject_name is the key for subject names
                                            }, $exam['reffereds']);
                                            $referreds = implode(", ", $referred_subjects);
                                        } else {
                                            $referreds = 'None';
                                        }

                                        $examDate = isset($exam['date']) ? date("Y-m-d", strtotime($exam['date'])) : 'N/A';

                                        // Apply green color to all GPA
                                        $gpaClass = 'high'; // All GPA results are green

                                        echo "<tr><td>" . $examDate . "</td><td class='gpa-label " . $gpaClass . "'>" . $gpa . "</td><td>" . $referreds . "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No exam results found</td></tr>";
                                }

                                echo "</tbody></table>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-warning mt-4'>No semester results found in the response.</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning mt-4'>Response is not a valid JSON: " . htmlspecialchars($response) . "</div>";
                }
            }
            // Display Developer Info after the results table
            echo "<div class='developer-info'>Developer: 𝗧𝗮𝗻𝘃𝗶𝗿 𝗛𝗼𝘀𝘀𝗲𝗻 𝗗𝗶𝗽𝘁𝗼</div>";
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
