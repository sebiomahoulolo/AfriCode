<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfriCode - Tableau de Bord Administrateur</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --secondary-color: #2ecc71;
            --secondary-dark: #27ae60;
            --accent-color: #f39c12;
            --accent-dark: #e67e22;
            --danger-color: #e74c3c;
            --danger-dark: #c0392b;
            --warning-color: #f1c40f;
            --warning-dark: #f39c12;
            --success-color: #2ecc71;
            --dark-color: #2c3e50;
            --dark-color-light: #34495e;
            --light-color: #ecf0f1;
            --text-color: #333;
            --text-light: #7f8c8d;
            --border-radius: 8px;
            --card-border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--text-color);
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: var(--dark-color);
            color: white;
            padding: 20px 0;
            transition: var(--transition);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--primary-color);
            letter-spacing: 1px;
        }

        .tagline {
            font-size: 12px;
            color: var(--light-color);
            font-style: italic;
        }

        .admin-info {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
        }

        .admin-name {
            font-weight: bold;
            font-size: 16px;
        }

        .admin-role {
            font-size: 12px;
            color: var(--accent-color);
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .menu-category {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-light);
            padding: 15px 20px 5px;
            letter-spacing: 1px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background-color: var(--primary-color);
            border-left: 4px solid var(--accent-color);
        }

        .menu-item.active::before {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--secondary-color);
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .badge {
            background-color: var(--danger-color);
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 10px;
            margin-left: auto;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 260px;
            transition: var(--transition);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: white;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--dark-color);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: var(--text-light);
        }

        .breadcrumb i {
            margin: 0 8px;
            font-size: 12px;
        }

        .breadcrumb span:last-child {
            color: var(--primary-color);
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .header-actions button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: var(--transition);
        }

        .header-actions button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        .header-actions button.add-btn {
            background-color: var(--secondary-color);
        }

        .header-actions button.add-btn:hover {
            background-color: var(--secondary-dark);
        }

        .header-actions button.export-btn {
            background-color: var(--accent-color);
        }

        .header-actions button.export-btn:hover {
            background-color: var(--accent-dark);
        }

        .header-actions button i {
            margin-right: 8px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .quick-stat {
            background: linear-gradient(135deg, #f6f8fb 0%, #f0f4f7 100%);
            border-radius: var(--card-border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
        }

        .quick-stat:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .quick-stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: white;
            font-size: 24px;
        }

        .stat-users {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-left-color: var(--primary-color);
        }

        .stat-courses {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-dark) 100%);
            border-left-color: var(--secondary-color);
        }

        .stat-revenue {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-dark) 100%);
            border-left-color: var(--accent-color);
        }

        .stat-certifications {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border-left-color: #9b59b6;
        }

        .quick-stat-info {
            flex: 1;
        }

        .quick-stat-title {
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .quick-stat-value {
            font-size: 28px;
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .quick-stat-change {
            display: flex;
            align-items: center;
            font-size: 12px;
        }

        .stat-up {
            color: var(--success-color);
        }

        .stat-down {
            color: var(--danger-color);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: var(--card-border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .stat-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .stat-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .users-icon {
            background-color: var(--primary-color);
        }

        .courses-icon {
            background-color: var(--secondary-color);
        }

        .revenue-icon {
            background-color: var(--accent-color);
        }

        .tasks-icon {
            background-color: var(--danger-color);
        }

        .stat-card-value {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .stat-card-desc {
            font-size: 14px;
            color: var(--text-light);
            display: flex;
            align-items: center;
        }

        .stat-card-desc i {
            margin-right: 5px;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background-color: white;
            border-radius: var(--card-border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--hover-shadow);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--dark-color);
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .card-actions select, .card-actions button {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .card-actions select:hover, .card-actions button:hover {
            border-color: var(--primary-color);
        }

        .card-actions button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            display: flex;
            align-items: center;
        }

        .card-actions button i {
            margin-right: 5px;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        tbody tr {
            transition: var(--transition);
        }

        tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .table-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
        }

        .status-active {
            background-color: rgba(46, 204, 113, 0.15);
            color: var(--secondary-color);
        }

        .status-pending {
            background-color: rgba(241, 196, 15, 0.15);
            color: var(--warning-color);
        }

        .status-inactive {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger-color);
        }

        .status-completed {
            background-color: rgba(52, 152, 219, 0.15);
            color: var(--primary-color);
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
            transition: var(--transition);
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .action-btn.edit {
            background-color: var(--primary-color);
            color: white;
        }

        .action-btn.delete {
            background-color: var(--danger-color);
            color: white;
        }

        .action-btn.view {
            background-color: var(--accent-color);
            color: white;
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .search-box button {
            padding: 12px 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .search-box button:hover {
            background-color: var(--primary-dark);
        }

        .search-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-tag {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .filter-tag i {
            margin-left: 5px;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .pagination-info {
            font-size: 14px;
            color: var(--text-light);
        }

        .pagination-controls {
            display: flex;
            gap: 5px;
        }

        .pagination-controls button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .pagination-controls button:hover {
            border-color: var(--primary-color);
        }

        .pagination-controls button.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }

        .user-item:hover {
            background-color: rgba(52, 152, 219, 0.05);
            padding-left: 10px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
            font-size: 18px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--dark-color);
        }

        .user-email {
            font-size: 13px;
            color: var(--text-light);
        }

        .user-action {
            display: flex;
            gap: 5px;
        }

        .progress-container {
            width: 100%;
            background-color: #eee;
            border-radius: 10px;
            height: 8px;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background-color: var(--primary-color);
        }

        .calendar {
            border-collapse: collapse;
            width: 100%;
        }

        .calendar th, .calendar td {
            padding: 10px;
            text-align: center;
        }

        .calendar th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        .calendar td {
            cursor: pointer;
            border-radius: 5px;
            transition: var(--transition);
        }

        .calendar td:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .calendar .today {
            background-color: rgba(52, 152, 219, 0.2);
            font-weight: bold;
            color: var(--primary-color);
        }

        .calendar .event {
            position: relative;
        }

        .calendar .event::after {
            content: "";
            position: absolute;
            bottom: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background-color: var(--accent-color);
            border-radius: 50%;
        }

        .task-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }

        .task-item:hover {
            background-color: rgba(52, 152, 219, 0.05);
            padding-left: 10px;
        }

        .task-checkbox {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .task-info {
            flex: 1;
        }

        .task-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-color);
        }

        .task-date {
            font-size: 13px;
            color: var(--text-light);
            display: flex;
            align-items: center;
        }

        .task-date i {
            margin-right: 5px;
            font-size: 12px;
        }

        .task-priority {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .priority-high {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger-color);
        }

        .priority-medium {
            background-color: rgba(241, 196, 15, 0.15);
            color: var(--warning-color);
        }

        .priority-low {
            background-color: rgba(46, 204, 113, 0.15);
            color: var(--secondary-color);
        }

        /* Course cards styling */
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .course-card {
            background-color: white;
            border-radius: var(--card-border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .course-img {
            height: 160px;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
        }

        .course-content {
            padding: 20px;
        }

        .course-tags {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .course-tag {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
        }

        .course-tag.premium {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        .course-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .course-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .course-stats {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .course-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .course-stat-value {
            font-weight: bold;
            color: var(--dark-color);
            font-size: 16px;
        }

        .course-stat-label {
            font-size: 12px;
            color: var(--text-light);
        }

        /* Payment section */
        .payment-list {
            margin-top: 15px;
        }

        .payment-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .payment-user {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .payment-info {
            flex: 1;
        }

        .payment-course {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .payment-date {
            font-size: 12px;
            color: var(--text-light);
        }

        .payment-amount {
            font-weight: bold;
            color: var(--success-color);
        }

        /* Notifications */
        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }

        .notification-item:hover {
            background-color: rgba(52, 152, 219, 0.05);
            padding-left: 10px;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }

        .icon-warning {
            background-color: var(--warning-color);
        }

        .icon-success {
            background-color: var(--success-color);
        }

        .icon-info {
            background-color: var(--primary-color);
        }

        .icon-danger {
            background-color: var(--danger-color);
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-time {
            font-size: 12px;
            color: var(--text-light);
        }

        .notification-actions {
            text-align: right;
        }

        .notification-badge {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--primary-color);
            margin-left: 5px;
        }

        /* Floating action button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: var(--transition);
            z-index: 100;
        }

        .fab:hover {
            background-color: var(--primary-dark);
            transform: scale(1.1);
        }

        .fab i {
            font-size: 24px;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: white;
            border-radius: 8px;
            padding: 15px 20px;
            box-/* Complétion du style toast */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: white;
    border-radius: 8px;
    padding: 15px 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    z-index: 1000;
    min-width: 300px;
    animation: slideIn 0.3s ease-out forwards;
}

.toast-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    flex-shrink: 0;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.toast-message {
    font-size: 14px;
    color: var(--text-light);
}

.toast-close {
    margin-left: 15px;
    cursor: pointer;
    color: var(--text-light);
    transition: var(--transition);
}

.toast-close:hover {
    color: var(--dark-color);
}

.toast-success .toast-icon {
    background-color: var(--success-color);
}

.toast-warning .toast-icon {
    background-color: var(--warning-color);
}

.toast-error .toast-icon {
    background-color: var(--danger-color);
}

.toast-info .toast-icon {
    background-color: var(--primary-color);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Modal styles */
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-backdrop.active {
    opacity: 1;
    visibility: visible;
}

.modal {
    background-color: white;
    border-radius: var(--card-border-radius);
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    transform: translateY(-50px);
    transition: all 0.3s ease;
}

.modal-backdrop.active .modal {
    transform: translateY(0);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 20px;
    font-weight: bold;
    color: var(--dark-color);
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--text-light);
    transition: var(--transition);
}

.modal-close:hover {
    color: var(--danger-color);
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.modal-btn {
    padding: 10px 15px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
}

.modal-btn-primary {
    background-color: var(--primary-color);
    color: white;
    border: none;
}

.modal-btn-primary:hover {
    background-color: var(--primary-dark);
}

.modal-btn-secondary {
    background-color: transparent;
    border: 1px solid #ddd;
}

.modal-btn-secondary:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Form styles */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--dark-color);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: p 0 0 3px rgba(52, 152, 219, 0.2);
}

.form-select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 15px;
}

.form-check {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.form-check-input {
    margin-right: 10px;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 992px) {
    .sidebar {
        width: 80px;
        overflow: hidden;
    }

    .sidebar-header, .admin-info, .menu-category {
        text-align: center;
        padding: 10px;
    }

    .logo {
        font-size: 20px;
    }

    .tagline, .admin-name, .admin-role {
        display: none;
    }

    .admin-avatar {
        margin: 0 auto;
    }

    .menu-item span {
        display: none;
    }

    .menu-item {
        justify-content: center;
        padding: 15px 0;
    }

    .menu-item i {
        margin: 0;
    }

    .badge {
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .main-content {
        margin-left: 80px;
    }
}

@media (max-width: 768px) {
    .quick-stats, .stats-container {
        grid-template-columns: 1fr;
    }

    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .breadcrumb {
        margin-bottom: 10px;
    }

    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .course-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .card-actions {
        width: 100%;
    }

    .search-box {
        flex-direction: column;
    }
}
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">AfriCode</div>
                <div class="tagline">Propulsez l'Afrique par le code</div>
            </div>
            
            <div class="admin-info">
                <div class="admin-avatar">SK</div>
                <div>
                    <div class="admin-name">Samuel Kouassi</div>
                    <div class="admin-role">Super Admin</div>
                </div>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-category">Principal</div>
                <div class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                    <span class="badge">15</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Cours</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-certificate"></i>
                    <span>Certifications</span>
                </div>
                
                <div class="menu-category">Gestion</div>
                <div class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Statistiques</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Paiements</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                    <span class="badge">8</span>
                </div>
                
                <div class="menu-category">Configuration</div>
                <div class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </div>
                <div class="menu-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="dashboard-header">
                <div>
                    <h1 class="page-title">Tableau de Bord</h1>
                    <div class="breadcrumb">
                        <span>Accueil</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Tableau de Bord</span>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="add-btn">
                        <i class="fas fa-plus"></i> Ajouter un cours
                    </button>
                    <button class="export-btn">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="quick-stat">
                    <div class="quick-stat-icon stat-users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="quick-stat-info">
                        <div class="quick-stat-title">Utilisateurs Totaux</div>
                        <div class="quick-stat-value">2,845</div>
                        <div class="quick-stat-change stat-up">
                            <i class="fas fa-arrow-up"></i> 12.5% depuis le mois dernier
                        </div>
                    </div>
                </div>
                
                <div class="quick-stat">
                    <div class="quick-stat-icon stat-courses">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="quick-stat-info">
                        <div class="quick-stat-title">Cours Actifs</div>
                        <div class="quick-stat-value">78</div>
                        <div class="quick-stat-change stat-up">
                            <i class="fas fa-arrow-up"></i> 5.3% depuis le mois dernier
                        </div>
                    </div>
                </div>
                
                <div class="quick-stat">
                    <div class="quick-stat-icon stat-revenue">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="quick-stat-info">
                        <div class="quick-stat-title">Revenus Mensuels</div>
                        <div class="quick-stat-value">5,245,000 FCFA</div>
                        <div class="quick-stat-change stat-up">
                            <i class="fas fa-arrow-up"></i> 8.7% depuis le mois dernier
                        </div>
                    </div>
                </div>
                
                <div class="quick-stat">
                    <div class="quick-stat-icon stat-certifications">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="quick-stat-info">
                        <div class="quick-stat-title">Certifications Délivrées</div>
                        <div class="quick-stat-value">1,287</div>
                        <div class="quick-stat-change stat-down">
                            <i class="fas fa-arrow-down"></i> 2.1% depuis le mois dernier
                        </div>
                    </div>
                </div>
            </div>
            
            
                    <!-- Recent Students -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-user-graduate"></i> Nouveaux Étudiants
                            </div>
                            <div class="card-actions">
                                <button><i class="fas fa-eye"></i> Voir Tous</button>
                            </div>
                        </div>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Cours</th>
                                        <th>Date d'inscription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <div class="user-avatar">AM</div>
                                                <div>
                                                    <div style="font-weight: bold;">Aminata Diallo</div>
                                                    <div style="font-size: 12px; color: var(--text-light);">aminata@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Introduction au Développement Web</td>
                                        <td>12 Avril 2025</td>
                                        <td><span class="status-badge status-active">Actif</span></td>
                                        <td>
                                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <div class="user-avatar">JK</div>
                                                <div>
                                                    <div style="font-weight: bold;">Jean Konan</div>
                                                    <div style="font-size: 12px; color: var(--text-light);">jean@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Python pour Data Science</td>
                                        <td>10 Avril 2025</td>
                                        <td><span class="status-badge status-pending">En attente</span></td>
                                        <td>
                                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <div class="user-avatar">SF</div>
                                                <div>
                                                    <div style="font-weight: bold;">Sekou Fofana</div>
                                                    <div style="font-size: 12px; color: var(--text-light);">sekou@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>React.js Avancé</td>
                                        <td>8 Avril 2025</td>
                                        <td><span class="status-badge status-active">Actif</span></td>
                                        <td>
                                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <div class="user-avatar">FS</div>
                                                <div>
                                                    <div style="font-weight: bold;">Fatou Sarr</div>
                                                    <div style="font-size: 12px; color: var(--text-light);">fatou@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>UX/UI Design Fondamentaux</td>
                                        <td>5 Avril 2025</td>
                                        <td><span class="status-badge status-inactive">Inactif</span></td>
                                        <td>
                                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                            <div class="pagination-info">Affichage de 1-4 sur 28 résultats</div>
                            <div class="pagination-controls">
                                <button><i class="fas fa-chevron-left"></i></button>
                                <button class="active">1</button>
                                <button>2</button>
                                <button>3</button>
                                <button><i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Popular Courses -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-star"></i> Cours Populaires
                            </div>
                            <div class="card-actions">
                                <button><i class="fas fa-plus"></i> Ajouter un cours</button>
                            </div>
                        </div>
                        <div class="course-grid">
                            <div class="course-card">
                                <div class="course-img">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="course-content">
                                    <div class="course-tags">
                                        <div class="course-tag">Développement</div>
                                        <div class="course-tag premium">Premium</div>
                                    </div>
                                    <h3 class="course-title">Développement Web Full-Stack</h3>
                                    <div class="course-info">
                                        <span><i class="fas fa-clock"></i> 48 heures</span>
                                        <span><i class="fas fa-user-graduate"></i> 345 étudiants</span>
                                    </div>
                                    <div class="course-stats">
                                        <div class="course-stat">
                                            <div class="course-stat-value">4.8</div>
                                            <div class="course-stat-label">Note</div>
                                        </div>
                                        <div class="course-stat">
                                            <div class="course-stat-value">85%</div>
                                            <div class="course-stat-label">Complétion</div>
                                        </div>
                                        <div class="course-stat">
                                            <div class="course-stat-value">45,000 FCFA</div>
                                            <div class="course-stat-label">Prix</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="course-card">
                                <div class="course-img" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="course-content">
                                    <div class="course-tags">
                                        <div class="course-tag">Data Science</div>
                                    </div>
                                    <h3 class="course-title">Introduction à la Data Science</h3>
                                    <div class="course-info">
                                        <span><i class="fas fa-clock"></i> 36 heures</span>
                                        <span><i class="fas fa-user-graduate"></i> 289 étudiants</span>
                                    </div>
                                    <div class="course-stats">
                                        <div class="course-stat">
                                            <div class="course-stat-value">4.6</div>
                                            <div class="course-stat-label">Note</div>
                                        </div>
                                        <div class="course-stat">
                                            <div class="course-stat-value">78%</div>
                                            <div class="course-stat-label">Complétion</div>
                                        </div>
                                        <div class="course-stat">
                                            <div class="course-stat-value">35,000 FCFA</div>
                                            <div class="course-stat-label">Prix</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar content area -->
                <div>
                    <!-- Calendar -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-calendar"></i> Calendrier
                            </div>
                            <div class="card-actions">
                                <button><i class="fas fa-plus"></i> Événement</button>
                            </div>
                        </div>
                        <table class="calendar">
                            <thead>
                                <tr>
                                    <th colspan="7">Avril 2025</th>
                                </tr>
                                <tr>
                                    <th>Lu</th>
                                    <th>Ma</th>
                                    <th>Me</th>
                                    <th>Je</th>
                                    <th>Ve</th>
                                    <th>Sa</th>
                                    <th>Di</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td class="event">3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>8</td>
                                    <td class="event">9</td>
                                    <td>10</td>
                                    <td>11</td>
                                    <td>12</td>
                                    <td>13</td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>15</td>
                                    <td>16</td>
                                    <td>17</td>
                                    <td class="event">18</td>
                                    <td>19</td>
                                    <td class="today">20</td>
                                </tr>
                                <tr>
                                    <td class="event">21</td>
                                    <td>22</td>
                                    <td>23</td>
                                    <td>24</td>
                                    <td>25</td>
                                    <td>26</td>
                                    <td>27</td>
                                </tr>
                                <tr>
                                    <td>28</td>
                                    <td>29</td>
                                    <td class="event">30</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Tasks -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-tasks"></i> Tâches
                            </div>
                            <div class="card-actions">
                                <button><i class="fas fa-plus"></i> Ajouter</button>
                            </div>
                        </div>
                        <div class="task-item">
                            <input type="checkbox" class="task-checkbox">
                            <div class="task-info">
                                <div class="task-title">Finaliser le nouveau cours Mobile Dev</div>
                                <div class="task-date">
                                    <i class="fas fa-calendar"></i> 22 Avril 2025
                                    <span class="task-priority priority-high">Haute</span>
                                </div>
                            </div>
                        </div>
                        <div class="task-item">
                            <input type="checkbox" class="task-checkbox">
                            <div class="task-info">
                                <div class="task-title">Revoir les demandes d'adhésion</div>
                                <div class="task-date">
                                    <i class="fas fa-calendar"></i> 21 Avril 2025
                                    <span class="task-priority priority-medium">Moyenne</span>
                                </div>
                            </div>
                        </div>
                        <div class="task-item">
                            <input type="checkbox" class="task-checkbox" checked>
                            <div class="task-info">
                                <div class="task-title" style="text-decoration: line-through;">Répondre aux e-mails des étudiants</div>
                                <div class="task-date">
                                    <i class="fas fa-calendar"></i> 20 Avril 2025
                                    <span class="task-priority priority-low">Basse</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Payments -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-money-bill-wave"></i> Paiements Récents
                            </div>
                            <div class="card-actions">
                                <button><i class="fas fa-eye"></i> Voir Tous</button>
                            </div>
                        </div>
                        <div class="payment-list">
                            <div class="payment-item">
                                <div class="payment-user">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="payment-info">
                                    <div class="payment-course">Python pour Data Science</div>
                                    <div class="payment-date">Il y a 2 heures</div>
                                </div>
                                <div class="payment-amount">35,000 FCFA</div>
                            </div>
                            <div class="payment-item">
                                <div class="payment-user">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="payment-info">
                                    <div class="payment-course">Python pour Data Science</div>
                                    <div class="payment-date">Il y a 2 heures</div>
                                </div>
                                <div class="payment-amount">35,000 FCFA</div>
                            </div>
                            <div class="payment-item">
                                <div class="payment-user">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="payment-info">
                                    <div class="payment-course">Développement Web Full-Stack</div>
                                    <div class="payment-date">Il y a 5 heures</div>
                                </div>
                                <div class="payment-amount">45,000 FCFA</div>
                            </div>
                            <div class="payment-item">
                                <div class="payment-user">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="payment-info">
                                    <div class="payment-course">UX/UI Design Fondamentaux</div>
                                    <div class="payment-date">Il y a 1 jour</div>
                                </div>
                                <div class="payment-amount">30,000 FCFA</div>
                            </div>
                            <div class="payment-item">
                                <div class="payment-user">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="payment-info">
                                    <div class="payment-course">React.js Avancé</div>
                                    <div class="payment-date">Il y a 2 jours</div>
                                </div>
                                <div class="payment-amount">40,000 FCFA</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-bell"></i> Notifications
                            </div>
                            <div class="card-actions">
                                <button><i class="fas fa-check-double"></i> Tout marquer comme lu</button>
                            </div>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item">
                                <div class="notification-icon icon-info">
                                    <i class="fas fa-info"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">5 nouveaux étudiants inscrits</div>
                                    <div class="notification-time">Il y a 30 minutes</div>
                                </div>
                                <div class="notification-actions">
                                    <span class="notification-badge"></span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon icon-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Paiement reçu pour 8 cours</div>
                                    <div class="notification-time">Il y a 2 heures</div>
                                </div>
                                <div class="notification-actions">
                                    <span class="notification-badge"></span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon icon-warning">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">3 cours nécessitent une mise à jour</div>
                                    <div class="notification-time">Il y a 5 heures</div>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon icon-danger">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Échec de paiement pour le cours UX/UI</div>
                                    <div class="notification-time">Il y a 1 jour</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <div class="fab">
        <i class="fas fa-plus"></i>
    </div>
    
    <!-- Toast Notification -->
    <div class="toast toast-success" style="display: none;">
        <div class="toast-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Succès!</div>
            <div class="toast-message">L'opération a été effectuée avec succès.</div>
        </div>
        <div class="toast-close">
            <i class="fas fa-times"></i>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Ajouter un nouveau cours</div>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Titre du cours</label>
                    <input type="text" class="form-control" placeholder="Entrez le titre du cours">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="3" placeholder="Décrivez le cours"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <select class="form-select">
                        <option>Développement Web</option>
                        <option>Développement Mobile</option>
                        <option>Data Science</option>
                        <option>UX/UI Design</option>
                        <option>Intelligence Artificielle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Prix (FCFA)</label>
                    <input type="number" class="form-control" placeholder="Ex: 35000">
                </div>
                <div class="form-group">
                    <label class="form-label">Durée (heures)</label>
                    <input type="number" class="form-control" placeholder="Ex: 36">
                </div>
                <div class="form-group">
                    <label class="form-label">Instructeur</label>
                    <select class="form-select">
                        <option>Samuel Kouassi</option>
                        <option>Aminata Diallo</option>
                        <option>Jean Konan</option>
                        <option>Fatou Sarr</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="premium">
                        <label for="premium">Cours Premium</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary close-modal">Annuler</button>
                <button class="modal-btn modal-btn-primary">Ajouter le cours</button>
            </div>
        </div>
    </div>
    
    <script>
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [
                    {
                        label: 'Revenus (FCFA)',
                        data: [3200000, 3800000, 4100000, 4500000, 4800000, 5245000, 0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderColor: '#3498db',
                        borderWidth: 3,
                        pointBackgroundColor: '#3498db',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        tension: 0.4
                    },
                    {
                        label: 'Utilisateurs',
                        data: [1500, 1800, 2100, 2300, 2600, 2845, 0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        borderColor: '#2ecc71',
                        borderWidth: 3,
                        pointBackgroundColor: '#2ecc71',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: "'Segoe UI', sans-serif"
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#2c3e50',
                        bodyColor: '#2c3e50',
                        borderColor: '#e0e0e0',
                        borderWidth: 1,
                        bodyFont: {
                            family: "'Segoe UI', sans-serif"
                        },
                        titleFont: {
                            family: "'Segoe UI', sans-serif",
                            weight: 'bold'
                        },
                        padding: 12,
                        boxPadding: 8,
                        usePointStyle: true,
                        callbacks: {
                            labelPointStyle: function(context) {
                                return {
                                    pointStyle: 'rectRounded',
                                    rotation: 0
                                };
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Segoe UI', sans-serif"
                            }
                        }
                    },
                    y: {
                        grid: {
                            borderDash: [8, 4]
                        },
                        ticks: {
                            font: {
                                family: "'Segoe UI', sans-serif"
                            },
                            callback: function(value) {
                                if (this.chart.data.datasets[0].label === 'Revenus (FCFA)') {
                                    return value >= 1000000 ? (value / 1000000).toFixed(1) + 'M' : value;
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Modal functionality
        const modalButtons = document.querySelectorAll('.add-btn, .fab');
        const modalBackdrop = document.querySelector('.modal-backdrop');
        const closeModalButtons = document.querySelectorAll('.modal-close, .close-modal');
        
        modalButtons.forEach(button => {
            button.addEventListener('click', () => {
                modalBackdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
        
        closeModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                modalBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
        
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) {
                modalBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Toast notification
        function showToast(type, title, message) {
            const toast = document.querySelector('.toast');
            
            // Remove existing classes
            toast.className = 'toast';
            
            // Add new class
            toast.classList.add(`toast-${type}`);
            
            // Update icon
            let iconClass = 'fas ';
            switch (type) {
                case 'success':
                    iconClass += 'fa-check';
                    break;
                case 'warning':
                    iconClass += 'fa-exclamation';
                    break;
                case 'error':
                    iconClass += 'fa-times';
                    break;
                case 'info':
                    iconClass += 'fa-info';
                    break;
            }
            
            toast.querySelector('.toast-icon i').className = iconClass;
            
            // Update content
            toast.querySelector('.toast-title').textContent = title;
            toast.querySelector('.toast-message').textContent = message;
            
            // Show toast
            toast.style.display = 'flex';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.style.display = 'none';
            }, 5000);
        }
        
        // Close toast on click
        document.querySelector('.toast-close').addEventListener('click', () => {
            document.querySelector('.toast').style.display = 'none';
        });
        
        // Example of showing a toast when adding a course
        document.querySelector('.modal-btn-primary').addEventListener('click', () => {
            modalBackdrop.classList.remove('active');
            document.body.style.overflow = '';
            showToast('success', 'Cours ajouté', 'Le cours a été ajouté avec succès!');
        });
        
        // Example checkbox interaction for tasks
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');
        taskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const taskTitle = e.target.closest('.task-item').querySelector('.task-title');
                if (e.target.checked) {
                    taskTitle.style.textDecoration = 'line-through';
                    taskTitle.style.color = 'var(--text-light)';
                    showToast('success', 'Tâche complétée', 'La tâche a été marquée comme terminée!');
                } else {
                    taskTitle.style.textDecoration = 'none';
                    taskTitle.style.color = 'var(--dark-color)';
                }
            });
        });
        
        // Calendar interaction
        const calendarDays = document.querySelectorAll('.calendar td');
        calendarDays.forEach(day => {
            if (day.textContent.trim()) {
                day.addEventListener('click', (e) => {
                    // Remove active class from all days
                    calendarDays.forEach(d => d.classList.remove('active'));
                    
                    // Add active class to clicked day
                    e.target.classList.add('active');
                    
                    // Show toast for event days
                    if (e.target.classList.contains('event')) {
                        showToast('info', 'Événement', `Vous avez un événement prévu pour le ${e.target.textContent} Avril 2025`);
                    }
                });
            }
        });
        
        // Action buttons in student table
        const viewButtons = document.querySelectorAll('.action-btn.view');
        const editButtons = document.querySelectorAll('.action-btn.edit');
        const deleteButtons = document.querySelectorAll('.action-btn.delete');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const studentName = e.target.closest('tr').querySelector('div[style="font-weight: bold;"]').textContent;
                showToast('info', 'Voir l\'étudiant', `Affichage des détails pour ${studentName}`);
            });
        });
        
        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const studentName = e.target.closest('tr').querySelector('div[style="font-weight: bold;"]').textContent;
                showToast('warning', 'Modifier l\'étudiant', `Vous êtes en train de modifier ${studentName}`);
            });
        });
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const studentName = e.target.closest('tr').querySelector('div[style="font-weight: bold;"]').textContent;
                if (confirm(`Êtes-vous sûr de vouloir supprimer ${studentName}?`)) {
                    e.target.closest('tr').remove();
                    showToast('error', 'Étudiant supprimé', `${studentName} a été supprimé avec succès`);
                }
            });
        });
        
        // Responsive sidebar toggle for mobile
        function createResponsiveToggle() {
            const toggle = document.createElement('div');
            toggle.className = 'sidebar-toggle';
            toggle.innerHTML = '<i class="fas fa-bars"></i>';
            toggle.style.cssText = `
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background-color: var(--dark-color);
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 5px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                display: none;
                box-shadow: var(--box-shadow);
            `;
            
            document.body.appendChild(toggle);
            
            toggle.addEventListener('click', () => {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('active');
                
                if (sidebar.classList.contains('active')) {
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                }
            });
            
            // Media query for mobile
            const mediaQuery = window.matchMedia('(max-width: 768px)');
            
            function handleScreenChange(e) {
                if (e.matches) {
                    // Mobile view
                    toggle.style.display = 'flex';
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.style.transform = 'translateX(-100%)';
                    document.querySelector('.main-content').style.marginLeft = '0';
                } else {
                    // Desktop view
                    toggle.style.display = 'none';
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.style.transform = 'translateX(0)';
                    if (window.innerWidth <= 992) {
                        document.querySelector('.main-content').style.marginLeft = '80px';
                    } else {
                        document.querySelector('.main-content').style.marginLeft = '260px';
                    }
                }
            }
            
            // Initial check
            handleScreenChange(mediaQuery);
            
            // Add listener
            mediaQuery.addEventListener('change', handleScreenChange);
        }
        
        // Initialize responsive toggle
        createResponsiveToggle();
        
        // Example of updating stats periodically
        function updateStats() {
            const userCount = parseInt(document.querySelector('.quick-stats .quick-stat:nth-child(1) .quick-stat-value').textContent.replace(',', ''));
            const randomIncrement = Math.floor(Math.random() * 10) + 1;
            const newUserCount = userCount + randomIncrement;
            
            document.querySelector('.quick-stats .quick-stat:nth-child(1) .quick-stat-value').textContent = newUserCount.toLocaleString();
            
            showToast('info', 'Mise à jour des statistiques', `${randomIncrement} nouveaux utilisateurs viennent de s'inscrire!`);
        }
        
        // Update stats every 2 minutes (simulated real-time updates)
        // Commented out to avoid confusion, uncomment for demo
        // setInterval(updateStats, 120000);
        
        // Initialize tooltips (if using Bootstrap or custom tooltip implementation)
        function initTooltips() {
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            tooltipElements.forEach(el => {
                // Implementation would depend on your tooltip library or custom code
                console.log('Tooltip initialized for:', el);
            });
        }
        
        // Call initial functions
        document.addEventListener('DOMContentLoaded', function() {
            // Any initialization code
            console.log('AfriCode Admin Dashboard loaded successfully');
        });
    </script>
</body>
</html>