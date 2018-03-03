<?php

namespace App\Controllers;


class UploadController extends Controller
{

    public function show($request, $response, $args) {
        $locations = $this->loaLocations();
        return $this->render($response, [ 'locations' => $locations ]);
    }

    public function upload($request, $response, $args) {
        $file = $request->getUploadedFiles()['file'];
        $form = $request->getParsedBody();
        $location = $this->getLocationPath($form['location']);
        if($file->getError() === UPLOAD_ERR_OK) {
            $path = $location['path'];
            mkdir($path, 0755, true);
            $file->moveTo( $path . $file->getClientFilename());
            return $response->withJson(['status' => 'uploaded']);
        } else {
            $response->withStatus(500);
        }
    }

    private function loaLocations() {
        $username = $_SESSION['username'];
        $stmt = $this->db->prepare("SELECT id, file_type_name FROM user_file_locations where username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchAll();
    }

    private function getLocationPath($id) {
        $stmt = $this->db->prepare("SELECT file_type_path AS path FROM user_file_locations where id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    protected function getView()
    {
        return 'upload.phtml';
    }
}