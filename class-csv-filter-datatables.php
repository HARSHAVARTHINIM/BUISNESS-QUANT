<?php
class CSV_Filter_DataTables {
    public static function render() {
        $data = CSV_Data::get_data();

        if (!$data) {
            echo '<p>No data found in the CSV file.</p>';
            return;
        }

        $headers = $data[0];
        $filters = self::get_filters($data);

        ?>
        <div id="csv-filter-datatables">
            <div id="filter-container" class="filters">
                <div class="filter-group">
                    <label for="ticker-filter">Ticker Filter:</label>
                    <select class="filter" id="ticker-filter" data-column="1">
                        <option value="">Select Ticker</option>
                        <?php foreach ($filters[1] as $value) : ?>
                            <option value="<?php echo esc_attr(trim($value)); ?>"><?php echo esc_html(trim($value)); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="individual-search-button" data-filter="ticker-filter">Search</button>
                </div>
                <div class="filter-group">
                    <label for="sector-filter">Sector Filter:</label>
                    <select class="filter" id="sector-filter" data-column="2">
                        <option value="">Select Sector</option>
                        <?php foreach ($filters[2] as $value) : ?>
                            <option value="<?php echo esc_attr(trim($value)); ?>"><?php echo esc_html(trim($value)); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="individual-search-button" data-filter="sector-filter">Search</button>
                </div>
                <button id="add-filter-button">Add Filter</button>
            </div>
            <table id="csv-table" class="display">
                <thead>
                    <tr>
                        <?php foreach ($headers as $header) : ?>
                            <th><?php echo esc_html($header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 1; $i < count($data); $i++) : ?>
                        <tr>
                            <?php foreach ($data[$i] as $value) : ?>
                                <td><?php echo esc_html($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
        <script>
            var filterData = <?php echo json_encode($filters); ?>;
            var headers = <?php echo json_encode($headers); ?>;
        </script>
        <?php
    }

    private static function get_filters($data) {
        $filters = [];

        foreach ($data[0] as $columnIndex => $columnName) {
            $filters[$columnIndex] = [];

            for ($i = 1; $i < count($data); $i++) {
                $filters[$columnIndex][] = $data[$i][$columnIndex];
            }

            $filters[$columnIndex] = array_unique($filters[$columnIndex]);
            sort($filters[$columnIndex]);
        }

        return $filters;
    }
}
?>
