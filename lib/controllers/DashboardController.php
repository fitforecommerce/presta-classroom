<?php

class DashboardController extends MainController
{
    public function default_action()
    {
        return 'showDashboard';
    }
    public function showDashboard()
    {
        $this->s()->display('dashboard.tpl');
        return true;

        $dv = $downloader->available_versions();
        $this->render(
			'dashboard/index.html.twig', 
			array('versions' => $dv)
		);
    }
	protected function requires_login()
	{
		return true;
	}
}
?>