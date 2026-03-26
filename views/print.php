<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIODATA - <?php echo htmlspecialchars($fullName); ?></title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="css/views/print.css?v=2.0">
</head>

<body>

    <div class="print-actions">
        <a href="index.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <button onclick="window.print()" class="btn btn-print"><i class="fas fa-print"></i> Print Biodata</button>
    </div>

    <div class="biodata-container">

        <div class="biodata-title">
            <h1>BIODATA</h1>
        </div>

        <div class="biodata-header-section">
            <div class="photo-box">
                <?php if (!empty($profile['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($profile['photo']); ?>" alt="ID Picture">
                <?php else: ?>
                    <div class="photo-placeholder">2x2<br>Picture</div>
                <?php endif; ?>
            </div>
        </div>

        <h3 class="section-title">I. PERSONAL INFORMATION</h3>
        <table class="biodata-table">
            <tr>
                <td class="label">Name:</td>
                <td class="value underline" colspan="3"><strong><?php echo htmlspecialchars($fullName); ?></strong></td>
            </tr>
            <tr>
                <td class="label">Email Address:</td>
                <td class="value underline" colspan="3"><?php echo htmlspecialchars($profile['email']); ?></td>
            </tr>
            <tr>
                <td class="label">Address / Location:</td>
                <td class="value underline" colspan="3">
                    <?php echo htmlspecialchars($profile['location'] ?? '________________________________'); ?></td>
            </tr>
            <tr>
                <td class="label">Date of Birth:</td>
                <td class="value underline">______________________</td>
                <td class="label">Age:</td>
                <td class="value underline">_________</td>
            </tr>
            <tr>
                <td class="label">Place of Birth:</td>
                <td class="value underline" colspan="3">________________________________</td>
            </tr>
            <tr>
                <td class="label">Civil Status:</td>
                <td class="value underline">______________________</td>
                <td class="label">Citizenship:</td>
                <td class="value underline">_________________</td>
            </tr>
            <tr>
                <td class="label">Religion:</td>
                <td class="value underline">______________________</td>
                <td class="label">Height/Weight:</td>
                <td class="value underline">_________________</td>
            </tr>
            <tr>
                <td class="label">Phone No.:</td>
                <td class="value underline" colspan="3">________________________________</td>
            </tr>
        </table>

        <?php if (!empty($profile['about_me'])): ?>
            <h3 class="section-title">II. PROFESSIONAL SUMMARY</h3>
            <p style="margin-top:0.5rem; text-align:justify; line-height:1.6;">
                <?php echo nl2br(htmlspecialchars($profile['about_me'])); ?></p>
        <?php endif; ?>

        <?php if (!empty($skills)): ?>
            <h3 class="section-title">III. SKILLS & EXPERTISE</h3>
            <ul class="skills-list">
                <?php foreach ($skills as $skill): ?>
                    <li><?php echo htmlspecialchars($skill); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (!empty($milestones)): ?>
            <h3 class="section-title">IV. EDUCATIONAL & PROFESSIONAL BACKGROUND</h3>
            <table class="timeline-table">
                <tr>
                    <th style="width:25%;">Date</th>
                    <th style="width:75%;">Title / Description</th>
                </tr>
                <?php foreach ($milestones as $ms): ?>
                    <tr>
                        <td class="timeline-date"><?php echo date("F Y", strtotime($ms['milestone_date'])); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($ms['title']); ?></strong>
                            <?php if (!empty($ms['description'])): ?>
                                <br><span class="timeline-desc"><?php echo nl2br(htmlspecialchars($ms['description'])); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <h3 class="section-title">V. IN CASE OF EMERGENCY, PLEASE NOTIFY:</h3>
        <table class="biodata-table">
            <tr>
                <td class="label" style="width: 15%">Name:</td>
                <td class="value underline" style="width: 85%">
                    ___________________________________________________________</td>
            </tr>
            <tr>
                <td class="label">Address:</td>
                <td class="value underline">___________________________________________________________</td>
            </tr>
            <tr>
                <td class="label">Phone No.:</td>
                <td class="value underline">___________________________________________________________</td>
            </tr>
        </table>

        <div class="signature-section">
            <p>I hereby certify that the above information is true and correct to the best of my knowledge and belief.
            </p>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Applicant's Signature</div>
            </div>
        </div>

    </div>

</body>

</html>