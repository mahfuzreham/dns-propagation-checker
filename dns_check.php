<?php

/**
 * DNS Propagation Check Script with Search Box and Result Table
 * 
 * This script allows users to input a domain name and checks DNS propagation for the given domain.
 * 
 * @param string $domain The domain name to check.
 * @param array $recordTypes The DNS record types to check (e.g., A, AAAA, CNAME, MX, NS, TXT).
 * @return array An array containing the results of the DNS queries.
 */
function checkDnsPropagation($domain, $recordTypes = ['A', 'AAAA', 'CNAME', 'MX', 'NS', 'TXT']) {
    $results = [];

    foreach ($recordTypes as $type) {
        $records = dns_get_record($domain, constant('DNS_' . $type));

        if ($records !== false) {
            $results[$type] = $records;
        } else {
            $results[$type] = "No records found for $type.";
        }
    }

    return $results;
}

// Check if a domain was submitted via POST
$domain = isset($_POST['domain']) ? trim($_POST['domain']) : null;
$dnsResults = $domain ? checkDnsPropagation($domain) : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS Propagation Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
            margin: 0;
        }
        h2 {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .result-table th, .result-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .result-table th {
            background-color: #4CAF50;
            color: white;
        }
        .no-records {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>DNS Propagation Check</h2>

    <form method="post" action="">
        <label for="domain">Enter Domain Name:</label>
        <input type="text" id="domain" name="domain" placeholder="example.com" required>
        <input type="submit" value="Check DNS">
    </form>

    <?php if ($dnsResults): ?>
        <h2>Results for <?php echo htmlspecialchars($domain); ?></h2>
        <?php foreach ($dnsResults as $type => $records): ?>
            <table class="result-table">
                <thead>
                    <tr>
                        <th><?php echo htmlspecialchars($type); ?> Records</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($records)): ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($type); ?></td>
                                <td><pre><?php echo htmlspecialchars(print_r($record, true)); ?></pre></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="no-records"><?php echo htmlspecialchars($records); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
