<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXAD Exercise - Chidi</title>
    <link rel="stylesheet" href="./views/assets/style.css">
</head>

<body>
    <form action="" method="post">
        <input type="datetime-local" name="date_range" min="<?php echo date('Y-m-d\TH:i'); ?>">
        <select name="tv_series" id="">
            <option default>Show all</option>
            <?php foreach ($series->tvSeriesNames as $series_title) : ?>
                <option value="<?php echo htmlspecialchars($series_title['title']); ?>">
                    <?php echo htmlspecialchars($series_title['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    <?php if ($notification != "") :; ?>
        <div id="notification" class="notification">
            <?php echo htmlspecialchars($notification); ?>
        </div>
    <?php endif;  ?>

    <?php if (!empty($allSeries)) : ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Week Day - Show Time</th>
                <th>Next Date</th>
            </tr>

            <?php foreach ($allSeries as $seriesDetail) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($seriesDetail['title']); ?></td>
                    <td><?php echo htmlspecialchars($seriesDetail['week_day'] . ' - ' . $seriesDetail['show_time']); ?></td>
                    <td><?php echo htmlspecialchars($seriesDetail['next_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <div class="no-movie">
            NO MOVIE SCHEDULE FOUND!
        </div>
    <?php endif; ?>
    <script src="./views/assets/script.js"></script>
</body>

</html>