<?php
namespace App\Controllers;

class HomeController extends Controller
{


    public function show($request, $response, $args) {
        $reportLinks = $this->loadReportsLinks();
        $dashboardLinks = $this->loadDashboardLinks();
        return $this->render($response, ['reportLinks' => $reportLinks, 'dashboardLinks' => $dashboardLinks]);
    }

    private function loadReportsLinks() {
        return $this->executeQuery(
            "SELECT report_name as name, repprt_url as url FROM user_reports where username = ?");
    }

    private function loadDashboardLinks() {
        return $this->executeQuery(
            "SELECT dashboard_name as name, dashboard_url as url FROM user_dashboards where username = ?");
    }

    private function executeQuery($query) {
        $username = $_SESSION['username'];
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetchAll();
    }

    protected function getView()
    {
        return 'home.phtml';
    }
}